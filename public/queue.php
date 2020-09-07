<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../drupal.php';

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
    setcookie('email', $email);
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
        #queues {
        }

        #queues iframe {
            border: none;
            margin: 1rem;
            height: 25vh;
            width: 100%;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="jumbotron">
        <h1><i class="fas fa-folder-plus"></i> <?= $strings['QUEUE_TITLE'] ?></h1>
        <hr>
        <p><?= $strings['QUEUE_SUBTITLE'] ?></p>
    </div>

    <?php

    if (empty($strings['QUEUE_ANNOUNCEMENT']) === false) {
        echo <<<EOT
    <div class="alert alert-{$strings['QUEUE_ANNOUNCEMENT_LEVEL']}" role="alert">
        {$strings['QUEUE_ANNOUNCEMENT']}
    </div>
EOT;
    }

    if (empty($email)) {
        echo <<<EOT
    <form class="needs-validation" enctype="multipart/form-data" action="{$_SERVER['PHP_SELF']}" method="post"
          novalidate>
        <div class="form-group">
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="Gann email address">
            <small>Email address</small>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
    </form>
EOT;
    } else {
        $gannUser = preg_replace('/([^@]+)@gannacademy.org/', '$1', $email);
        $maxSize = pretty_file_upload_max_size();
        echo <<<EOT
    <div class="container">
        <div class="row">
            <form id="signout" method="post" class="form-inline">
                <label>Signed in as {$email}.&nbsp;</label>
                <button class="btn btn-outline-primary" type="submit" name="email" value="">Sign Out</button>
            </form>
            </div>
        <div class="row">Maximum file upload size is {$maxSize}.</div>
    </div>
    <div class="container" id="queues">
        <div class="flex-row">
            <iframe src="/~sbattis/octoprint/upload/3dprint/{$gannUser}?t=Add to 3D Printing Queue"></iframe>
            <iframe src="/~sbattis/octoprint/upload/laser/{$gannUser}?t=Add to Laser Cutting Queue"></iframe>
            <iframe src="/~sbattis/octoprint/upload/qdrive/{$gannUser}?t=Upload to Q: Drive&c=no"></iframe>
        </div>
    </div>
EOT;
    }
    ?>
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
