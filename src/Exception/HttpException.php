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
     * @return bool
     */
    public function isBadRequest()
    {
        return $this->code === Http::BAD_REQUEST;
    }

    /**
     * @return bool
     */
    public function isUnauthorized()
    {
        return $this->code === Http::UNAUTHORIZED;
    }

    /**
     * @return bool
     */
    public function isForbidden()
    {
        return $this->code === Http::FORBIDDEN;
    }

    /**
     * @return bool
     */
    public function isNotFound()
    {
        return $this->code === Http::NOT_FOUND;
    }

    /**
     * @return bool
     */
    public function isMethodNotAllowed()
    {
        return $this->code === Http::METHOD_NOT_ALLOWED;
    }

    /**
     * @return bool
     */
    public function isConflict()
    {
        return $this->code === Http::CONFLICT;
    }

    /**
     * @return bool
     */
    public function isGone()
    {
        return $this->code === Http::GONE;
    }

    /**
     * @return bool
     */
    public function isPayloadTooLarge()
    {
        return $this->code === Http::PAYLOAD_TOO_LARGE;
    }

    /**
     * @return bool
     */
    public function isUriTooLong()
    {
        return $this->code === Http::URI_TOO_LONG;
    }

    /**
     * @return bool
     */
    public function isExpectationFailed()
    {
        return $this->code === Http::EXPECTATION_FAILED;
    }

    /**
     * @return bool
     */
    public function isInternalServerError()
    {
        return $this->code === Http::INTERNAL_SERVER_ERROR;
    }

    /**
     * @return bool
     */
    public function isBadGateway()
    {
        return $this->code === Http::BAD_GATEWAY;
    }

    /**
     * @return bool
     */
    public function isGatewayTimeout()
    {
        return $this->code === Http::GATEWAY_TIMEOUT;
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
