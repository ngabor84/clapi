<?php declare(strict_types=1);

namespace Clapi\ApiCall;

use Clapi\Authentication\Authentication;

class ApiCallOptions
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $payload;

    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * @var array
     */
    private $headers = [];

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setPayload(?string $payload): void
    {
        $this->payload = $payload;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function hasPayload(): bool
    {
        return !empty($this->payload);
    }

    public function setAuthentication(?Authentication $authentication): void
    {
        $this->authentication = $authentication;
    }

    public function getAuthentication(): ?Authentication
    {
        return $this->authentication;
    }

    public function hasAuthentication(): bool
    {
        return !empty($this->authentication);
    }

    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(): bool
    {
        return count($this->headers) > 0;
    }
}
