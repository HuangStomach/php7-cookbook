<?php

function htmlList($iterator) {
    $output = '<ul>';
    while ($value = $iterator->current()) {
        $output .= '<li>' . $value . '</li>';
        $iterator->next();
    }
    $output .= '</ul>';
    return $output;
}

function fetchCountryName($sql, $connection) {
    $iterator = new ArrayIterator();
    $stmt = $connection->pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $iterator->append($row['name']);
    }
    return $iterator;
}

function nameFilterIterator($innerIterator, $name) {
    if (!$name) return $innerIterator;
    $name = trim($name);
    $iterator = new CallbackFilterIterator($innerIterator, function ($current, $key, $iterator) use ($name) {
        $pattern = '/' . $name . '/i';
        return (bool)preg_match($pattern, $current);
    });
    return $iterator;
}

function pageinationCountryName($sql, $connection, $offset, $limit) {
    $pagination = new LimitIterator(fetchCountryName($sql, $connection), $offset, $limit);
    return $pagination;
}

function showElements($iterator) {
    foreach ($iterator as $item) echo $item . ' ';
    echo PHP_EOL;
}

$a = range('A', 'Z');
$i = new ArrayIterator($a);
showElements($i);

$f = new class($i) extends FilterIterator {
    public function accept() {
        $current = $this->current();
        return !(ord($current) & 1);
    }
};
showElements($f);

$l = new LimitIterator($f, 2, 6);
showElements($l);

function fetchAllAssoc($sql, $connection) {
    $iterator = new ArrayIterator();
    $stmt = $connection->pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $iterator->append($row);
    }
    return $iterator;
}
