<?php
namespace Application\Web;

class Hoover {
    protected $content;

    public function getContent($url) {
        if (!$this->content) {
            if (stripos($url, 'http') !== 0) $url = 'http://' . $url;
            $this->content = new DOMDocument('1.0', 'utf-8');
            $this->content->preserveWhiteSpace = FALSE;
            @$this->content->loadHTMLFile($url);
        }
        return $this->content;
    }
}
