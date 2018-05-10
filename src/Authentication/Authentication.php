<?php declare(strict_types=1);

namespace Clapi\Authentication;

class Authentication
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var Credential
     */
    private $credential;

    public function __construct(string $type, Credential $credential)
    {
        $this->type = $type;
        $this->credential = $credential;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCredential(): Credential
    {
        return $this->credential;
    }
}
