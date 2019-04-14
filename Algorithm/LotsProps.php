<?php

class LotsProps {
    /**
     * 许多props
     */

    public function __call($method, $params) {
        preg_match('/^(get|set)(.*?)$/i', $method, $matches);
        $prefix = $matches[1] ?? '';
        $key = $matches[2] ?? '';
        $key = strtolower($key);
        if ($prefix == 'get') {
            return $this->values[$key] ?? '---';
        }
        else {
            $this->values[$key] = $params[0];
        }
    }
}