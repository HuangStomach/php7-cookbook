<?php
namespace Application\Generic\Hydrator\Strategy;

class PublicProps implements HydratorInterface {
    public static function hydrate(array $array, $object) {
        $properyList = array_keys(get_class_vars(get_class($object)));
        foreach ($properyList as $property) {
            $object->$property = $array[$property] ?? null;
        }
        return $object;
    }

    public static function extract($object) {
        $array = [];
        $properyList = array_keys(get_class_vars(get_class($object)));
        foreach ($properyList as $property) {
            $array[$property] = $object->$property;
        }
        return $array;
    }
}