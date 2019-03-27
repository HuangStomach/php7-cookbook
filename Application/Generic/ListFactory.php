<?php
namespace Application\Generic;

use PDO;
use Exception;
use Application\Databas\ { Connection, ConnectionAwareInterface };

class ListFactory {
    const ERROR_NAME = 'Class must be Connection Aware';
    public static function factory (ConnectionAwareInterface $class, $dbParams) {
        if ($class instanceof ConnectionAwareInterface) {
            $class->setConnection(new Connection($dbParams));
            return $class;
        }
        else {
            throw new Exception(self::ERROR_AWARE);
        }
        return FALSE;
    }
}