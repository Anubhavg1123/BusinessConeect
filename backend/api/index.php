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

// 2. Set dynamic cache, storage, and view paths for serverless Laravel
putenv('APP_STORAGE=/tmp/storage');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_PACKAGES_CACHE=/tmp/storage/framework/packages.php');
putenv('APP_SERVICES_CACHE=/tmp/storage/framework/services.php');
putenv('APP_CONFIG_CACHE=/tmp/storage/framework/config.php');
putenv('APP_ROUTES_CACHE=/tmp/storage/framework/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/storage/framework/events.php');

// 3. Forward request to Laravel entrypoint
require __DIR__ . '/../public/index.php';
