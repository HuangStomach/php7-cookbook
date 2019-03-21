<?php
declare(strict_types = 1);

function someTypeHint(Array $a, DateTime $t, Callable $c) {
    $message = '';
    $message .= 'Array Count: ' . count($a) . PHP_EOL;
    $message .= 'Date: ' . $t->format('Y-m-d') . PHP_EOL;
    $message .= 'Callable Return: ' . $c() . PHP_EOL;
    return $message;
}

function someScalarHint(bool $b, int $i, float $f, string $s) {
    return sprintf("\n%20s : %5\n%20s : %5d\n%20s : %5.2f\n%20s : %20s\n\n",
    'Boolean', ($b ? 'True' : 'False'),
    'Integer', $i,
    'Float', $f,
    'String', $s);
}
