<?php

namespace MyCoolPay\Http;

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
}
