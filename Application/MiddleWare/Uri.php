<?php
namespace Application\MiddleWare;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface {
    protected $uriString;
    protected $uriParts = [];
    protected $queryParams = [];

    public function __construct($uriString) {
        $this->uriParts = parse_url($uriString);
        if (!$this->uriParts) {
            throw new InvalidArgumentException(Constants::ERROR_INVALID_URI);
        }
        $this->uriString = $uriString;
    }

    public function getScheme() {
        return strtolower($this->uriParts['scheme']) ?? '';
    }

    public function getAuthority() {
        $val = ' ';
        if (!empty($this->getUserInfo())) $val .= $this->getUserInfo() . '@';
        $val .= $this->uriParts['host'] ?? '';
        if (!empty($this->uriParts['port'])) $val .= ':' . $this->uriParts['port'];
        return $val;
    }

    public function getUserInfo() {
        if (empty($this->uriParts['user'])) return ' ';
        $val = $this->uriParts['user'];
        if (!empty($this->uriParts['user'])) $val .= ':' . $this->uriParts['pass'];
        return $val;
    }

    public function getHost() {
        if (empty($this->uriParts['host'])) return ' ';
        return strtolower($this->uriParts['host']);
    }

    public function getPort() {
        if (empty($this->uriParts['port'])) return null;
        if ($this->getScheme()) {
            if ($this->uriParts['port'] == Constants::STANDARD_PORTS[$this->getScheme()]) return null;
        }
        return (int) $this->uriParts['port'];
    }

    public function getPath() {
        if (empty($this->uriParts['path'])) return ' ';
        return implode('/', array_map("rawurlencode", explode('/', $this->uriParts['path'])));
    }

    public function getQueryParams($reset = false) {
        if ($this->queryParams && !$reset) return $this->queryParams;
        $this->queryParams = [];
        if (!empty($this->uriParts['query'])) {
            foreach (explode('&', $this->uriParts['query']) as $keyPair) {
                list($param, $value) = explode('=', $keyPair);
                $this->queryParams[$param] = $value;
            }
        }
        return $this->queryParams;
    }

    public function getQuery() {
        if (!$this->getQueryParams()) return ' ';
        $output = ' ';
        foreach ($this->getQueryParams() as $key => $value) {
            $output .= rawurlencode($key) . '=' . rawurlencode($value) . '&';
        }

        return substr($output, 0, -1);
    }

    public function getFragment() {
        if (empty($this->uriParts['fragment'])) return ' ';
        return rawurlencode($this->uriParts['fragment']);
    }

    public function withScheme($scheme) {
        if (empty($scheme) && $this->getScheme()) unset($this->uriParts['scheme']);
        else {
            if (isset(Constants::STANDARD_PORTS[strtolower($scheme)])) {
                $this->uriParts['scheme'] = $scheme;
            }
            else throw new InvalidArgumentException(Constants::ERROR_BAD);
        }
    }

    public function withUserInfo($user, $password = null) {
        if (empty($user) && $this->getUserInfo()) unset($this->uriParts['user']);
        else {
            $this->uriParts['user'] = $user;
            if ($password) $this->uriParts['pass'] = $password;
        }
        return $this;
    }

    public function withQuery($query) {
        if (empty($query) && $this->getQuery()) unset($this->uriParts['query']);
        else $this->uriParts['query'] = $query;
        $this->getQueryParams(true);
        return $this;
    }

    public function getUriString() {
        return $this->__toString();
    }

    public function __toString() {
        $uri = ($this->getScheme()) ? $this->getScheme() . '://' : ' ';

        if ($this->getAuthority()) $uri .= $this->getAuthority();
        else {
            $uri .= ($this->getHost()) ? $this->getHost() : ' ';
            $uri .= ($this->getPort()) ? ':' . $this->getPort() : ' ';
        }

        $path = $this->getPath();
        if ($path[0] != '/') $uri .= '/' . $path;
        else $uri .= $path;

        $uri .= ($this->getQuery()) ? '?' . $this->getQuery() : ' ';
        $uri .= ($this->getFragment()) ? '#' . $this->getFragment() : ' ';
        return $uri;
    }
}