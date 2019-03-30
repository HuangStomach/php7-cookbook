<?php
namespace Application\Web;

class Received extends AbstractHttp {
    public function __construct($uri = null, $method = null, array $headers = null,
    array $data = null, array $cookies = NULL) {
        $this->headers = $headers;
        $this->uri = $uri;
        $this->method = $method;
        $this->data = $data;
        $this->cookies = $cookies;

        $this->setTransport();
    }
}
