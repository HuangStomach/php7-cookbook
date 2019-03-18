<?php

require '../Autoload/Loader.php';
Application\Autoload\Loader::init(__DIR__ . '/../..');

$vac = new Application\Web\Hoover();

var_dump($vac->getTags('http://oreilly.com/', 'a'));
