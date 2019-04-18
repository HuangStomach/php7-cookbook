<?php
namespace Application\PubSub;

use SplSubject;
use SplObserver;

class Subscriber implements SplObserver {
    protected $key;
    protected $name;
    protected $priority;
    protected $callback;

    public function __construct(string $name, callable $callback, $priority = 0) {
        $this->key = md5(date('YmdHis')) . rand(0, 9999);
        $this->name = $name;
        $this->callback = $callback;
        $this->priority = $priority;
    }

    public function update(SplSubject $publisher) {
        call_user_func($this->callback, $publisher);
    }
}
