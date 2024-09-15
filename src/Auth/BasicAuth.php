<?php

namespace MyCoolPay\Http\Auth;

use MyCoolPay\Http\Inheritance\AuthStrategy;

class BasicAuth implements AuthStrategy
{
    /**
     * @var string $username
     */
    protected $username;
    /**
     * @var string $password
     */
    protected $password;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAuthHeaders($additional_headers = [])
    {
        return array_merge(
            ['Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)],
            $additional_headers
        );
    }
}
