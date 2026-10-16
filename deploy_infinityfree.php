<?php
/**
 * Automated InfinityFree FTP Deployment Script
 * Business Connect Platform
 */

$configFile = __DIR__ . '/infinityfree_config.json';
if (!file_exists($configFile)) {
    echo "
[ERROR] Missing configuration file: infinityfree_config.json
";
    echo "Please copy 'infinityfree_config.json.example' to 'infinityfree_config.json' and fill in your InfinityFree credentials.
";
    echo "This file is git-ignored and ensures your credentials remain private.

";
    exit(1);
}

$config = json_decode(file_get_contents($configFile), true);
if (!$config) {
    echo "
[ERROR] Invalid JSON in infinityfree_config.json

";
    exit(1);
}

$requiredKeys = ['APP_URL', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'FTP_HOST', 'FTP_USERNAME', 'FTP_PASSWORD'];
foreach ($requiredKeys as $key) {
    if (empty($config[$key])) {
        echo "
[ERROR] Missing required configuration key: {$key} in infinityfree_config.json

";
        exit(1);
    }
}

$bundleDir = 'E:/business-connect-infinityfree-htdocs';
$zipFile   = 'E:/business-connect-infinityfree-htdocs.zip';

if (!is_dir($bundleDir) || !file_exists($zipFile)) {
    echo "
[ERROR] Bundled deployment files not found in E:/

";
    exit(1);
}

echo "
=======================================================
";
echo "  Business Connect - InfinityFree Deployment
";
echo "=======================================================

";

// 1. Generate Production .env
echo "[1/4] Preparing production environment configuration...
";
$appUrl = rtrim($config['APP_URL'], '/');
$envContent = <<<EOT
APP_NAME="Business Connect"
APP_ENV=production
APP_KEY=base64:vHHhX8C4c09wqS6tEq8CwC2ONbPJ8Oi+lhIPg1YIF/A=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL={$appUrl}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$config['DB_HOST']}
DB_PORT=3306
DB_DATABASE={$config['DB_DATABASE']}
DB_USERNAME={$config['DB_USERNAME']}
DB_PASSWORD={$config['DB_PASSWORD']}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
CACHE_STORE=file

EOT;

$tempEnv = __DIR__ . '/.env.infinityfree.temp';
file_put_contents($tempEnv, $envContent);
echo "      Production .env prepared successfully.

";

// 2. Connect to FTP
$ftpHost = $config['FTP_HOST'];
$ftpPort = (int)($config['FTP_PORT'] ?? 21);
$ftpUser = $config['FTP_USERNAME'];
$ftpPass = $config['FTP_PASSWORD'];

echo "[2/4] Connecting to InfinityFree FTP ({$ftpHost}:{$ftpPort})...
";
$conn = @ftp_connect($ftpHost, $ftpPort, 30);
if (!$conn) {
    echo "      [FAIL] Could not connect to FTP host {$ftpHost}:{$ftpPort}
";
    echo "      Please verify FTP Host and Port.

";
    @unlink($tempEnv);
    exit(1);
}

if (!@ftp_login($conn, $ftpUser, $ftpPass)) {
    echo "      [FAIL] FTP login failed for user {$ftpUser}
";
    echo "      Please verify FTP Username and Password.

";
    ftp_close($conn);
    @unlink($tempEnv);
    exit(1);
}

ftp_pasv($conn, true);
echo "      FTP Authentication successful! Passive mode active.

";

// 3. Determine target htdocs path
echo "[3/4] Checking remote htdocs directory...
";
$list = (array)ftp_nlist($conn, '.');
$remoteTarget = 'htdocs';
if (!in_array('htdocs', $list) && !in_array('./htdocs', $list)) {
    if (in_array('index.php', $list) || in_array('index.html', $list)) {
        $remoteTarget = '.';
    } else {
        @ftp_mkdir($conn, 'htdocs');
    }
}

// 4. Fast Zip Upload & Extractor Setup
echo "[4/4] Deploying files to InfinityFree...
";
$deploySecret = bin2hex(random_bytes(16));
$extractorCode = '<?php' . "
"
    . 'if (empty($_GET["key"]) || $_GET["key"] !== "' . $deploySecret . '") {' . "
"
    . '    http_response_code(403); die("Forbidden");' . "
"
    . '}' . "
"
    . '$zip = new ZipArchive();' . "
"
    . 'if ($zip->open(__DIR__ . "/business_connect_bundle.zip") === TRUE) {' . "
"
    . '    $zip->extractTo(__DIR__ . "/");' . "
"
    . '    $zip->close();' . "
"
    . '    @unlink(__DIR__ . "/business_connect_bundle.zip");' . "
"
    . '    @unlink(__FILE__);' . "
"
    . '    echo "SUCCESS_UNZIPPED";' . "
"
    . '} else {' . "
"
    . '    echo "FAILED_OPEN_ZIP";' . "
"
    . '}' . "
";

$extractorFile = __DIR__ . '/extract_infinityfree.php';
file_put_contents($extractorFile, $extractorCode);

$remotePrefix = ($remoteTarget === '.') ? '' : 'htdocs/';

echo "      Uploading package zip (13.4 MB)...
";
$zipUpload = @ftp_put($conn, $remotePrefix . 'business_connect_bundle.zip', $zipFile, FTP_BINARY);
if (!$zipUpload) {
    echo "      [WARN] Zip upload failed. Falling back to direct recursive upload...
";
    function uploadDir($conn, $localDir, $remoteDir) {
        $items = scandir($localDir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $lp = $localDir . '/' . $item;
            $rp = $remoteDir . '/' . $item;
            if (is_dir($lp)) {
                @ftp_mkdir($conn, $rp);
                uploadDir($conn, $lp, $rp);
            } else {
                @ftp_put($conn, $rp, $lp, FTP_BINARY);
                echo "      Uploaded: {$rp}
";
            }
        }
    }
    uploadDir($conn, $bundleDir, $remotePrefix ? rtrim($remotePrefix, '/') : '.');
} else {
    echo "      Zip upload complete!
";
    echo "      Uploading extractor and .env...
";
    @ftp_put($conn, $remotePrefix . 'extract_infinityfree.php', $extractorFile, FTP_BINARY);
}

// Always upload .env to ensure fresh production config
@ftp_put($conn, $remotePrefix . '.env', $tempEnv, FTP_BINARY);

ftp_close($conn);
@unlink($tempEnv);
@unlink($extractorFile);

echo "
=======================================================
";
echo "  Files Uploaded Successfully to InfinityFree!
";
echo "=======================================================

";

if ($zipUpload) {
    $extractUrl = $appUrl . '/extract_infinityfree.php?key=' . $deploySecret;
    echo "CRITICAL STEP TO COMPLETE EXTRACTION:
";
    echo "Open the following URL in your browser to extract the application:

";
    echo "   >>  {$extractUrl}  <<

";
    echo "(You will see 'SUCCESS_UNZIPPED'. The script and zip will automatically delete themselves!)

";
}

echo "DATABASE IMPORT INSTRUCTIONS:
";
echo "1. Log into InfinityFree: https://dash.infinityfree.com/
";
echo "2. Open Control Panel -> MySQL Databases -> phpMyAdmin
";
echo "3. Click your database: {$config['DB_DATABASE']}
";
echo "4. Click 'Import' tab -> Choose File -> Select:
";
echo "   E:\business-connect\backend\database\schema_infinityfree.sql
";
echo "5. Click 'Go' to import all tables and seed records.

";
echo "6. Visit your live site:
";
echo "   >>  {$config['APP_URL']}  <<

";
