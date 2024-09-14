<?php

namespace MyCoolPay\Http\Auth;

use MyCoolPay\Http\Inheritance\AuthStrategy;

class BearerAuth implements AuthStrategy
{
    /**
     * @var string $token
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAuthHeaders($additional_headers = [])
    {
        return array_merge(
            ['Authorization' => 'Bearer ' . $this->token],
            $additional_headers
        );
    }
}
