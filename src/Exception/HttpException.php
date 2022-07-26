<?php

namespace MyCoolPay\Http\Exception;

use Exception;
use MyCoolPay\Http\Http;
use MyCoolPay\Http\HttpResult;
use MyCoolPay\Http\Request;
use MyCoolPay\Http\Response;

class HttpException extends Exception implements HttpResult
{
    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @param int $code
     * @param string $message
     * @param Request|null $request
     */
    public function __construct($code = 0, $message = "", $request = null)
    {
        parent::__construct($message, $code);

        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        return $this->code;
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
    public function getRawData()
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return "HttpException " . $this->code . ($this->message !== "" ? ": " . $this->message : "");
    }

    /**
     * @param Response $res
     * @return static
     */
    public static function fromJSONResponse(Response $res)
    {
        return new self(Http::EXPECTATION_FAILED, $res->getRawData(), $res->getRequest());
    }
}
