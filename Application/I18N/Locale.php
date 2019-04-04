<?php
namespace Application\I18n;

use NumberFormatter;
use Locale as PhpLocale;

class Locale extends PhpLocale {
    const FALLBACK_LOCALE = 'en';
    protected $localeCode;
    protected $numberFormatter;

    public function __construct($localeString = null) {
        if ($localeString) {
            $this->setLocaleCode($localeString);
        }
        else {
            $this->setLocaleCode($this->getAcceptLanguage());
        }
    }

    public function getAcceptLanguage() {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? self::FALLBACK_LOCALE;
    }

    public function getLocaleCode() {
        return $this->localeCode;
    }

    public function setLocaleCode($acceptLangHeader) {
        $this->localeCode = $this->acceptFromHttp($acceptLangHeader);
    }

    public function getNumberFormatter() {
        if (!$this->numberFormatter) {
            $this->numberFormatter = new NumberFormatter($this->getLocaleCode(), NumberFormatter::DECIMAL);
        }
        return $this->numberFormatter;
    }

    public function formatNumber($number) {
        return $this->getNumberFormatter()->format($number);
    }

    public function parseNumber($string) {
        $result = $this->getNumberFormatter()->parse($string);
        return ($result) ? $result : 'error';
    }
}
