<?php

namespace MyCoolPay\Http\Inheritance;

interface AuthStrategy
{
    /**
     * @param array $additional_headers
     * @return array
     */
    public function getAuthHeaders($additional_headers = []);
}
