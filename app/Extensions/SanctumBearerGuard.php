<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumBearerGuard implements Guard
{
    protected $request;
    protected $provider;
    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $authHeader = $this->request->header('Authorization');

        if ($authHeader && preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            $plainToken = $matches[1]; // "5|abc123..."

            if (strpos($plainToken, '|') !== false) {
                [$id, $token] = explode('|', $plainToken, 2);

                $accessToken = PersonalAccessToken::find($id);

                if ($accessToken && hash_equals($accessToken->token, hash('sha256', $token))) {
                    return $this->user = $accessToken->tokenable;
                }
            }
        }

        return null;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->user() ? $this->user()->getAuthIdentifier() : null;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }
}
