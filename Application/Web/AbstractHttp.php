<?php
namespace Application\Web;

class AbstractHttp {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_FROM_URL_ENCODED = 'application/x-www-form-urlencoded';
    
    const HEADER_CONTENT_TYPE = 'Content-Type';

    const TRANSPORT_HTTP = 'http';
    const TRANSPORT_HTTPS = 'https';

    const STATUS_200 = '200';
    const STATUS_401 = '401';
    const STATUS_500 = '500';

    protected $uri;
    protected $method;
    protected $headers;
    protected $cookies;
    protected $metaData;
    protected $transport;
    protected $data = [];

    public function setMethod($method) {
        $this->method = $method;
    }

    public function getMethod() {
        return $this->method ?? self::METHOD_GET;
    }

    public function setHeaderByKey($key, $value) {
        $this->header[$key] = $value;
    }

    public function getHeaderByKey($key) {
        return $this->header[$key] ?? null;
    }

    public function getData() {
        return $this->data ?? [];
    }

    public function getDataByKey($key) {
        return $this->data[$key] ?? null;
    }

    public function getMetaDataByKey($key) {
        return $this->metadata[$key] ?? null;
    }

    public function setUri($uri, array $params = null) {
        $this->uri = $uri;
        $first = TRUE;
        if ($params) {
            $this->uri .= '?' . http_build_query($params);
        }
    }

    public function getDataEncoded() {
        return http_build_query($this->getData());
    }

    public function setTransport($transport = null) {
        if ($transport) $this->trasport = $transport;
        else $this->trasport = substr($this->uri, 0, 5) == self::TRANSPORT_HTTPS
            ? self::TRANSPORT_HTTPS
            : self::TRANSPORT_HTTP;
    }
}