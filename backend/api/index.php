<?php

// 1. Prepare writable directory structure in /tmp for AWS Lambda / Vercel Serverless
$storagePaths = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// 2. Set storage and view paths for Laravel
putenv('APP_STORAGE=/tmp/storage');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// 3. Forward request to Laravel entrypoint
require __DIR__ . '/../public/index.php';
