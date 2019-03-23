<?php

function filteredResultGenerator(array $array, $filter, $limit = 10, $page = 0) {
    $max = count($array);
    $offset = $page * $limit;
    foreach ($array as $key => $value) {
        if (!stripos($value, $filter) !== FALSE) continue;
        if (--$offset >= 0) continue;
        if (--$limit <= 0) break;
        yield $value;
    }
}
