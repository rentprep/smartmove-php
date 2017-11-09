<?php

namespace SmartMove;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class HttpClient {

    /**
	 * HTTP verb - GET. For getting objects.
	 * @var string
	 */
	const METHOD_GET = 'GET';
	/**
	 * HTTP verb - POST. For creating object.
	 * @var string
	 */
	const METHOD_POST = 'POST';
	/**
	 * HTTP verb - PUT. For updating objects.
	 * @var string
	 */
	const METHOD_PUT = 'PUT';
	/**
	 * HTTP verb - DELETE. For deleting objects.
	 * @var string
	 */
	const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Psr\Log\LoggerInterface;
     */
    private $logger;

    /**
     * @var GuzzleHttp\Client
     */
    private $transport = null;

    /**
     * @var Psr\Http\Message\RequestInterface
     */
    private $request = null;

    /**
     * @var Psr\Http\Message\ResponseInterface
     */
    private $response = null;

    /**
     * Instantiate
     */
    public function __construct($apiKey, $baseUri, LoggerInterface $logger = null) {
        $this->apiKey = $apiKey;
        $this->transport = new Client(['base_uri' => $baseUri]);
        $this->logger = $logger;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
    }

    public function get($path, array $params = []) {
        return $this->request(self::METHOD_GET, $path, $params);
    }

    public function post($path, array $params = []) {
        return $this->request(self::METHOD_POST, $path, $params);
    }

    public function put($path, array $params = []) {
        return $this->request(self::METHOD_PUT, $path, $params);
    }

    public function delete($path, array $params = []) {
        return $this->request(self::METHOD_DELETE, $path, $params);
    }

    /**
     * Create request
     *
     * @return mixed
     */
    private function request($method, $path, array $params = []) {
        $request = new Request($method, $path);

        return $this->send($request, $params);
    }

    /**
     * Send request
     *
     * @return mixed
     */
    private function send(RequestInterface $request, array $params = []) {
        $this->request = $request;

        $options = $this->prepareOptions(
            $request->getMethod(),
            $request->getRequestTarget(),
            $params
        );

        try {
            $this->response = $response = $this->transport->send($request, $options);
        } catch(ClientException $e) {
            if($e->getCode() == 400) {
                $this->logErrors($e);
                return false;
            }
            throw $e;
        }

        return json_decode($response->getBody(), true) ?: $response->getStatusCode() == 200;
    }

    private function prepareOptions($method, $path, array $params = []) {
        $options = [];

        if ($params) {
            $format = ($method == self::METHOD_GET) ? RequestOptions::QUERY : RequestOptions::JSON;
            $options[$format] = $params;
        }

        $options[RequestOptions::HEADERS] = [
            'User-Agent' => 'smartmove/php/' . SmartMove::VERSION,
            'x-apiKey' => $this->apiKey
        ];

        return $options;
    }

    private function logErrors(ClientException $e) {
        if(!$this->logger) {
            return;
        }

        $msg = explode(PHP_EOL, $e->getMessage());
        $msg = $msg[0] . ' ' . json_decode($msg[1], true)['Message'];

        $this->logger->error($msg);
    }
}
