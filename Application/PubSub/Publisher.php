<?php
namespace Application\PubSub;

use SplSubject;
use SplObserver;

class Publisher implements SplSubject {
    protected $name;
    protected $data;
    protected $linked;
    protected $subscribers;

    public function __construct($name) {
        $this->name = $name;
        $this->data = [];
        $this->subscribers = [];
        $this->linked = [];
    }

    public function __toString() {
        return $this->name;
    }

    public function attach(SplObserver $subscriber) {
        $this->subscribers[$subscriber->getKey()] = $subscriber;
        $this->linked[$subscriber->getKey()] = $subscriber->getPriority();
        asort($this->linked);
    }

    public function detach(SplObserver $subscriber) {
        unset($this->subscribers[$subscriber->getKey()]);
        unset($this->linked[$subscriber->getKey()]);
    }

    public function notify() {
        foreach ($this->linked as $key => $value) {
            $this->subscribers[$key]->update($this);
        }
    }

    public function setDataByKey($key, $value) {
        $this->data[$key] = $value;
    }
}
