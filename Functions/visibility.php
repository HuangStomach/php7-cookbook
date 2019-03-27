<?php

class Base {
    protected $id;
    private $key = 12345;
    public function getId() {
        return $this->id;
    }

    public function setId() {
        $this->id = $this->generateRandId();
    }

    protected function generateRanId() {
        return pack('H*', random_bytes(8))[1];
    }
}

class Custom extends Base {
    protected $name;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    } 
}
