<?php

namespace Clapi\Test\ApiCall;

use Clapi\ApiCall\ApiCallOptions;
use Clapi\Authentication\Authentication;
use Clapi\Authentication\Credential;
use PHPUnit\Framework\TestCase;

class ApiCallOptionsTest extends TestCase
{
    /**
     * @var ApiCallOptions
     */
    private $options;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->options = new ApiCallOptions();
    }

    /**
     * @test
     */
    public function setUrl_SetUrlProperty_GetUrlProvideTheSetUrl(): void
    {
        $this->options->setUrl('https://test.it');
        
        $this->assertEquals('https://test.it', $this->options->getUrl());
    }

    /**
     * @test
     */
    public function setMethod_SetMethodProperty_GetMethodProvideTheSetMethod(): void
    {
        $this->options->setMethod('POST');

        $this->assertEquals('POST', $this->options->getMethod());
    }

    /**
     * @test
     */
    public function setPayload_SetPayloadProperty_GetPayloadProvideTheSetPayload(): void
    {
        $this->options->setPayload('{"someKey": "someValue"}');

        $this->assertEquals('{"someKey": "someValue"}', $this->options->getPayload());
    }

    /**
     * @test
     */
    public function hasPayload_ReturnTrue_WhenPayloadWasSet(): void
    {
        $this->options->setPayload('{"someKey": "someValue"}');

        $this->assertTrue($this->options->hasPayload());
    }

    /**
     * @test
     */
    public function hasPayload_ReturnFalse_WhenPayloadWasNotSet(): void
    {
        $this->assertFalse($this->options->hasPayload());
    }

    /**
     * @test
     */
    public function setAuthentication_SetAuthenticationProperty_GetAuthenticationProvideTheSetAuthentication(): void
    {
        $testAuth = new Authentication('wsse', new Credential('testKey', 'testSecret'));
        $this->options->setAuthentication($testAuth);

        $this->assertEquals($testAuth, $this->options->getAuthentication());
    }

    /**
     * @test
     */
    public function hasAuthentication_ReturnTrue_WhenAuthenticationWasSet(): void
    {
        $testAuth = new Authentication('wsse', new Credential('testKey', 'testSecret'));
        $this->options->setAuthentication($testAuth);

        $this->assertTrue($this->options->hasAuthentication());
    }

    /**
     * @test
     */
    public function hasAuthentication_ReturnFalse_WhenAuthenticationWasNotSet(): void
    {
        $this->assertFalse($this->options->hasAuthentication());
    }

    /**
     * @test
     */
    public function addHeader_AddHeaderToOptions_GetHeadersProvideTheAddedHeaders(): void
    {
        $testHeaders = [
            'testHeaderName1' => 'testHeaderValue1',
            'testHeaderName2' => 'testHeaderValue2',
            'testHeaderName3' => 'testHeaderValue3',
        ];

        foreach ($testHeaders as $headerName => $headerValue) {
            $this->options->addHeader($headerName, $headerValue);
        }

        $this->assertEquals($testHeaders, $this->options->getHeaders());
    }

    /**
     * @test
     */
    public function hasHeader_ReturnTrue_WhenHeaderWasAdded(): void
    {
        $this->options->addHeader('testHeaderName', 'testHeaderValue');

        $this->assertTrue($this->options->hasHeader());
    }

    /**
     * @test
     */
    public function hasHeader_ReturnFalse_WhenHeaderWasNotAdded(): void
    {
        $this->assertFalse($this->options->hasHeader());
    }
}
