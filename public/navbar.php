<?php

$printers = $db->query('SELECT * FROM `3d_printers`');
$statement = $db->prepare("SELECT * FROM `strings` WHERE `category` = 'navbar.php'");
$result = $statement->execute();
$rows = $statement->fetchAll();
foreach ($rows as $row) {
    $strings[$row['id']] = $row['value'];
}

?><!DOCTYPE html>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><?= $strings['NAVBAR_BRAND'] ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="queue.php"><i class="fas fa-folder-plus"></i> <?= $strings['QUEUE_TITLE'] ?>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cubes"></i> 3D Printers
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                    foreach ($printers as $printer) {
                        echo <<<EOT
<a class="dropdown-item" target="_blank" href="{$printer['dashboard']}">{$printer['cn']}</a>
EOT;
                    }
                    ?>
                </div>
            </li>
        </ul>
    </div>
</nav>
