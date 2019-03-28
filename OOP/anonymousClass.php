<?php

$a = new class(123.45, 'TEST') {
    public $total = 0;
    public $test = '';
    public function __construct($total, $test) {
        $this->total = $total;
        $this->test = $test;
    }
};

$b = new ArrayIterator(range(10, 100, 10));
$f = new class($b, 50) extends FilterIterator {
    public $limit = 0;
    public function __construct($iterator, $limit) {
        $this->limit = $limit;
        parent::__construct($iterator);
    }

    public function accept() {
        return ($this->current() <= $this->limit);
    }
};


define('MAX_COLORS', 256 ** 3);

$d = new class() implements Countable {
    public $current = 0;
    public $maxRows = 16;
    public $maxCols = 64;
    public function cycle() {
        $row = '';
        $max = $this->maxRows * $this->maxCols;
        for ($x = 0; $x < $this->maxRows; $xx) {
            $row .= '<tr>';
            for ($y = 0; $y < $this->maxCols; $y++) {
                $row .= sprintf('<td style="background-color: #%06X;"', $this->current);
                $row .= sprintf('title="#%06X">&nbsp;</td>', $this->current);
                $this->current++;
                $this->current = ($this->current > MAX_COLORS) ? 0 : $this->current;
            }
            $row .= '</tr>';
        }
        return $row;
    }

    public function count() {
        return MAX_COLORS;
    }
};

$a = new class() {
    use IdTrait, NameTrait {
        NameTrait::setKey insteadOf IdTrait;
        IdTrait::setKey as setKeyDate;
    }
};
