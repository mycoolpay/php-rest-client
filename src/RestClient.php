<?php

namespace MyCoolPay\Http;

use MyCoolPay\Http\Exception\HttpException;
use MyCoolPay\Logging\LoggerInterface;

class RestClient
{
    const USER_AGENT = "MyCoolPay/PHP/RestClient";

    /**
     * @var string $baseUrl
     */
    protected $baseUrl;
    /**
     * @var false|resource $ch
     */
    protected $ch;
    /**
     * @var LoggerInterface $logger
     */
    protected $logger;
    /**
     * @var bool $debug
     */
    protected $debug;
    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var string $log
     */
    protected $log;

    /**
     * @param string $base_url
     * @param LoggerInterface|null $logger
     * @param bool $debug
     */
    public function __construct($base_url = '', $logger = null, $debug = false)
    {
        $this->baseUrl = rtrim($base_url, '/');
        $this->logger = $logger;
        $this->debug = $debug;
        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
            CURLOPT_CONNECTTIMEOUT => 0, // wait for connection indefinitely
            CURLOPT_TIMEOUT => 0,  // execute indefinitely
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_RETURNTRANSFER => true, // return the response as a string
            CURLOPT_FAILONERROR => false, // preserve server response on error
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        ]);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    /**
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    public function setCurlOption($option, $value)
    {
        curl_setopt($this->ch, $option, $value);
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setCurlOptions($options)
    {
        curl_setopt_array($this->ch, $options);
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param string $method
     * @return void
     */
    private function beginRequest($method = 'GET')
    {
        $this->request = new Request($method);

        if ($this->isDebug()) {
            $this->log = 'HTTP Request' . PHP_EOL
                . '>>>>>>>>>> Begin HTTP Request <<<<<<<<<<' . PHP_EOL
                . 'Method: ' . $method . PHP_EOL;
        }
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug && !is_null($this->logger);
    }

    /**
     * @param bool $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return string
     */
    public function getUrl($endpoint)
    {
        return $this->baseUrl . $endpoint;
    }

    /**
     * @param string $endpoint
     * @return void
     */
    private function setUrl($endpoint)
    {
        $url = ltrim($endpoint, '/');

        if (!empty($this->baseUrl))
            $url = $this->baseUrl . '/' . $url;

        curl_setopt($this->ch, CURLOPT_URL, $url);

        if (strpos($url, '?') === false)
            $this->request->setUrl($url);
        else {
            $parts = explode('?', $url);
            parse_str($parts[1], $query);
            $this->request
                ->setUrl($parts[0])
                ->setQuery($query);
        }

        if ($this->isDebug())
            $this->log .= 'Url: ' . $url . PHP_EOL;
    }

    /**
     * @param array $headers
     * @param bool $decode_json
     * @param bool $urlencoded
     * @return void
     */
    private function setHeaders($headers, $decode_json = true, $urlencoded = false)
    {
        $headers = array_merge([
            'Content-Type' => 'application/' . ($urlencoded ? 'x-www-form-urlencoded' : 'json'),
            'Accept' => ($decode_json ? 'application/json' : '*/*'),
            'User-Agent' => static::USER_AGENT,
        ], $headers);

        $curl_headers = [];
        foreach ($headers as $key => $value)
            $curl_headers[] = $key . ': ' . $value;

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $curl_headers);

        $this->request->setHeaders($headers);

        if ($this->isDebug()) {
            $this->log .= '---------- Headers (' . count($curl_headers) . ')' . PHP_EOL;
            foreach ($curl_headers as $header)
                $this->log .= $header . PHP_EOL;
        }
    }

    /**
     * @param bool $decode_json
     * @return Response
     * @throws HttpException
     */
    private function getResponse($decode_json)
    {
        $output = curl_exec($this->ch);
        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $json_output = json_decode($output, true);

        if ($this->isDebug()) {
            $this->log .= '---------- Response HTTP/1.1 ' . $statusCode . ' (' . strlen($output) . ' o)' . PHP_EOL;

            if ($decode_json && is_array($json_output))
                foreach ($json_output as $key => $value)
                    $this->log .= $key . ': ' . (is_array($value) ? json_encode($value) : $value) . PHP_EOL;
            else
                $this->log .= $output . PHP_EOL;

            $this->log .= '>>>>>>>>>> End HTTP Request <<<<<<<<<<';

            $this->logger->debug($this->log);
        }

        if (!$statusCode || $statusCode >= 400) {
            $message = $output;

            if (is_array($json_output)) {
                if (array_key_exists('message', $json_output))
                    $message = $json_output['message'];
                elseif (array_key_exists('msg', $json_output))
                    $message = $json_output['msg'];
            }

            if ((empty($message) || (is_string($message) && empty(trim($message)))) && curl_errno($this->ch))
                $message = curl_error($this->ch);

            throw new HttpException($statusCode, $message, $this->request);
        }

        return new Response($statusCode, $decode_json ? $json_output : $output, $this->request);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param bool $decode_json
     * @param bool $urlencoded
     * @return Response
     * @throws HttpException
     */
    public function request($method, $endpoint, $data = [], $headers = [], $decode_json = true, $urlencoded = false)
    {
        $this->beginRequest($method);
        $this->setUrl($endpoint);
        $this->setHeaders($headers, $decode_json, $urlencoded);

        $this->request->setBody($data);

        if ($this->isDebug()) {
            $this->log .= '---------- Body (' . count($data) . ')' . PHP_EOL;
            foreach ($data as $key => $value)
                $this->log .= $key . ': ' . (is_array($value) ? json_encode($value) : $value) . PHP_EOL;
        }

        $data = $urlencoded ? http_build_query($data) : json_encode($data);

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);

        return $this->getResponse($decode_json);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @param bool $decode_json
     * @return Response
     * @throws HttpException
     */
    public function get($endpoint, $params = [], $headers = [], $decode_json = true)
    {
        $this->beginRequest();

        $url_suffix = '';
        foreach ($params as $key => $value)
            $url_suffix .= '&' . $key . '=' . urlencode($value);

        $this->setUrl($endpoint . preg_replace('/^&/', '?', $url_suffix));
        $this->setHeaders($headers, $decode_json);

        curl_setopt($this->ch, CURLOPT_HTTPGET, true);

        return $this->getResponse($decode_json);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param bool $decode_json
     * @param bool $urlencoded
     * @return Response
     * @throws HttpException
     */
    public function post($endpoint, $data = [], $headers = [], $decode_json = true, $urlencoded = false)
    {
        return $this->request('POST', $endpoint, $data, $headers, $decode_json, $urlencoded);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param bool $decode_json
     * @param bool $urlencoded
     * @return Response
     * @throws HttpException
     */
    public function put($endpoint, $data = [], $headers = [], $decode_json = true, $urlencoded = false)
    {
        return $this->request('PUT', $endpoint, $data, $headers, $decode_json, $urlencoded);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param bool $decode_json
     * @param bool $urlencoded
     * @return Response
     * @throws HttpException
     */
    public function patch($endpoint, $data = [], $headers = [], $decode_json = true, $urlencoded = false)
    {
        return $this->request('PATCH', $endpoint, $data, $headers, $decode_json, $urlencoded);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param bool $decode_json
     * @param bool $urlencoded
     * @return Response
     * @throws HttpException
     */
    public function delete($endpoint, $data = [], $headers = [], $decode_json = true, $urlencoded = false)
    {
        return $this->request('DELETE', $endpoint, $data, $headers, $decode_json, $urlencoded);
    }
}
