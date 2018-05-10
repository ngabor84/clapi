<?php

namespace Clapi\Test\ApiCall;

use Clapi\ApiCall\ApiCallClientBuilder;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

trait TestHelper
{
    private $clientHistory = [];

    private function createApiCallClientBuilder(): ApiCallClientBuilder
    {
        $history = Middleware::history($this->clientHistory);
        $handlerStack = HandlerStack::create();
        $handlerStack->push($history);
        $clientConfig = ['handler' => $handlerStack];

        return new ApiCallClientBuilder($clientConfig);
    }

    private function getRequestFromClientHistory($requestNumber = 0): RequestInterface
    {
        return $this->clientHistory[$requestNumber]['request'];
    }
}
