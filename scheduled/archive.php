<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::create(__DIR__ . '/..')->load();

$queue = getenv('QUEUE_DIR');
$weekly = getenv('QUEUE_WEEKLY_DIR');

function emptyDir($dir)
{
    foreach (array_diff(scandir($dir), ['.', '..']) as $file) {
        $path = "$dir/$file";
        if (is_dir($path)) {
            emptyDir($path);
            rmdir($path);
        } else {
            unlink($path);
        }
    }
}

if (!file_exists($weekly)) {
    if (count(scandir($queue)) > 2) {
        mkdir($weekly);
    }
} else {
    emptyDir($weekly);
}

foreach (array_diff(scandir($queue), ['.', '..']) as $file) {
    $path = "$queue/$file";
    if ($path != $weekly) {
        rename($path, "$weekly/$file");
    }
}
