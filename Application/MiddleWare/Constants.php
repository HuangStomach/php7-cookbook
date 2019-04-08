<?php
namespace Applcation\MiddleWare;

class Constants {
    const HEADER_HOST = 'Host';
    const HEADER_CONTENT_TYPE = 'Content-Type';
    const HEADER_CONTENT_LENGTH = 'Content-Length';

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_PATH = 'patch';
    const METHOD_DELETE = 'delete';

    const HTTP_METHODS = [self::METHOD_GET, self::METHOD_PUT, self::METHOD_PATH, self::METHOD_POST, self::METHOD_DELETE];

    const STANDARD_PORTS = [
        'ftp' => 21,
        'ssh' => 22,
        'http' => 80,
        'https' => 443,
    ];

    const CONTENT_TYPE_FORM_ENCODED = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE_MULTI_FORM = 'multipart/form-data';
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_HAL_JSON = 'application/hal+json';

    const DEFAULT_STATUS_CODE = 200;
    const DEFAULT_BODY_STREAM = 'php://input';
    const DEFAULT_REQUEST_TARGET = '/';

    const MODE_READ = 'r';
    const MODE_WRITE = 'w';

    const ERROR_BAD = 'ERROR: ';
    const ERROR_INVALID_URI = 'ERROR: invaild uri';
    const ERROR_UNKNOWN = 'ERROR: unknown';

    const STATUS_CODES = [
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        401 => 'Unauthorized',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        418 => 'I_m A Teapot',
        500 => 'Internal Server Error'
    ];
}