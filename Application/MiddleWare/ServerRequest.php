<?php
namespace Application\MiddleWare;

use Psr\Http\Message\ { ServerRequestInterface, UploadedFileInterface };

class ServerRequest extends Request implements ServerRequestInterface {
    protected $serverParams;
    protected $cookies;
    protected $queryParams;
    protected $contentType;
    protected $parsedBody;
    protected $attributes;
    protected $method;
    protected $uploadedFileInfo;
    protected $uploadedFileObjs;

    public function getServerParams() {
        if (!$this->serverParams) $this->serverParams = $_SERVER;
        return $this->serverParams;
    }

    public function getCookieParams() {
        if (!$this->cookies) $this->cookies = $_COOKIE;
        return $this->cookies;
    }

    public function withCookieParams(array $cookies) {
        array_merge($this->getCookieParams(), $cookies);
        return $this;
    }

    public function getQueryParams() {
        if (!$this->queryParams) $this->queryParams = $_GET;
        return $this->queryParams;
    }

    public function withQueryParams(array $query) {
        array_merge($this->getQueryParams(), $query);
        return $this;
    }

    public function getUploadedFileInfo() {
        if (!$this->uploadedFileInfo) $this->uploadedFileInfo = $_FILES;
        return $this->uploadedFileInfo;
    }

    public function getRequestMethod() {
        $method = $this->getServerParams()['REQUEST_METHOD'] ?? '';
        $this->method = strtolower($method);
        return $method;
    }

    public function getContentType() {
        if (!$this->contentType) {
            $this->contentType = $this->getServerParams()['CONTENT_TYPE'] ?? ' ';
            $this->contentType = strtolower($this->contentType);
        }
        return $this->contentType;
    }

    public function getUploadedFiles() {
        if (!$this->uploadedFileObjs) {
            foreach ($this->getUploadedFiles() as $field => $value) {
                $this->uploadedFileObjs[$field] = new UploadedFile($field, $value);
            }
        }
        return $this->uploadedFileObjs;
    }

    public function withUploadedFiles(array $uploadedFiles) {
        if (!count($uploadedFiles)) {
            throw new \InvalidArgumentException(Constants::ERROR_NO_UPLOADED_FILES);
        }

        foreach ($uploadedFiles as $fileObj) {
            if (!$fileObj instanceof UploadedFileInterface) {
                throw new \InvalidArgumentException(Constants::ERROR_INVALID_UPLOADED);
            }
        }

        $this->uploadedFileObjs = $uploadedFiles;
    }

    public function getParsedBody() {
        if (!$this->parsedBody) {
            $contentType = $this->getContentType();
            if (($contentType == Constants::CONTENT_TYPE_FORM_ENCODED || $contentType == Constants::CONTENT_TYPE_MULTI_FORM)
            && $this->getRequestMethod() == Constants::METHOD_POST) {
                $this->parsedBody = $_POST;
            }
            elseif ($contentType == Constants::CONTENT_TYPE_JSON || $contentType == Constants::CONTENT_TYPE_HAL_JSON) {
                ini_set("allow_url_fopen", true);
                $this->parsedBody = json_decode(file_get_contents('php://input'));
            }
            elseif (!empty($_REQUEST)) {
                $this->parsedBody = $_REQUEST;
            }
            else {ini_set("allow_url_fopen", true);
                $this->parsedBody = file_get_contents('php://input');
            }
        }
        return $this->parsedBody;
    }

    public function withParsedBody($data) {
        $this->parsedBody = $data;
        return $this;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null) {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value) {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function withoutAttributes($name) {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
        return $this;
    }

    public function initialize() {
        $this->getServerParams();
        $this->getCookieParams();
        $this->getQueryParams();
        $this->getUploadedFiles();
        $this->getRequestMethod();
        $this->getContentType();
        $this->getParsedBody();
        return $this;
    }
} 