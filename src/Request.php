<?php

namespace MyCoolPay\Http;

class Request
{
    private $method;
    private $url;
    private $query;
    private $headers;
    private $body;

    /**
     * @param string|null $method
     * @param string|null $url
     * @param array|null $query
     * @param array|null $headers
     * @param array|null $body
     */
    public function __construct($method = null, $url = null, $query = [], $headers = [], $body = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->query = is_null($query) ? [] : $query;
        $this->headers = is_null($headers) ? [] : $headers;
        $this->body = is_null($body) ? [] : $body;
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
     * @return array|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array|null $body
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
}
