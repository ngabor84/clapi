<?php declare(strict_types=1);

namespace Clapi\ApiCall;

use Psr\Http\Message\ResponseInterface;

class ApiCall
{
    /**
     * @var array
     */
    private $requestConfig;

    /**
     * @var ApiCallClientBuilder
     */
    private $clientBuilder;

    public function __construct(ApiCallClientBuilder $clientBuilder)
    {
        $this->clientBuilder = $clientBuilder;
        $this->requestConfig = [];
    }

    public function execute(ApiCallOptions $options): ResponseInterface
    {
        $this->applyOptions($options);
        $client = $this->clientBuilder->build();

        return $client->request($options->getMethod(), $options->getUrl(), $this->requestConfig);
    }

    private function applyOptions(ApiCallOptions $options): void
    {
        if ($options->hasPayload()) {
            $this->setPayload($options->getPayload());
        }

        if ($options->hasAuthentication()) {
            $this->clientBuilder->setAuth($options->getAuthentication());
        }

        if ($options->hasHeader()) {
            $this->setHeaders($options->getHeaders());
        }
    }

    private function setPayload(string $payload): void
    {
        $this->requestConfig['body'] = $payload;
    }

    private function setHeaders(array $headers): void
    {
        $this->requestConfig['headers'] = $headers;
    }
}
