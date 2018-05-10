<?php

namespace Clapi\Test\ApiCall;

use Clapi\ApiCall\ApiCallOptionParser;
use Clapi\ApiCall\ApiCallOptions;
use Clapi\Authentication\Authentication;
use Clapi\Authentication\Credential;
use Clapi\Authentication\EscherCredential;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;

class ApiCallOptionParserTest extends TestCase
{
    private const METHOD = 'POST';
    private const WSSE_AUTH = 'wsse';
    private const ESCHER_AUTH = 'escher';
    private const KEY = 'test_key';
    private const SECRET = 'test_secret';
    private const HEADER = 'Custom-Header: test';
    private const PAYLOAD = '{"someKey": "someValue"}';
    private const URL = 'https://test.it';
    private const SCOPE = 'some/scope/ex';

    /**
     * @var ApiCallOptionParser
     */
    private $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new ApiCallOptionParser();
    }

    /**
     * @test
     */
    public function parse_ReturnWithTheProperApiCallOptions_WhenGivenInputWasOk(): void
    {
        $arguments = [
            'URL' => self::URL
        ];
        $options = [
            'method' => self::METHOD,
            'payload' => self::PAYLOAD,
            'auth' => self::WSSE_AUTH,
            'key' => self::KEY,
            'secret' => self::SECRET,
            'scope' => self::SCOPE,
            'header' => [self::HEADER],
        ];

        $this->assertEquals(
            $this->expectedOption($arguments, $options),
            $this->parser->parse($this->mockInput($arguments, $options))
        );
    }

    /**
     * @test
     * @dataProvider missingParameretDataProvider
     */
    public function parse_ThrowsException_WhenRequiredParameterIsMissing($arguments, $options): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->assertEquals(
            $this->expectedOption($arguments, $options),
            $this->parser->parse($this->mockInput($arguments, $options))
        );
    }

    public function missingParameretDataProvider()
    {
        return [
            // Missing URL
            [
                [],
                [
                    'method' => self::METHOD,
                    'payload' => self::PAYLOAD,
                    'auth' => self::WSSE_AUTH,
                    'key' => self::KEY,
                    'secret' => self::SECRET,
                    'header' => [self::HEADER],
                ]
            ],
            // Use wsse auth without key and secret
            [
                [
                    'URL' => self::URL
                ],
                [
                    'method' => self::METHOD,
                    'payload' => self::PAYLOAD,
                    'auth' => self::WSSE_AUTH,
                    'header' => [self::HEADER],
                ]
            ],
            // Use wsse auth without secret
            [
                [
                    'URL' => self::URL
                ],
                [
                    'method' => self::METHOD,
                    'payload' => self::PAYLOAD,
                    'auth' => self::WSSE_AUTH,
                    'key' => self::KEY,
                    'header' => [self::HEADER],
                ]
            ],
            // Use wsse auth without key
            [
                [
                    'URL' => self::URL
                ],
                [
                    'method' => self::METHOD,
                    'payload' => self::PAYLOAD,
                    'auth' => self::WSSE_AUTH,
                    'secret' => self::SECRET,
                    'header' => [self::HEADER],
                ]
            ],
            // Use escher auth without scope
            [
                [
                    'URL' => self::URL
                ],
                [
                    'method' => self::METHOD,
                    'payload' => self::PAYLOAD,
                    'auth' => self::ESCHER_AUTH,
                    'key' => self::KEY,
                    'secret' => self::SECRET,
                    'header' => [self::HEADER],
                ]
            ]
        ];
    }

    private function mockInput(array $arguments, array $options): InputInterface
    {
        $argsMock = $this->createMock(ArgvInput::class);
        $hasArgument = function ($argumentName) use ($arguments) {
            return array_key_exists($argumentName, $arguments);
        };
        $getArgument = function ($argumentName) use ($arguments) {
            return $arguments[$argumentName] ?? null;
        };
        $hasOption = function ($optionName) use ($options) {
            return array_key_exists($optionName, $options);
        };
        $getOption = function ($optionName) use ($options) {
            return $options[$optionName] ?? null;
        };

        $argsMock->expects($this->any())->method('hasArgument')->willReturnCallback($hasArgument);
        $argsMock->expects($this->any())->method('getArgument')->willReturnCallback($getArgument);
        $argsMock->expects($this->any())->method('hasOption')->willReturnCallback($hasOption);
        $argsMock->expects($this->any())->method('getOption')->willReturnCallback($getOption);

        return $argsMock;
    }

    private function expectedOption(array $arguments, array $options): ApiCallOptions
    {
        $option = new ApiCallOptions();

        if (array_key_exists('URL', $arguments)) {
            $option->setUrl($arguments['URL']);
        }

        if (array_key_exists('method', $options)) {
            $option->setMethod($options['method']);
        }

        if (array_key_exists('auth', $options) && array_key_exists('key', $options) && array_key_exists('secret', $options)) {
            if ($options['auth'] == 'escher') {
                $credential = new EscherCredential($options['key'], $options['secret'], $options['scope'] ?? '');
            } else {
                $credential = new Credential($options['key'], $options['secret']);
            }

            $auth = new Authentication($options['auth'], $credential);
            $option->setAuthentication($auth);
        }

        if (array_key_exists('header', $options)) {
            foreach ($options['header'] as $header) {
                $option->addHeader(...explode(':', $header));
            }
        }

        if (array_key_exists('payload', $options)) {
            $option->setPayload($options['payload']);
        }

        return $option;
    }
}
