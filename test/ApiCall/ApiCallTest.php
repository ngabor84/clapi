<?php

namespace Clapi\Test\ApiCall;

use Clapi\ApiCall\ApiCall;
use Clapi\ApiCall\ApiCallOptions;
use Clapi\Authentication\Authentication;
use Clapi\Authentication\Credential;
use PHPUnit\Framework\TestCase;

class ApiCallTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     */
    public function execute_MakeRequest_WhenUrlAndMethodWereProvided()
    {
        $clientBuilder = $this->createApiCallClientBuilder();
        $apiCall = new ApiCall($clientBuilder);
        $callOptions = new ApiCallOptions();
        $callOptions->setMethod('GET');
        $callOptions->setUrl('http://test.it');
        $apiCall->execute($callOptions);

        $request = $this->getRequestFromClientHistory();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http', $request->getUri()->getScheme());
        $this->assertEquals('test.it', $request->getUri()->getHost());
    }

    /**
     * @test
     */
    public function execute_MakeRequestWithPayload_WhenPayloadWasProvided()
    {
        $clientBuilder = $this->createApiCallClientBuilder();
        $apiCall = new ApiCall($clientBuilder);
        $callOptions = new ApiCallOptions();
        $callOptions->setMethod('POST');
        $callOptions->setUrl('http://test.it');
        $callOptions->setPayload('{"test": "it"}');
        $apiCall->execute($callOptions);

        $request = $this->getRequestFromClientHistory();
        $this->assertEquals('{"test": "it"}', $request->getBody()->getContents());
    }

    /**
     * @test
     */
    public function execute_MakeRequestWithWsseAuth_WhenWsseAuthenticationWasProvided()
    {
        $clientBuilder = $this->createApiCallClientBuilder();
        $apiCall = new ApiCall($clientBuilder);
        $callOptions = new ApiCallOptions();
        $callOptions->setMethod('POST');
        $callOptions->setUrl('http://test.it');
        $authentication = new Authentication('wsse', new Credential('test_key', 'test_secret'));
        $callOptions->setAuthentication($authentication);
        $apiCall->execute($callOptions);

        $request = $this->getRequestFromClientHistory();
        $this->assertTrue($request->hasHeader('X-WSSE'));
        $this->assertTrue($request->hasHeader('Authorization'));
        $authHeader = $request->getHeader('X-WSSE')[0];
        $this->assertContains('UsernameToken Username="test_key"', $authHeader);
    }
}
