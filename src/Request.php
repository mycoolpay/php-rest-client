<?php

namespace MyCoolPay\Http;

use MyCoolPay\Http\Constant\DataType;

class Request
{
    protected $ip;
    protected $method;
    protected $url;
    protected $query;
    protected $headers;
    protected $body;
    protected $data_type;
    protected $response_type;

    /**
     * @param string|null $method
     * @param string|null $url
     * @param array|null $query
     * @param array|null $headers
     * @param array|string|null $body
     * @param string $data_type
     * @param string $response_type
     */
    public function __construct($method = null, $url = null, $query = [], $headers = [], $body = [], $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        $this->method = $method;
        $this->url = $url;
        $this->query = is_null($query) ? [] : $query;
        $this->headers = is_null($headers) ? [] : $headers;
        $this->body = is_null($body) ? [] : $body;
        $this->data_type = $data_type;
        $this->response_type = $response_type;
    }

    /**
     * @return string|null
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string|null $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param array|null $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function addQuery($key, $value)
    {
        $this->query[$key] = $value;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array|null $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @return array|string|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array|string|null $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function addBody($key, $value)
    {
        $this->body[$key] = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDataType()
    {
        return $this->data_type;
    }

    /**
     * @param string|null $data_type
     * @return $this
     */
    public function setDataType($data_type)
    {
        $this->data_type = $data_type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseType()
    {
        return $this->response_type;
    }

    /**
     * @param string|null $response_type
     * @return $this
     */
    public function setResponseType($response_type)
    {
        $this->response_type = $response_type;
        return $this;
    }
}
