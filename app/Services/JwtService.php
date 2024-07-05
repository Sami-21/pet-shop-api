<?php

namespace App\Services;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\HasClaim;
use Lcobucci\JWT\Validation\Constraint\HasClaimWithValue;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

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
        $now = new DateTimeImmutable();
        $token = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->relatedTo('user')
            ->identifiedBy(config('jwt.jwt_id'))
            ->issuedAt($now)
            ->expiresAt($now->modify('+'.config('jwt.jwt_expiration').' minutes'))
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
            $constraints = [
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
                new IssuedBy(config('app.url')),
                new PermittedFor(config('app.url')),
                new IdentifiedBy(config('jwt.jwt_id')),
                new RelatedTo('user'),
                new HasClaim('uuid'),
                // new HasClaimWithValue('uuid', Auth::user()->uuid)
            ];
            $this->config->validator()->validate($parsedToken, ...$constraints);

            return true;
        } catch (RequiredConstraintsViolated $e) {
            return false;
        }
    }

    public function parseToken(string $token)
    {
        try {
            $token = $this->config->parser()->parse($token);
            assert($token instanceof UnencryptedToken);

            $constraints = [
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
            ];

            if ($this->config->validator()->validate($token, ...$constraints)) {
                $uuid = $token->claims()->get('uuid');

                return User::where('uuid', $uuid)->first();
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}
