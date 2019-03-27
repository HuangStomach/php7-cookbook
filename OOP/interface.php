<?php

interface ConnectionAwareInterface {
    public function setConnection(Connection $connection);
}

class ContryList implements ConnectionAwareInterface {
    protected $connection;
    public function setConnection(Connection $connection) {
        $this->connection = $connection;
    }

    public function list() {
        $list = [];
        $stmt = $this->connection->pdo->query('SELECT iso3, name FROM iso_country_codes');
        while ($country = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $list[$country['iso3']] = $country['name'];
        }
        return $list;
    }
}

class CustomerList implements ConnectionAwareInterface {
    protected $connection;
    public function setConnection(Connection $connection) {
        $this->connection = $connection;
    }

    public function list() {
        $list = [];
        $stmt = $this->connection->pdo->query('SELECT id, name FROM customer');
        while ($customer = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $list[$customer['id']] = $customer['name'];
        }
        return $list;
    }
}