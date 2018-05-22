<?php

namespace Clapi\Test\ApiCall;

use Clapi\ApiCall\ApiCallClientBuilder;
use Clapi\Authentication\Authentication;
use Clapi\Authentication\Credential;
use Clapi\Authentication\EscherCredential;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiCallClientBuilderTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     */
    public function build_CreateAGuzzleHttpClient_Perfect(): void
    {
        $builder = new ApiCallClientBuilder();
        $client = $builder->build();

        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * @test
     */
    public function build_CreateAClientWithGivenConfiguration_WhenConfigWasProvidedToConstructor(): void
    {
        $testConfig = [
            'url' => 'http://test.it'
        ];
        $builder = new ApiCallClientBuilder($testConfig);
        $client = $builder->build();

        $this->assertArraySubset($testConfig, $client->getConfig());
    }

    /**
     * @test
     */
    public function build_CreateAClientWithBasicAuthentication_WhenSetAuthWasCalledWithBasicAuth(): void
    {
        $builder = $this->createApiCallClientBuilder();

        $auth = new Authentication('basic', new Credential('test_key', 'test_secret'));
        $builder->setAuth($auth);
        $client = $builder->build();

        $client->post('http://test.it');

        $request = $this->getRequestFromClientHistory();
        $this->assertTrue($request->hasHeader('Authorization'));
        $authHeader = $request->getHeader('Authorization')[0];
        $this->assertEquals('Basic dGVzdF9rZXk6dGVzdF9zZWNyZXQ=', $authHeader);
    }

    /**
     * @test
     */
    public function build_CreateAClientWithEscherAuthentication_WhenSetAuthWasCalledWithEscherAuth(): void
    {
        $builder = $this->createApiCallClientBuilder();

        $auth = new Authentication('escher', new EscherCredential('test_key', 'test_secret', 'some/test/scope'));
        $builder->setAuth($auth);
        $client = $builder->build();

        $client->post('http://test.it', ['auth' => 'escher']);

        $request = $this->getRequestFromClientHistory();
        $this->assertTrue($request->hasHeader('x-ems-auth'));
        $this->assertTrue($request->hasHeader('x-ems-date'));
        $authHeader = $request->getHeader('x-ems-auth')[0];
        $expectedHeaderPart = sprintf('EMS-HMAC-SHA256 Credential=test_key/%s/some/test/scope', date('Ymd'));
        $this->assertContains($expectedHeaderPart, $authHeader);
    }

    /**
     * @test
     */
    public function build_CreateAClientWithWsseAuthentication_WhenSetAuthWasCalledWithWsseAuth(): void
    {
        $builder = $this->createApiCallClientBuilder();

        $auth = new Authentication('wsse', new Credential('test_key', 'test_secret'));
        $builder->setAuth($auth);
        $client = $builder->build();

        $client->post('http://test.it');

        $request = $this->getRequestFromClientHistory();
        $this->assertTrue($request->hasHeader('X-WSSE'));
        $this->assertTrue($request->hasHeader('Authorization'));
        $authHeader = $request->getHeader('X-WSSE')[0];
        $this->assertContains('UsernameToken Username="test_key"', $authHeader);
    }
}
