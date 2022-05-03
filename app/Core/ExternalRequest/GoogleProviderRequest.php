<?php

namespace App\Core\ExternalRequest;

class GoogleProviderRequest extends BaseExternalRequest
{
    protected $uri;

    protected $params;

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
        return ['body' => $this->params];
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

