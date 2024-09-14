<?php

namespace MyCoolPay\Http;

use MyCoolPay\Http\Constant\DataType;
use MyCoolPay\Http\Inheritance\HttpResult;

class Response implements HttpResult
{
    /**
     * @var Request|null $request
     */
    protected $request;
    /**
     * @var int $statusCode
     */
    protected $statusCode;
    /**
     * @var string $raw_data
     */
    protected $raw_data;
    /**
     * @var mixed $data
     */
    protected $data;
    /**
     * @var string $data_type
     */
    protected $data_type;

    /**
     * @param int|null $status_code
     * @param mixed $data
     * @param Request|null $request
     */
    public function __construct($status_code, $raw_data = '', $data = [], $data_type = DataType::JSON, $request = null)
    {
        $this->statusCode = is_null($status_code) ? 0 : $status_code;
        $this->raw_data = $raw_data;
        $this->data = $data;
        $this->data_type = $data_type;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request|null $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $status_code
     * @return $this
     */
    public function setStatusCode($status_code)
    {
        $this->statusCode = $status_code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRawData()
    {
        return $this->raw_data;
    }

    /**
     * @param mixed $raw_data
     * @return $this
     */
    public function setRawData($raw_data)
    {
        $this->raw_data = $raw_data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($this->data) && array_key_exists($key, $this->data))
            return $this->data[$key];

        return $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return is_array($this->data) && array_key_exists($key, $this->data);
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->get('message');
    }
}
