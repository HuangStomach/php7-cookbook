<?php
namespace Application\I18n;

use Locale as PhpLocale;

class Locale extends PhpLocale {
    const FALLBACK_LOCALE = 'en';
    protected $localeCode;

    public function setLocaleCode($acceptLangHeader) {
        $this->localeCode = $this->acceptFromHttp($acceptLangHeader);
    }

    public function getAcceptLanguage() {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? self::FALLBACK_LOCALE;
    }

    public function getLocaleCode() {
        return $this->localeCode;
    }

    public function __construct($localeString = null) {
        if ($localeString) {
            $this->setLocaleCode($localeString);
        }
        else {
            $this->setLocaleCode($this->getAcceptLanguage());
        }
    }
}
