<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

Dotenv::create(__DIR__ . '/..')->load();

$db = new PDO(getenv('DB') . ':host' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USER'), getenv('DB_PASSWORD'));

$statement = $db->prepare("SELECT * FROM `strings` WHERE `category` = 'queue.php'");
$result = $statement->execute();
$rows = $statement->fetchAll();
$strings = [];
foreach ($rows as $row) {
    $strings[$row['id']] = $row['value'];
}


$queue = $strings['QUEUE_DIR'];
$archive = $strings['QUEUE_ARCHIVE_DIR'];

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

if (!file_exists($archive)) {
    if (count(scandir($queue)) > 2) {
        mkdir($archive);
    }
} else {
    emptyDir($archive);
}

foreach (array_diff(scandir($queue), ['.', '..']) as $file) {
    $path = "$queue/$file";
    if ($path != $archive && $file != '.DAV') {
        rename($path, "$archive/$file");
    }
}
