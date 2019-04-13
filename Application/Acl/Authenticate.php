<?php
namespace Application\Acl;

use Application\MiddleWare\ { Response, TextStream };
use Psr\Http\Message\ { RequestInterface, ResponseInterface };

class Authenticate {
    const ERROR_AUTH = 'ERROR: invalid token';
    const DEFAULT_KEY = 'auth';

    protected $adapter;
    protected $token;

    public function __construct(AuthenticateInterface $adapter, $key) {
        $this->key = $key;
        $this->adapter = $adapter;
    }

    public function getToken() {
        $this->token = bin2hex(random_bytes(16));
        $_SESSION['token'] = $this->token;
        return $this->token;
    }

    public function matchToken() {
        $sessToken = $_SESSION['token'] ?? date('Ymd');
        return ($this->token == $sessToken);
    }

    public function getLoginForm($action = null) {
        // 一个具有csrf防御的表单
        return '<form></form>';
    }

    public function login(RequestInterface $request) : ResponseInterface {
        $params = json_decode($request->getBody()->getContents());
        $token = $params->token ?? false;
        if (!($token && $this->matchToken($token))) {
            $code = 400;
            $body = new TextStream(self::ERROR_AUTH);
            $response = new Response($code, $body);
        }
        else {
            $response = $this->adapter->login($request);
        }

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $_SESSION[$this->key] = json_decode($response->getBody()->getContents());
        }
        else {
            $_SESSION[$this->key] = NULL;
        }
        return $response;
    }
}
