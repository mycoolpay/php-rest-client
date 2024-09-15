<?php

namespace MyCoolPay\Http;

use MyCoolPay\Http\Constant\DataType;
use MyCoolPay\Http\Constant\Http;
use MyCoolPay\Http\Exception\HttpException;
use MyCoolPay\Http\Inheritance\AuthStrategy;
use MyCoolPay\Logging\LoggerInterface;

class RestClient
{
    /**
     * @var string $agent
     */
    protected $agent = 'MyCoolPay/PHP/RestClient';
    /**
     * @var AuthStrategy|null $auth
     */
    protected $auth;
    /**
     * @var string $baseUrl
     */
    protected $baseUrl;
    /**
     * @var array $baseHeaders
     */
    protected $baseHeaders = [];
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
        $this->setBaseUrl($base_url);
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
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param string $agent
     * @return $this
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return AuthStrategy|null
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param AuthStrategy|null $auth
     * @return $this
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
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
     * @param string $base_url
     * @return $this
     */
    final public function setBaseUrl($base_url)
    {
        $this->baseUrl = rtrim($base_url, '/');
        return $this;
    }

    /**
     * @param array $additional_headers
     * @return array
     */
    public function getBaseHeaders($additional_headers = [])
    {
        return array_merge($this->baseHeaders, $additional_headers);
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setBaseHeaders($headers)
    {
        $this->baseHeaders = $headers;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setBaseHeader($name, $value)
    {
        $this->baseHeaders[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeBaseHeader($name)
    {
        if (array_key_exists($name, $this->baseHeaders))
            unset($this->baseHeaders[$name]);
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function addBaseHeaders($headers)
    {
        $this->baseHeaders = array_merge($this->baseHeaders, $headers);
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
    private function beginRequest($method = Http::GET)
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

        if (!(empty($this->baseUrl) || preg_match('#^https?://#i', $url)))
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
     * @param string $data_type
     * @param string $response_type
     * @return void
     */
    protected function setHeaders($headers, $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        $base_headers = $this->getBaseHeaders([
            'Accept' => $response_type,
            'User-Agent' => $this->agent,
        ]);
        if ($data_type !== DataType::NONE) {
            $base_headers['Content-Type'] = $data_type;
        }
        if (!is_null($this->auth)) {
            $base_headers = $this->auth->getAuthHeaders($base_headers);
        }
        $headers = array_merge($base_headers, $headers);

        $curl_headers = [];
        foreach ($headers as $key => $value)
            $curl_headers[] = $key . ': ' . $value;

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $curl_headers);

        $this->request->setHeaders($headers)
            ->setDataType($data_type)
            ->setResponseType($response_type);

        if ($this->isDebug()) {
            $this->log .= '---------- Headers (' . count($curl_headers) . ')' . PHP_EOL;
            foreach ($curl_headers as $header)
                $this->log .= $header . PHP_EOL;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function sanitizeDecodedXML(&$data)
    {
        foreach ($data as &$value) {
            if ($value === [])
                $value = null;
            elseif (is_array($value))
                $this->sanitizeDecodedXML($value);
        }

        return $data;
    }

    /**
     * @param string $format
     * @return Response
     * @throws HttpException
     */
    protected function getResponse($format = DataType::JSON)
    {
        $output = curl_exec($this->ch);
        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $parsed_output = null;

        if ($output !== false && $format !== DataType::ANY) {
            switch ($format) {
                case DataType::JSON:
                    $parsed_output = json_decode($output, true);
                    break;
                case DataType::XML:
                    $parsed_output = [];
                    $parsed_xml = simplexml_load_string($output);
                    if ($parsed_xml) {
                        $parsed_xml = json_encode($parsed_xml);
                        if ($parsed_xml) {
                            $parsed_xml = json_decode($parsed_xml, true);
                            $parsed_output = $this->sanitizeDecodedXML($parsed_xml);
                        }
                    }
                    break;
                default:
                    $parsed_output = $output;
            }
        }

        if ($this->isDebug()) {
            $this->log .= '---------- Response HTTP/1.1 ' . $statusCode . ' (' . strlen($output) . ' o)' . PHP_EOL;
            $this->log .= $output . PHP_EOL;
            $this->log .= '>>>>>>>>>> End HTTP Request <<<<<<<<<<';

            $this->logger->debug($this->log);
        }

        if (!$statusCode || $statusCode >= 400) {
            $message = $output;

            if (is_array($parsed_output)) {
                if (array_key_exists('message', $parsed_output))
                    $message = $parsed_output['message'];
                elseif (array_key_exists('msg', $parsed_output))
                    $message = $parsed_output['msg'];
            }

            if (is_string($message))
                $message = trim($message);

            if (empty($message) && curl_errno($this->ch))
                $message = curl_error($this->ch);

            throw new HttpException($statusCode, $message, $this->request);
        }

        return new Response($statusCode, $output, $parsed_output, $format, $this->request);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @param string $response_type
     * @return Response
     * @throws HttpException
     */
    public function get($endpoint, $params = [], $headers = [], $response_type = DataType::JSON)
    {
        $this->beginRequest();

        $querystring = http_build_query($params);
        if (!empty($querystring))
            $querystring = '?' . $querystring;

        $this->setUrl($endpoint . $querystring);
        $this->setHeaders($headers, DataType::NONE, $response_type);

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, Http::GET);

        return $this->getResponse($response_type);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param mixed $data
     * @param array $headers
     * @param string $data_type
     * @param string $response_type
     * @return Response
     * @throws HttpException
     */
    public function request($method, $endpoint, $data = [], $headers = [], $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        if ($method === Http::GET) {
            return $this->get($endpoint, [], $headers, $response_type);
        }

        $this->beginRequest($method);
        $this->setUrl($endpoint);
        $this->setHeaders($headers, $data_type, $response_type);

        $this->request->setBody($data);

        if ($this->isDebug()) {
            $this->log .= '---------- Body (' . count($data) . ')' . PHP_EOL;
            if (is_array($data)) {
                foreach ($data as $key => $value)
                    $this->log .= $key . ': ' . (is_array($value) ? json_encode($value) : $value) . PHP_EOL;
            } else {
                $this->log .= $data;
            }
        }

        switch ($data_type) {
            case DataType::JSON:
                $encoded_data = json_encode($data);
                break;
            case DataType::URL_ENCODED:
                $encoded_data = http_build_query($data);
                break;
            default:
                $encoded_data = $data;
        }

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $encoded_data);

        return $this->getResponse($response_type);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws HttpException
     */
    public function execute($request)
    {
        return $this->request(
            $request->getMethod(),
            $request->getUrl(),
            $request->getBody(),
            $request->getHeaders(),
            $request->getDataType(),
            $request->getResponseType()
        );
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param string $data_type
     * @param string $response_type
     * @return Response
     * @throws HttpException
     */
    public function post($endpoint, $data = [], $headers = [], $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        return $this->request(Http::POST, $endpoint, $data, $headers, $data_type, $response_type);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param string $data_type
     * @param string $response_type
     * @return Response
     * @throws HttpException
     */
    public function put($endpoint, $data = [], $headers = [], $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        return $this->request(Http::PUT, $endpoint, $data, $headers, $data_type, $response_type);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param string $data_type
     * @param string $response_type
     * @return Response
     * @throws HttpException
     */
    public function patch($endpoint, $data = [], $headers = [], $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        return $this->request(Http::PATCH, $endpoint, $data, $headers, $data_type, $response_type);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @param string $data_type
     * @param string $response_type
     * @return Response
     * @throws HttpException
     */
    public function delete($endpoint, $data = [], $headers = [], $data_type = DataType::JSON, $response_type = DataType::JSON)
    {
        return $this->request(Http::DELETE, $endpoint, $data, $headers, $data_type, $response_type);
    }
}
