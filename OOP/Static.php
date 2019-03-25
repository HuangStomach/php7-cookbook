<?php 
class Test {
    public static $test = 'Test';
    public static function getEarlyTest() {
        return self::$test;
    }

    public static function getLateTest() {
        return static::$test;
    }
}

class Child extends Test {
    public static $test = 'Child';

    public static function factory($driver, $dbname, $host, $user, $pwd, array $options = []) {
        $dsn = sprintf('%s:dbname=%s;host=%s', $driver, $dbname, $host);
        try {
            return new PDO($dsn, $user, $pwd, $options);
        }
        catch (PDOException $e) {
            error_log($e->getMessage);
        }
    }
}