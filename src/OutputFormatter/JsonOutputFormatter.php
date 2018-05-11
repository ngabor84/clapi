<?php declare(strict_types=1);

namespace Clapi\OutputFormatter;

use Symfony\Component\Console\Formatter\OutputFormatter;

class JsonOutputFormatter extends OutputFormatter
{
    public function format($string): string
    {
        $formattedMessage = parent::format($string);

        if ($this->isJsonString($string)) {
            $formattedMessage = json_decode($formattedMessage, true);
        }

        return print_r($formattedMessage, true);
    }

    private function isJsonString(string $string): bool
    {
        if (empty($string)) {
            return false;
        }

        return json_decode($string, true) !== null;
    }
}
