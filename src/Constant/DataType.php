<?php

namespace MyCoolPay\Http\Constant;

/**
 * HTTP data types
 */
class DataType
{
    const NONE = '';
    const ANY = '*/*';
    const JSON = 'application/json';
    const URL_ENCODED = 'application/x-www-form-urlencoded';
    const XML = 'application/xml';
    const YAML = 'application/yaml';
    const MULTIPART = 'multipart/form-data';
    const TEXT = 'text/plain';
    const HTML = 'text/html';
    const CSV = 'text/csv';
}
