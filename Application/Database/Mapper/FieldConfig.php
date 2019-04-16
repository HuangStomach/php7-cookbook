<?php
namespace Application\Database\Mapper;

use InvalidArgumentException;

class FieldConfig {
    const ERROR_SOURCE = 'source error';
    const ERROR_DEST = 'dest error';

    public $key;
    public $source;
    public $destTable;
    public $destCol;
    public $default;

    public function __construct($source = null, $destTable = null, $destCol = null, $default = null) {
        $this->key = $source . '.' . $destTable . '.' . $destCol;
        $this->source = $source;
        $this->destTable = $destTable;
        $this->destCol = $destCol;
        $this->default = $default;

        if (($destTable && !$destCol) || (!$destTable && $destCol)) {
            throw new InvalidArgumentException(self::ERROR_DEST);
        }

        if (!$destTable && !$source) {
            throw new InvalidArgumentException(self::ERROR_SOURCE);
        }
    }

    public function getDefault($row) {
        if (is_callable($this->default)) {
            return call_user_func($this->default, $row);
        }
        else {
            return $this->default;
        }
    }

    public function getKey() {
        return $this->key;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function getSource() {
        return $this->source;
    }

    public function setSource($source) {
        $this->source = $source;
    }

    public function getDestTable() {
        return $this->destTable;
    }

    public function setDestTable($destTable) {
        $this->destTable = $destTable;
    }

    public function getDestCol() {
        return $this->destCol;
    }

    public function setDestCol($destCol) {
        $this->destCol = $destCol;
    }
}