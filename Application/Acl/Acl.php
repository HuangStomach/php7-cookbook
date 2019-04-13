<?php
namespace Application\Acl;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Application\MiddleWare\ { Constants, Response, TextStream };

class Acl {
    const DEFAULT_STATUS = '';
    const DEFAULT_LEVEL = 0;
    const DEFAULT_PAGE = 0;
    const ERROR_ACL = 'ERROR: authoriztion error';
    const ERROR_DEF = 'ERROR: must assign keys "levels", "pages" and "allowed"';

    protected $default;
    protected $levels;
    protected $pages;
    protected $allowed;

    public function __construct(array $assignments) {
        $this->default = $assignments['default'] ?? self::DEFAULT_PAGE;
        $this->pages = $assignments['pages'] ?? false;
        $this->levels = $assignments['levels'] ?? false;
        $this->allowed = $assignments['allowed'] ?? false;
        if (!($this->pages && $this->levels && $this->allowed)) {
            throw new InvalidArgumentException(self::ERROR_DEF);
        }
    }

    protected function mergeInherited($status, $level) {
        $allowed = $this->allowed[$status]['pages'][$level] ?? [];
        for ($x = $status; $x > 0; $x--) {
            $inherits = $this->allowed[$x]['inherits'];
            if ($inherits) {
                $subArray = $this->allowed[$inherits]['pages'][$level] ?? [];
                $allowed = array_merge($allowed, $subArray);
            }
        }
        return $allowed;
    }

    public function isAuthorized(RequestInterface $request) {
        $code = 401;
        $text['page'] = $this->pages[$this->default];
        $text['authorized'] = false;
        $page = $request->getUri()->getQueryParmas()['page'] ?? false;
        if ($page == false) {
            $code = 400;
        }
        else {
            $params = json_decode($request->getBody()->getContents());
            $status = $params->status ?? self::DEFAULT_LEVEL;
            $level = $params->level ?? '*';
            $allowed = $this->mergeInherited($status, $level);
            if (in_array($page, $allowed)) {
                $code = 200;
                $text['authorized'] = true;
                $text['page'] = $this->pages[$page];
            }
            else {
                $code = 401;
            }
        }

        $body = new TextStream(json_encode($text));
        return (new Response())->withStatus($code)->withBody($body);
    }
}