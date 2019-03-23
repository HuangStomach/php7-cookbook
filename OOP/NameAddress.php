<?php
declare(strict_types = 1);

class Name {
    protected $name = 'TEST';

    public function getName() :string {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }
}

class Address {
    protected $address = 'TEST';

    public function getAddress() :string {
        return $this->address;
    }

    public function setAddress(string $address) {
        $this->address = $address;
        return $this;
    }
}