<?php

abstract class Base {
    protected $id;
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public abstract function validate();
}

class Customer extends Base {
    protected $name;
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function validate() {
        $valid = 0;
        $count = count(get_object_vars($this));
        if (!empty($this->id) && is_int($this->id)) $valid++;
        if (!empty($this->name) && preg_match('/[a-z0-9]/i', $this->name)) $valid++;
        return $valid == $count;
    }
}