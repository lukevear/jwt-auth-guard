<?php

namespace LukeVear\JWTAuthGuard;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class AuthGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var JWTAuth
     */
    protected $jwt;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * AuthGuard constructor.
     * @param JWTAuth $jwt
     * @param UserProvider $provider
     * @param Request $request
     */
    public function __construct(JWTAuth $jwt, UserProvider $provider, Request $request)
    {
        $this->jwt = $jwt;
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        /*
         * If we have already retrieved the user for the current request we can
         * just return it back immediately.
         */
        if (! is_null($this->user)) {
            return $this->user;
        }

        // Attempt to retrieve the token from the request
        $token = $this->jwt->getToken();
        if (! $token) {
            return null;
        }

        // Get the user associated with the token
        try {
            $user = $this->jwt->toUser($token);
        } catch (JWTException $e) {
            return null;
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }

        return false;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}