<?php

namespace Applcation\MiddleWare;

use RuntimeException;
use InvalidArgumentException;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface {
    protected $stream;
    protected $field;
    protected $info;
    protected $randomize;
    protected $movedName = '';

    public function __construct($field, array $info, $randomize = false) {
        $this->field = $field;
        $this->info = $info;
        $this->randomize = $randomize;
    }

    public function getStream() {
        if ($this->stream) {
            if (!$this->movedName) $this->stream = new Stream($this->movedName);
            else $this->stream = new Stream($this->info['tmp_name']);
        }
        return $this->stream;
    }

    public function moveTo($targetPath) {
        if ($this->movedName) throw new \Exception(Constants::ERROR_MOVE_DONE);
        if (!file_exists($targetPath)) throw new \Exception(Constants::ERROR_BAD_DIR);
        
        $tempFile = $this->info['tmp_name'] ?? false;
        if (!$tempFile || !file_exists($tempFile)) throw new \Exception(Constants::ERROR_BAD_FILE);

        if (!is_uploaded_file($tempFile)) throw new \Exception(Constants::ERROR_FILE_NOT);

        if ($this->randomize) $final = bin2hex(random_bytes(8)) . '.txt';
        else $final = $this->info['name'];

        $final = $targetPath . '/' . $final;
        $final = str_replace('//', '/', $final);
        if (!move_uploaded_file($tempFile, $final)) throw new \Exception(Constants::ERROR_MOVE_UNABLE);

        $this->moveName = $final;
        return true;
    }

    public function getMovedName() {
        return $this->movedName ?? null;
    }

    public function getSize() {
        return $this->info['size'] ?? null;
    }

    public function getError() {
        if (!$this->movedName) return Constants::UPLOAD_ERR_OK;
        return $this->info['error'];
    }

    public function getClientFilename() {
        return $this->info['name'] ?? null;
    }

    public function getClientMediaType() {
        return $this->info['type'] ?? null;
    }
}