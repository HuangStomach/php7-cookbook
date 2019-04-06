<?php
namespace Application\I18n;

use NumberFormatter;
use IntlCalendar;
use IntlDateFormatter;
use Locale as PhpLocale;

class Locale extends PhpLocale {
    const FALLBACK_LOCALE = 'en';
    const FALLBACK_CURRENCY = 'GBP';

    const DATE_TYPE_FULL = IntlDateFormatter::FULL;
    const DATE_TYPE_LONG = IntlDateFormatter::LONG;
    const DATE_TYPE_MEDIUM = IntlDateFormatter::MEDIUM;
    const DATE_TYPE_SHORT = IntlDateFormatter::SHORT;

    const ERROR_UNABLE_TO_PARSE = 'ERROR: Unable to parse';
    const ERROR_UNABLE_TO_FORMAT = 'ERROR: Unable to format date';
    const ERROR_ARGS_STRING_ARRAY = 'ERROR: Date must be string: ...';
    const ERROR_CREATE_INTL_DATE_FMT = 'ERROR: Unable to create international date formatter';

    protected $localeCode;
    protected $numberFormatter;
    protected $currencyFormatter;
    protected $currencyLookup;
    protected $currencyCode;
    protected $dateFormatter;

    public function __construct($localeString = null, IsoCodesInterface $currencyLookup = null) {
        if ($localeString) {
            $this->setLocaleCode($localeString);
        }
        else {
            $this->setLocaleCode($this->getAcceptLanguage());
        }
        $this->currencyLookup = $currencyLookup;
        if ($this->currencyLookup) {
            $this->currencyCode = $this->currencyLookup
                ->getCurrencyCodeFromIso2CountryCode($this->getCountryCode())
                ->currency_code;
        }
        else {
            $this->currencyCode = self::FALLBACK_CURRENCY;
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

    public function getCountryCode() {
        return $this->getRegion($this->getLocaleCode());
    }

    public function getCurrencyCode() {
        return $this->currencyCode;
    }

    public function getCurrencyFormatter() {
        if (!$this->currencyFormatter) {
            $this->currencyFormatter = new NumberFormatter($this->getLocaleCode(), NumberFormatter::CURRENCY);
        }
        return $this->currencyFormatter;
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
        return ($result) ? $result : self::ERROR_UNABLE_TO_PARSE;
    }

    public function getDateFormatter($type) {
        switch ($type) {
            case self::DATE_TYPE_SHORT:
                $formatter = new IntlDateFormatter($this->getLocaleCode(), self::DATE_TYPE_SHORT, self::DATE_TYPE_SHORT);
                break;
            case self::DATE_TYPE_MEDIUM:
                $formatter = new IntlDateFormatter($this->getLocaleCode(), self::DATE_TYPE_MEDIUM, self::DATE_TYPE_MEDIUM);
                break;
            case self::DATE_TYPE_LONG:
                $formatter = new IntlDateFormatter($this->getLocaleCode(), self::DATE_TYPE_LONG, self::DATE_TYPE_LONG);
                break;
            case self::DATE_TYPE_FULL:
                $formatter = new IntlDateFormatter($this->getLocaleCode(), self::DATE_TYPE_FULL, self::DATE_TYPE_FULL);
                break;
            default:
                throw new \InvalidArgumentException(self::ERROR_CREATE_INTL_DATE_FMT);
        }
        $this->dateFormatter = $formatter;
        return $this->dateFormatter;
    }

    public function formatDate($date, $type, $timeZone = NULL) {
        $result = null;
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $hour = 0;
        $minutes = 0;
        $seconds = 0;

        if (is_string($date)) {
            list($dateParts, $timeParts) = explode(' ', $date);
            list($year, $month, $day) = explode('-', $dateParts);
            list($hour, $minutes, $seconds) = explode(':', $timeParts);
        }
        else if (is_array($date)) {
            list($year, $month, $day, $hour, $minutes, $seconds) = $date;
        }
        else {
            throw new \InvalidArgumentException(self::ERROR_ARGS_STRING_ARRAY);
        }

        $intlDate = IntlCalendar::createInstance($timeZone, $this->getLocaleCode());
        $intlDate->set($year, $month, $day, $hour, $minutes, $seconds);
        $formatter = $this->getDateFormatter($type);
        if ($timeZone) {
            $formatter->setTimeZone($timeZone);
        }
        $result = $formatter->format($intlDate);
        return $result ?? self::ERROR_UNABLE_TO_FORMAT;
    }

    public function parseDate($string, $type = null) {
        if ($type) {
            $result = $this->getDateFormatter($type)->parse($string);
        }
        else {
            $tryThese = [
                self::DATE_TYPE_FULL,
                self::DATE_TYPE_LONG,
                self::DATE_TYPE_MEDIUM,
                self::DATE_TYPE_SHORT
            ];
            foreach ($tryThese as $type) {
                $result = $this->getDateFormatter($type)->parse($string);
                if ($result) {
                    break;
                }
            }
        }
        return ($result) ? $result : self::ERROR_UNABLE_TO_PARSE;
    }

    public function formatCurrency($currency) {
        return $this->getCurrencyFormatter()
            ->formatCurrency($currency, $this->currencyCode);
    }

    public function parseCurrency($string) {
        $result = $this->getCurrencyFormatter()->parseCurrency($string, $this->currencyCode);
        return ($result) ? $result : self::ERROR_UNABLE_TO_PARSE;
    }
}
