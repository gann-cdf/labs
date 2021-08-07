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

$email = "";
if (isset($_REQUEST['email']) && empty($_REQUEST['email'])) {
    unset($_COOKIE['email']);
    setcookie('email', null);
} elseif (empty($_REQUEST['email']) === false) {
    $email = $_REQUEST['email'];
    setcookie('email', $email, time() + getenv('COOKIE_EXPIRY'));
} elseif (empty($_COOKIE['email']) === false) {
    $email = $_COOKIE['email'];
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $strings['QUEUE_TITLE'] ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css"
          integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/<?= $strings['FONT_AWESOME'] ?>.js" crossorigin="anonymous"></script>
    <style>
        body, html {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: table;
            empty-cells: show;
            border-collapse: collapse;
            width: 100%;
            max-width: 8in;
            height: 100%;
            margin: auto;
        }

        .container {
            display: table-row;
            height: 100%;
            overflow: hidden;
        }

        iframe {
            border: none;
            display: block;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php
    if (empty($strings['QUEUE_ANNOUNCEMENT']) === false) {
        echo <<<EOT
    <div class="alert alert-{$strings['QUEUE_ANNOUNCEMENT_LEVEL']}" role="alert">
        {$strings['QUEUE_ANNOUNCEMENT']}
    </div>
EOT;
    }
    ?>
    <div class="container">
        <iframe src="/~sbattis/octoprint/"></iframe>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"
        integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4"
        crossorigin="anonymous"></script>
</body>
</html>
