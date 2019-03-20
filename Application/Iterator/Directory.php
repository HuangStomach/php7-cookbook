<?php
namespace Application\Iterator;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Directory {
    const ERROR_UNABLE = 'ERROR: Unable to read directory';

    protected $path;
    protected $rdi;

    public function __construct($path) {
        try {
            $this->rdi = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path),
                RecursiveIteratorIterator::SELF_FIRST
            );
        }
        catch (\Throwable $e) {
            $message = __METHOD__ . ' : ' . self::ERROR_UNABLE . PHP_EOL;
            $message .= strip_tags($filename) . PHP_EOL;
            echo $message;
            exit;
        }
    }

    public function ls($pattern = NULL) {
        $outerIterator = ($pattern)
        ? $this->regex($this->rdi, $pattern)
        : $this->rdi;

        foreach ($outerIterator as $obj) {
            if ($obj->isDir()) {
                if ($obj->getFileName() == '..') continue;
                $line = $obj->getPath() . PHP_EOL;
            }
            else {
                $line = sprintf('%4 %1d %4s %4s %10d, %12s %-40s' . PHP_EOL,
                substr(sprintf('%o', $obj->getPerms()), -4),
                $obj->getType() == 'file' ? 1 : 2,
                $obj->getOwner(),
                $obj->getGroup(),
                $obj->getSize(),
                date('M d Y H:i', $obj->getATime()),
                $obj->getFileName());
            }
            yield $line;
        }
    }

    protected function regex($iterator, $pattern) {
        $pattern = '!^.' . str_replace('.', '\\.', $pattern) . '$!';
        return new RegexIterator($iterator, $pattern);
    }

    public function dir($pattern = NULL) {
        $outerIterator = ($pattern)
        ? $this->regex($this->rdi, $pattern)
        : $this->rdi;
        foreach ($outerIterator as $name => $obj) {
            yield $name . PHP_EOL;
        }
    }
}
