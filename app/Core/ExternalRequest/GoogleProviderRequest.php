<?php

namespace App\Core\ExternalRequest;

class GoogleProviderRequest extends BaseExternalRequest
{
    protected $uri;

    protected $params;

    protected $method;

    /**
     * Set params request
     *
     * @param string $params params
     * 
     * @return this
     */
    public function setParamsRequet(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set uri
     *
     * @param string $uri uri
     * 
     * @return this
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Set uri
     *
     * @param string $uri uri
     * 
     * @return this
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get methid
     *
     * @return string
     */
    public function getMethod(): string
    {
        return strtoupper($this->method);
    }

    /**
     * Get header request
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return ['Content-Type' => 'application/x-www-form-urlencoded'];
    }

    /**
     * Handle request data
     *
     * @return array
     */
    public function handleRequest(): array
    {
        return ['form_params' => $this->params];
    }

    /**
     * Handle response
     *
     * @param array $res response
     *
     * @return array
     */
    public function handleResponse(array $res): array
    {
        return $res;
    }
}

