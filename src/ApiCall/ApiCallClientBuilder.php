<?php

namespace Clapi\ApiCall;

use Clapi\Authentication\Authentication;
use Clapi\Authentication\Credential;
use Clapi\Authentication\EscherCredential;
use EightPoints\Guzzle\WsseAuthMiddleware;
use Guzzle\Http\Middleware\EscherMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class ApiCallClientBuilder
{
    /**
     * @var array
     */
    private $clientConfig;

    public function __construct(array $config = [])
    {
        $this->clientConfig = $config;

        if ($this->handlerDidNotSet()) {
            $this->setDefaultHandler();
        }
    }

    public function setAuth(Authentication $authentication): void
    {
        switch ($authentication->getType()) {
            case 'basic':
                $this->setBasicAuthentication($authentication->getCredential());
                break;
            case 'escher':
                $this->setEscherAuthentication($authentication->getCredential());
                break;
            case 'wsse':
                $this->setWsseAuthentication($authentication->getCredential());
                break;
            default:
                $errorMessage = sprintf('Authentication type %s is not supported', $authentication->getType());
                throw new \InvalidArgumentException($errorMessage);
        }
    }

    public function build(): Client
    {
        return new Client($this->clientConfig);
    }

    private function handlerDidNotSet(): bool
    {
        return !array_key_exists('handler', $this->clientConfig) || empty($this->clientConfig['handler']);
    }

    private function setDefaultHandler(): void
    {
        $this->clientConfig['handler'] = HandlerStack::create();
    }

    private function setBasicAuthentication(Credential $credential): void
    {
        $this->clientConfig['auth'] = [$credential->getKey(), $credential->getSecret()];
    }

    private function setEscherAuthentication(EscherCredential $credential): void
    {
        $credential = new \Guzzle\Http\Middleware\EscherCredential($credential->getKey(), $credential->getSecret(), $credential->getScope());
        $escherMiddleware = new EscherMiddleware($credential);

        $this->clientConfig['auth'] = 'escher';
        $this->clientConfig['handler']->unshift($escherMiddleware);
    }

    private function setWsseAuthentication(Credential $credential): void
    {
        $wsseMiddleware = new WsseAuthMiddleware($credential->getKey(), $credential->getSecret());

        $this->clientConfig['handler']->unshift($wsseMiddleware->attach());
    }
}
