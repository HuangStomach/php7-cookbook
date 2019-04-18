<?php
namespace Application\Captcha;

class Phrase {
    const DEFAULT_LENGTH = 5;
    const DEFAULT_NUMBERS = '0123456789';
    const DEFAULT_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const DEFAULT_LOWER = 'abcdefghijklmnopqrstuvwxyz';
    const DEFAULT_SPECIAL = '…………一些符号';
    const DEFAULT_SUPPRESS = ['O', 'l'];

    protected $phrase;
    protected $includeNumbers;
    protected $includeUpper;
    protected $includeLower;
    protected $includeSpecial;
    protected $otherChars;
    protected $suppressChars;
    protected $string;
    protected $length;

    public function __construct($length = null, $includeNumbers = true, $includeUpper = true, $includeLower = true,
        $includeSpecial = false, $otherChars = null, array $suppressChars = null) {
        $this->length = $length ?? self::DEFAULT_LENGTH;
        $this->includeNumbers = $includeNumbers;
        $this->includeUpper = $includeUpper;
        $this->includeLower = $includeLower;
        $this->includeSpecial = $includeSpecial;
        $this->otherChars = $otherChars;
        $this->suppressChars = $suppressChars ?? self::DEFAULT_SUPPRESS;
        $this->phrase = $this->generatePhrase();
    }

    public function getString() {
        return $this->string;
    }

    public function setString($string) {
        $this->string = $string;
    }

    public function initString() {
        $string = '';
        if ($this->includeNumbers) $string .= self::DEFAULT_NUMBERS;
        if ($this->includeUpper) $string .= self::DEFAULT_UPPER;
        if ($this->includeLower) $string .= self::DEFAULT_LOWER;
        if ($this->includeSpecial) $string .= self::DEFAULT_SPECIAL;
        if ($this->otherChars) $string .= $this->otherChars;
        if ($this->suppressChars) $string = str_replace($this->suppressChars, '', $string);
        return $string;
    }

    public function generatePhrase() {
        $phrase = '';
        $this->string = $this->initString();
        $max = strlen($this->string) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            $phrase .= substr($this->string, random_int(0, $max), 1);
        }
        return $phrase;
    }
}
