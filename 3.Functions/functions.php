<?php

function someName($a) {
    $result = 'INIT';
    $result .= ' and also ' . $a;
    return $result;
}

function someOtherName($a, $b = NULL) {
    $result = 0;
    $result += $a;
    $result += $b ?? 0;
    return $result;
}

function someInfinite(...$params) {
    return var_export($params, TRUE);
}

function someDirScan($dir) {
    static $list = [];
    $list = glob($dir . DIRECTORY_SEPARATOR . '*');
    foreach ($list as $item) {
        if (is_dir($item)) $list = array_merge($list, someDirScan($item));
    }
    return $list;
}