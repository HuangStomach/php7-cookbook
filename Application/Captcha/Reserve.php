<?php
namespace Application\Captcha;

class Reserve implements CaptchaInterface {
    const DEFAULT_LABEL = 'Type this in reserve';
    const DEFAUTL_LENGTH = 6;
    protected $phrase;

    public function __construct($label = self::DEFAULT_LABEL, $length = self::DEFAUTL_LENGTH, $includeNumbers = true,
        $includeUpper = true, $includeLower = true, $includeSpecial = false, $otherChars = null, array $suppressChars = null) {
        $this->label = $label;
        $this->phrase = new Phrase(
            $length,
            $includeNumbers,
            $includeUpper,
            $includeLower,
            $includeSpecial,
            $otherChars,
            $suppressChars
        );
    }

    public function getLabel() {
        return $this->label;
    }

    public function getImage() {
        return strrev($this->phrase->getPhrase());
    }

    public function getPhrase() {
        return $this->phrase->getPhrase();
    }
}
