<?php

namespace Clapi\Test\OutputFormatter;

use Clapi\OutputFormatter\JsonOutputFormatter;
use PHPUnit\Framework\TestCase;

class JsonOutputFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function format_ReturnWithFormattedString_WhenValidJsonStringWasGiven()
    {
        $formatter = new JsonOutputFormatter();
        $testData = ['a' => 'b', 'c' => ['d' => ['e' => 'f'], 'g' => ['h' => 'i']]];
        $testJsonString = json_encode($testData);
        $expectedString = print_r($testData, true);

        $this->assertEquals($expectedString, $formatter->format($testJsonString));
    }

    /**
     * @test
     */
    public function format_ReturnWithOriginalString_WhenNotAValidJsonStringWasGiven()
    {
        $formatter = new JsonOutputFormatter();
        $testJsonString = 'not a json string';

        $this->assertEquals($testJsonString, $formatter->format($testJsonString));
    }
}
