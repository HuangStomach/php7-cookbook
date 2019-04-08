<?php
namespace Application\Acl;

use Psr\Http\Message\ { RequestInterface, ResponseInterface };

interface AuthenticateInterface {
    public function login(RequestInterface $requset) : ResponseInterface;
}