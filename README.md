# Laravel JWT Auth Guard

[![Total Downloads](https://poser.pugx.org/lukevear/jwt-auth-guard/d/total.svg)](https://packagist.org/packages/lukevear/jwt-auth-guard)
[![Latest Stable Version](https://poser.pugx.org/lukevear/jwt-auth-guard/v/stable.svg)](https://packagist.org/packages/lukevear/jwt-auth-guard)
[![Latest Unstable Version](https://poser.pugx.org/lukevear/jwt-auth-guard/v/unstable.svg)](https://packagist.org/packages/lukevear/jwt-auth-guard)
[![License](https://poser.pugx.org/lukevear/jwt-auth-guard/license.svg)](https://packagist.org/packages/lukevear/jwt-auth-guard)

This package provides an authentication guard for the `tymon/jwt-auth` package (< 1.0.0) when using Laravel or Lumen 5.3.
 
> NOTE: The `tymon/jwt-auth` package as of 1.0.* supplies its own guard.

## Installation

Before you can use this package, you must configure the jwt-auth package according to the project [WIKI](https://github.com/tymondesigns/jwt-auth/wiki/Installation).

Once you have installed and configured the jtw-auth package, add `lukevear/jwt-auth-guard` to your `composer.json`:

```json
"lukevear/jwt-auth-guard": "1.*"
```
    
Then you can run `composer update` to grab the latest and greatest version.

Alternatively, you may install this package directly from the command line:

```shell
composer require "lukevear/jwt-auth-guard:1.*"
```

You'll need need to activate the service provider, which you can do in `config/app.php`:

```php
'providers' => [
    ...
    LukeVear\JWTAuthGuard\JWTAuthGuardServiceProvider::class,
]
```

Finally, configure the authentication guard in `config/auth.php`:

```php
'guards' => [
    ...
    
    'api' => [
        'driver'   => 'jwt-auth',
        ...
    ],
],
```

## What's next?
Enjoy JWT based token authentication in your Laravel/Lumen 5.3 application!