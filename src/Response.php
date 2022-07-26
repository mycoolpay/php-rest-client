<?php

namespace MyCoolPay\Http;

class Response implements HttpResult
{
    /**
     * @var Request|null $request
     */
    private $request;
    /**
     * @var int $statusCode
     */
    private $statusCode;
    /**
     * @var mixed $data
     */
    private $data;

    /**
     * @param int|null $status_code
     * @param mixed $data
     * @param Request|null $request
     */
    public function __construct($status_code, $data, $request = null)
    {
        $this->statusCode = is_null($status_code) ? 0 : $status_code;
        $this->data = $data;
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
        if (is_null($this->data) || is_string($this->data))
            return $this->data;

        if (is_array($this->data))
            return json_encode($this->data);

        return strval($this->data);
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed|null $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
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
     * @return mixed|null
     */
    public function getMessage()
    {
        return $this->get('message');
    }
}
