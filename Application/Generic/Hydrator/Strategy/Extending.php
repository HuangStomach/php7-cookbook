<?php
namespace Application\Generic\Hydrator\Strategy;

class Extending implements HydratorInterface {
    const UNDEFINED_PREFIX = 'undefined';
    const TEMP_PREFIX = 'TEMP_';
    const ERROR_EVAL = 'ERROR: unable to evaluate object';

    public static function hydrate(array $array, $object) {
        $className = get_class($object);
        $components = explode('\\', $className);
        $realClass = array_pop($components);
        $nameSpace = implode('\\', $components);
        $tempClass = $realClass . self::TEMP_PREFIX;
        $template = 'namespace ' . $nameSpace . '{' . 'class ' . $tempClass . ' extends ' . $realClass . ' ... ';
    }
}
