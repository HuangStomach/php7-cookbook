<?php

require '../Autoload/Loader.php';
Application\Autoload\Loader::init(__DIR__ . '/../..');

$deep = new Application\Web\Deep();
foreach ($deep->scan('http://unlikelysource.com/', 'img') as $item) {
    $src = $item['attributes']['src'] ?? NULL;
    if ($src && (stripos($src, 'png') || stripos($src, 'jpg'))) {
        printf("<img src=\"%s\">\n", $src);
    }
}
