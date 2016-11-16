<?php

namespace LukeVear\JWTAuthGuard;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
//use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthGuard implements Guard
{
    use GuardHelpers;

    /**
     * The user we last attempted to retrieve.
     * 
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

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
        $this->jwt      = $jwt;
        $this->provider = $provider;
        $this->request  = $request;
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
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param  array  $credentials
     * @param  bool  $login
     *
     * @return bool|string
     */
    public function attempt(array $credentials = [], $login = true)
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);
        if ($this->hasValidCredentials($user, $credentials)) {
            return $login ? $this->login($user) : true;
        }
        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Create a token for a user.
     *
     * @param   $user
     *
     * @return string
     */
    public function login($user)
    {
        $this->setUser($user);
        return $this->jwt->fromUser($user);
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return $this->attempt($credentials, false);
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

    /**
     * Log a user into the application using their credentials.
     *
     * @param  array  $credentials
     *
     * @return bool
     */
    public function once(array $credentials = [])
    {
        if ($this->validate($credentials)) {
            $this->setUser($this->lastAttempted);
            return true;
        }
        return false;
    }
    
     /**
     * Log the given User into the application.
     *
     * @param  mixed  $id
     *
     * @return bool
     */
    public function onceUsingId($id)
    {
        if ($user = $this->provider->retrieveById($id)) {
            $this->setUser($user);
            return true;
        }
        return false;
    }
    
    /**
     * Alias for onceUsingId.
     *
     * @param  mixed  $id
     *
     * @return bool
     */
    public function byId($id)
    {
        return $this->onceUsingId($id);
    }
}
