<?php
namespace Application\MiddleWare;

use Psr\Http\Message\ { ResponseInterface, StreamInterface };

class Response extends Message implements ResponseInterface {
    protected $status;

    public function __construct($statusCode = null, StreamInterface $body = null, $headers = null, $version = null) {
        $this->body = $body;
        $this->status['code'] = $statusCode ?? Constants::DEFAULT_STATUS_CODE;
        $this->status['reason'] = Constants::STATUS_CODES[$statusCode] ?? '';
        $this->httpHeaders = $headers;
        $this->version = $this->onlyVersion($version);
        if ($statusCode) $this->setStatusCode();
    }

    public function setStatusCode() {
        http_response_code($this->getStatusCode());
    }

    public function getStatusCode() {
        return $this->status['code'];
    }

    public function withStatus($statusCode, $reasonPhrase = '') {
        if (!isset(Constants::STATUS_CODES[$statusCode])) {
            throw new \InvalidArgumentException(Constants::ERROR_INVALID_STATUS);
        }
        $this->status['code'] = $statusCode;
        $this->status['reason'] = ($reasonPhrase) ? Constants::STATUS_CODES[$statusCode] : null;
        $this->setStatusCode();
        return $this;
    }

    public function getReasonPhrase() {
        return $this->status['reason'] ?? Constants::STATUS_CODES[$this->status['code']] ?? ' ';
    }
}