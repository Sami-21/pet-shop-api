<?php

namespace App\Guards;

use App\Services\JwtService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    protected Request $request;

    protected UserProvider $provider;

    protected JwtService $jwtService;

    protected ?Authenticatable $user = null;

    public function __construct(UserProvider $provider, Request $request, JwtService $jwtService)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->jwtService = $jwtService;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->getTokenForRequest();
        if (! $token) {
            return null;
        }

        $user = $this->jwtService->parseToken($token);
        if ($user) {
            $this->user = $user;
        } else {
            return null;
        }

        return $this->user;
    }

    protected function getTokenForRequest(): ?string
    {
        return $this->request->bearerToken();
    }

    /**
     * @param  array<string>  $credentials
     */
    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function check()
    {
        return $this->user() !== null;
    }

    public function guest()
    {
        return ! $this->check();
    }

    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function hasUser()
    {
        return ! is_null($this->user);
    }

    public function login(Authenticatable $user): void
    {
        $this->setUser($user);
    }

    public function logout(): void
    {
        $this->user = null;
    }

    /**
     * @param  array<string>  $credentials
     */
    public function attempt(array $credentials = [], bool $remember = false): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);

            return true;
        }

        return false;
    }
}
