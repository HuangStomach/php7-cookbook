<?php
declare(strict_types=1);
namespace Application\MiddleWare;

use Throwable;
use RuntimeException;
use SplFileInfo;
use Psr\Http\Message\StreamInterface;

class TextStream implements StreamInterface {
    protected $stream;
    protected $pos;

    public function __construct(string $input) {
        $this->stream = $input;
    }

    public function getStream() {
        return $this->stream;
    }

    public function getInfo() {
        return null;
    }

    public function getContents() {
        return $this->stream;
    }

    public function getSize() {
        return strlen($this->stream);
    }

    public function close() {

    }

    public function detach() {
        return $this->close();        
    }

    public function tell() {
        return $this->pos;
    }

    public function eof() {
        return ($this->pos == strlen($this->stream));
    }

    public function isSeekable() {
        return TRUE;
    }

    public function seek($offset, $whence = SEEK_SET) {
        if ($offset < $this->getSize()) {
            $this->pos = $offset;
        }
        else throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
    }

    public function rewind() {
        $this->pos = 0;
    }

    public function isWritable() {
        return TRUE;
    }

    public function write($string) {
        $temp = substr($this->stream, 0, $this->pos);
        $this->stream = $temp . $string;
        $this->pos = strlen($this->stream);
    }

    public function isReadable() {
        return TRUE;
    }

    public function read($length) {
        return substr($this->stream, $this->pos, $length);
    }

    public function getMetadata($key = null) {
        return null;
    }

    public function __toString() {
        return $this->getContents();
    }
}