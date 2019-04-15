<?php
function buildLinkedList(array $primary, callable $makeLink, $filterCol = null, $filterVal = null) {
    $linked = new ArrayIterator();
    $filterVal = trim($filterVal);
    foreach ($primary as $key => $row) {
        if ($filterCol) {
            if (trim($row[$filterCol]) == $filterVal) {
                $linked->offsetSet($makeLink($row), $key);
            }
        }
        else {
            $linked->offsetSet($makeLink($row), $key);
        }
    }
    $linked->ksort();
    return $linked;
}


function buildDoublyLinkedList(ArrayIterator $linked) {
    $double = new SplDoublyLinkedList();
    foreach ($linked as $key => $value) {
        $double->push($value);
    }
    return $double;
}
