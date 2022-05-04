<?php

namespace App\Core\ExternalRequest;

use GuzzleHttp\Client;

abstract class BaseExternalRequest
{
    protected $baseUri;

    /**
     * Get base uri
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri ?? '';
    }

    /**
     * Get uri
     *
     * @return string
     */
    abstract protected function getUri(): string;

    /**
     * Get method
     *
     * @return string
     */
    abstract protected function getMethod(): string;
    
    /**
     * Get header
     *
     * @return array
     */
    abstract protected function getHeaders(): array;

    /**
     * Get body
     *
     * @return array
     */
    protected function getBody(): array
    {
        return $this->handleRequest();
    }

    /**
     * Handle request data
     *
     * @return array
     */
    abstract protected function handleRequest(): array;

    /**
     * Handle response
     *
     * @param array $res response
     *
     * @return array
     */
    abstract protected function handleResponse(array $res): array;


    public function call(): array
    {
        $client = new Client([
            'base_uri' => $this->getBaseUri(),
            'headers' => $this->getHeaders(),
        ]);
        $res = $client->request($this->getMethod(), $this->getUri(), $this->getBody());
        $data = $res->getBody()->getContents();

        return $this->handleResponse(json_decode($data, true));
    }
}
