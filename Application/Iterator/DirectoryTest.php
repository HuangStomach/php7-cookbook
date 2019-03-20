<?php
require '../Autoload/Loader.php';
Application\Autoload\Loader::init(__DIR__ . '/../..');

$directory = new Application\Iterator\Directory('../../');

try {
    foreach ($directory->ls('*.php') as $info) {
        echo $info;
    }

    foreach ($directory->dir('*.php') as $info) {
        echo $info;
    }
}
catch (\Throwable $e) {
    echo $e->getMessage();
}
