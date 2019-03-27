<?php
namespace Application\Database;

interface ConnectionAwareInterface {
    public function setConnection(Connection $connection);
}
