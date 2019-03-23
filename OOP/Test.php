<?php
declare(strict_types = 1);

class Test {
    protected $test = 'TEST';

    public function getTest() :string {
        return $this->test;
    }

    public function setTest(string $test) {
        $this->test = $test;
        return $this;
    }
}