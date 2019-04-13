<?php
namespace Application\MiddleWare;

use Psr\Http\Message\ { MessageInterface, StreamInterface, UriInterface };

class Message implements MessageInterface {
    protected $body;
    protected $version;
    protected $httpHeaders = [];

    public function getBody() {
        if (!$this->body) $this->body = new Stream(Constants::DEFAULT_BODY_STREAM);
        return $this->body;
    }

    public function withBody(StreamInterface $body) {
        if (!$body->isReadable()) throw new \InvalidArgumentException(Constants::ERROR_BODY_UNREADABLE);
        $this->body = $body;
        return $this;
    }

    protected function findHeader($name) {
        $found = false;
        foreach ($this->getHeaders() as $key => $value) {
            if (stripos($key, $name) !== false) {
                $found = $key;
                break;
            }
        }
        return $found;
    }

    protected function getHttpHeaders() {
        if (!$this->httpHeaders) {
            if (function_exists('apache_request_headers')) $this->httpHeaders = apache_request_headers();
            else $this->httpHeaders = $this->altApacheReqHeaders();
        }
        return $this->httpHeaders;
    }

    protected function altApacheReqHeaders() {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (stripos($key, 'HTTP_') !== false) {
                $headerKey = str_ireplace('HTTP_', '', $key);
                $headers[$this->explodeHeader($headerKey)] = $value;
            }
            elseif (stripos($key, 'CONTENT_') !== false) {
                $headers[$this->explodeHeader($key)] = $value;
            }
        }
        return $headers;
    }

    protected function explodeHeader($header) {
        $headerParts = explode('_', $header);
        $headerKey = ucwords(strtolower(implode(' ', $headerParts)));
        return str_replace(' ', '-', $headerKey);
    }

    public function getHeaders() {
        foreach ($this->getHttpHeaders() as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    public function withHeader($name, $value) {
        $found = $this->findHeader($name);
        if ($found) $this->httpHeaders[$found] = $value;
        else $this->httpHeaders[$name] = $value;
        return $this;
    }

    public function withAddedHeader($name, $value) {
        $found = $this->findHeader($name);
        if ($found) $this->httpHeaders[$found] .= $value;
        else $this->httpHeaders[$name] .= $value;
        return $this;
    }

    public function withoutHeader($name) {
        $found = $this->findHeader($name);
        if ($found) unset($this->httpHeaders[$found]);
        return $this;
    }

    public function hasHeader($name) {
        return boolval($this->findHeader($name));
    }

    public function getHeaderLine($name) {
        $found = $this->findHeader($name);
        if ($found) return $this->httpHeaders[$found];
        return ' ';
    }

    public function getHeader($name) {
        $line = $this->getHeaderLine($name);
        if ($line) return explode(',', $line);
        return [];
    }

    public function getHeadersAsString() {
        $output = ' ';
        $headers = $this->getHttpHeaders();
        if ($headers && is_array($headers)) {
            foreach ($headers as $key => $value) {
                if ($output) $output .= "\r\n" . $key . ': ' . $value;
                else $output .= $key . ': ' . $value;
            }
        }
        return $output;
    }

    public function getProtocolVersion() {
        if (!$this->version) {
            $this->version = $this->onlyVersion($_SERVER['SERVER_PROTOCOL']);
        }
        return $this->version;
    }

    public function withProtocolVersion($version) {
        $this->version = $this->onlyVersion($version);
        return $this;
    }

    protected function onlyVersion($version) {
        if (!empty($version)) return preg_replace('/[^0-9\.]/', ' ', $version);
        return null;
    }
}