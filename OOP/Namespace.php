<?php
declare(strict_types=1);
namespace Application\Entity;

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

class Name {
}

class Profile {
}
