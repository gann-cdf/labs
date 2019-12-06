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
if (empty($_REQUEST['email']) === false) {
    $email = $_REQUEST['email'];
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

    $ready = true;
    if (empty($_REQUEST) === false) {
        if (empty($email) || preg_match('/^([a-z0-9]+)@gannacademy.org$/i', $_REQUEST['email'], $match) == false) {
            echo <<<EOT
<div class="alert alert-warning" role="alert">
    You must use your Gann email address!
</div>
EOT;
            $ready = false;
        }
        if (empty($_FILES['upload']['name'])) {
            echo <<<EOT
<div class="alert alert-warning" role="alert">
    Please select a file to upload!
</div>
EOT;
            $ready = false;
        }

        if ($ready) {
            setcookie('email', $_REQUEST['email']);
            $sep = $strings['QUEUE_NAME_SEPARATOR'];
            $location = $strings['QUEUE_CN'];
            $filenames = [];
            $errors = [];
            for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
                $filename = date('Y-m-d_H-i-s ') . $sep . strtolower($match[1]) . $sep . $_FILES['upload']['name'][$i];
                if (move_uploaded_file($_FILES['upload']['tmp_name'][$i], $strings['QUEUE_DIR'] . "/$filename")) {
                    $filenames[] = $filename;
                } else {
                    $errors = $_FILES['upload']['name'][$i];
                }
            }
            if (count($errors)) {
                $list = implode(', ', $errors);
                echo <<<EOT
<div class="alert alert-danger" role="alert">
    There was an error uploading $errors.
</div>
EOT;
            }
            if (count($filenames)) {
                $list = implode(', ', $filenames);
                $wasWere = (count($filenames) > 1 ? 'were' : 'was');
                $isAre = (count($filenames) > 1 ? 'are' : 'is');
                echo <<<EOT
<div class="alert alert-success" role="alert">
    $list $wasWere uploaded to $location and $isAre now accessible there.
</div>
EOT;
            }
        }

    }

    ?>
    <form class="needs-validation" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'] ?>" method="post"
          novalidate>
        <div class="form-group">
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= $email ?>"
                   placeholder="Gann email address">
            <small>Email address</small>
        </div>
        <div class="form-group">
            <div class="custom-file">
                <input type="file" multiple class="custom-file-input" id="upload" name="upload[]">
                <label class="custom-file-label" for="upload">Choose file</label>
            </div>
            <small>Files to add to queue</small>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Add to Queue</button>
        </div>
    </form>
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
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    })
</script>
</body>
</html>
