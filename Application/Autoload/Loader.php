<?php
namespace Application\Autoload;

class Loader {
    static $dirs = [];
    static $registered = 0;

    public function __construct($dirs = []) {
        self::init($dirs);
    }

    public static function init($dirs = []) {
        if ($dirs) self::addDirs($dirs);

        if (self::$registered == 0) {
            spl_autoload_register(__CLASS__ . '::autoload');
            self::$registered++;
        }
    }

    public static function autoLoad($class) {
        $success = false;
        // DIRECTORY_SEPARATOR: 系统内置常量 表示不同系统下目录的分隔符
        $fn = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        foreach (self::$dirs as $start) {
            $file = $start . DIRECTORY_SEPARATOR . $fn;
            var_dump($file);
            if (self::loadFile($file)) {
                $success = true;
                break;
            }
        }

        if (!$success) {
            if (!self::loadFile(__DIR__ . DIRECTORY_SEPARATOR . $fn)) {
                throw new \Exception('unable to load file ' . $class);
            }
        }
        
        return $success;
    }

    public static function addDirs($dirs) {
        if (is_array($dirs)) {
            self::$dirs = array_merge(self::$dirs, $dirs);
        }
        else {
            self::$dirs[] = $dirs;
        }
    }

    protected static function loadFile($file) {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
}