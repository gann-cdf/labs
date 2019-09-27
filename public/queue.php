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

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $strings['QUEUE_TITLE'] ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css"
          integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="jumbotron">
        <h1><?= $strings['QUEUE_TITLE'] ?></h1>
        <hr>
        <p><?= $strings['QUEUE_SUBTITLE'] ?></p>
    </div>

    <?php

    if (!empty($_REQUEST['email']) && preg_match('/^([a-z0-9]+)@gannacademy.org$/i', $_REQUEST['email'], $match) && !empty($_FILES['upload']['tmp_name'])) {
        setcookie('email', $_REQUEST['email']);
        $sep = $strings['QUEUE_NAME_SEPARATOR'];
        $location = $strings['QUEUE_CN'];
        $filename = date('Y-m-d_H-i-s ') . $sep . strtolower($match[1]) . $sep . $_FILES['upload']['name'];
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $strings['QUEUE_DIR'] . "/$filename")) {
            echo <<<EOT
<div class="alert alert-success" role="alert">
    <code>$filename</code> was uploaded to $location and is now accessible there.
</div>
EOT;

        } else {
            echo <<<EOT
<div class="alert alert-danger" role="alert">
    Bad things happened!
</div>
EOT;

        }
    } elseif (!empty($_FILES['upload']['name'])) {
        echo <<<EOT
<div class="alert alert-warning" role="alert">
    You must use your Gann email address!
</div>
EOT;
    }

    ?>
    <form class="needs-validation" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'] ?>" method="post"
          novalidate>
        <div class="form-group">
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= (empty($_COOKIE['email']) ? $_REQUEST['email'] : $_COOKIE['email']) ?>"
                   placeholder="Gann email address">
            <small>Email address</small>
        </div>
        <div class="form-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="upload" name="upload">
                <label class="custom-file-label" for="upload">Choose file</label>
            </div>
            <small>File to add to queue</small>
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
