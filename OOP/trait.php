<?php
use Application\Database\ConnectionAwareInterface;

trait ListTrait {
    public function list() {
        $list = [];
        $sql = sprintf('SELECT %s, %s FROM %s', $this->key, $this->value, $this->table);
        $stmt = $this->connection->pdo->query($sql);
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $list[$item[$this->key]] = $item[$this->value];
        }
        return $list;
    }
}

class CountryListUsingTrait implements ConnectionAwareInterface {
    use ListTrait;
    protected $connection;
    protected $key = 'iso3';
    protected $value = 'name';
    protected $table = 'iso_country_codes';

    public function setConnection(Connection $connection) {
        $this->connection = $connection;
    }
}

// as insteadof
trait IdTrait {
    protected $id;
    public $key;
    public function setId($id) {
        $this->id = $id;
    }

    public function setKey() {
        $this->key = date('YmdHis') . sprintf('%04d', rand(0, 9999));
    }
}

trait NameTrait {
    protected $name;
    public $key;
    public function setName($name) {
        $this->name = $name;
    }

    public function setKey() {
        $this->key = unpack('H*', random_bytes(18))[1];
    }
}

class Test {
    use IdTrait, NameTrait {
        NameTrait::setKey insteadOf IdTrait;
        IdTrait::setKey as setKeyDate;
    }
}
