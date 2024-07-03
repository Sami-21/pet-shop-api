<?php

namespace App\Services;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\HasClaim;

class JwtService
{
    protected $config;

    public function __construct()
    {
        $privateKey = InMemory::file(storage_path('/keys/private.key'));
        $publicKey = InMemory::file(storage_path('/keys/public.key'));

        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            $privateKey,
            $publicKey
        );
    }

    public function generateToken(string $claim, mixed $value): string
    {
        $now   = new DateTimeImmutable();
        $token = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor('')
            ->relatedTo('user')
            ->identifiedBy('jwtId')
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim($claim, $value)
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }

    public function validateToken(string $token): bool
    {
        try {
            $parsedToken = $this->config->parser()->parse(
                $token
            );
            $this->config->validator()->assert($parsedToken, new RelatedTo('user'));
            $this->config->validator()->assert($parsedToken, new HasClaim('uuid'));

            return true;
        } catch (RequiredConstraintsViolated $e) {
            return false;
        }
        return true;
    }

    public function parseToken(string $currentToken): UnencryptedToken
    {
        try {
            return $this->config->parser()->parse($currentToken);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            throw  $e;
        }
    }
}
