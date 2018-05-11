<?php declare(strict_types=1);

namespace Clapi\Authentication;

class EscherCredential extends Credential
{
    /**
     * @var string
     */
    private $scope;

    public function __construct(string $key, string $secret, string $scope)
    {
        parent::__construct($key, $secret);
        $this->scope = $scope;
    }

    public function getScope(): string
    {
        return $this->scope;
    }
}
