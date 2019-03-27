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

class Registry {
    protected static $instance = NULL;
    protected $registry = [];

    private static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get($key) {
        return $this->registry[$key] ?? NULL;
    }

    public function __set($key, $value) {
        $this->registry[$key] = $value;
    }
}

class Test {
    public const TEST_WHOLE_WORLD = 'visible.everywhere';
    protected const TEST_INHERITED = 'visible.in.child.classes';
    private const TEST_LOCAL = 'local.to.class.Test.only';

    public static function getTestInherited() {
        return static::TEST_INHERITED;
    }

    public static function getTestLocal() {
        return static::TEST_LOCAL;
    }
}
