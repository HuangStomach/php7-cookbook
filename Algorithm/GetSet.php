<?php
class GetSet {
    protected $intVal = null;
    protected $arrVal = null;

    public function getIntVal() : int {
        return $this->intVal ?? 0;
    }

    public function getArrVal() : array {
        return $this->arrVal ?? [];
    }

    public function setIntVal($val) {
        $this->intVal = (int)$val ?? 0;
    }

    public function setArrVal(array $val) {
        $this->arrVal = $val ?? [];
    }
}