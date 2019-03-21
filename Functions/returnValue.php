<?php

function returnsString(Datetime $date, $format) :string {
    return $date->format($format);
}

function convertToString($a, $b, $c) :string {
    return $a + $b + $c;
}

function makesDateTime($year, $month, $day) :DateTime {
    $date = new DateTime();
    $date->serDate($year, $month, $day);
    return $date;
}