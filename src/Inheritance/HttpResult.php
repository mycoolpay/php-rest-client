<?php

namespace MyCoolPay\Http\Inheritance;

use MyCoolPay\Http\Request;

interface HttpResult
{
    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return Request|null
     */
    public function getRequest();

    /**
     * @return string|null
     */
    public function getRawData();

    /**
     * @return mixed
     */
    public function getData();
}
