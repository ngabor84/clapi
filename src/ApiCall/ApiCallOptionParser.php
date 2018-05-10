<?php

namespace Clapi\ApiCall;

use Clapi\Authentication\Authentication;
use Clapi\Authentication\Credential;
use Clapi\Authentication\EscherCredential;
use Symfony\Component\Console\Input\InputInterface;

class ApiCallOptionParser
{
    public function parse(InputInterface $input): ApiCallOptions
    {
        $options = new ApiCallOptions();
        $options->setUrl($this->parseUrl($input));
        $options->setMethod($this->parseMethod($input));
        $options->setPayload($this->parsePayload($input));
        $options->setAuthentication($this->parseAuth($input));

        foreach ($this->parseHeaders($input) as $header) {
            [$headerName, $headerValue] = explode(':', $header);
            $options->addHeader($headerName, $headerValue);
        }

        return $options;
    }

    private function parseUrl(InputInterface $input): string
    {
        if (!$input->hasArgument('URL')) {
            throw new \InvalidArgumentException('URL is required');
        }

        return $input->getArgument('URL');
    }

    private function parseMethod(InputInterface $input): string
    {
        return $input->getOption('method');
    }

    private function parsePayload(InputInterface $input): ?string
    {
        return $input->getOption('payload');
    }

    private function parseAuth(InputInterface $input): ?Authentication
    {
        $authType = $input->getOption('auth');

        if (!empty($authType)) {
            $credential = $this->parseCredential($authType, $input);

            return new Authentication($authType, $credential);
        }

        return null;
    }

    private function parseCredential(string $authType, InputInterface $input): Credential
    {
        if (!$input->hasOption('key')) {
            throw new \InvalidArgumentException('Key is required for authentication');
        }

        if (!$input->hasOption('secret')) {
            throw new \InvalidArgumentException('Secret is required for authentication');
        }

        if ($authType === 'escher') {
            if (!$input->hasOption('scope')) {
                throw new \InvalidArgumentException('Scope is required for escher authentication');
            }

            return new EscherCredential($input->getOption('key'), $input->getOption('secret'), $input->getOption('scope'));
        }

        return new Credential($input->getOption('key'), $input->getOption('secret'));
    }

    private function parseHeaders(InputInterface $input): array
    {
        return $input->getOption('header');
    }
}
