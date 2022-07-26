<?php

namespace MyCoolPay\Http;

class Http
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;

    const MOVED = 301;
    const NOT_MODIFIED = 304;

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const GONE = 410;
    const PAYLOAD_TOO_LARGE = 413;
    const URI_TOO_LONG = 414;
    const EXPECTATION_FAILED = 417;

    const INTERNAL_SERVER_ERROR = 500;
    const BAD_GATEWAY = 502;
    const GATEWAY_TIMEOUT = 504;
}
