# SocialAuth

Socialite wrapper that handles all the remaining social authentication boilerplate, exposing endpoints ready for use that enable multiple providers at once for any model(s) you need.

## Installation

1- Require via Composer

``` bash
$ composer require rzb/socialauth
```

2- Run auto-published migrations

``` bash
$ php artisan migrate
```

3- Add the contracts and traits to the User model, or to whatever model(s) you need

``` php
<?php

namespace App\Models\User;

use Rzb\SocialAuth\Contracts\Resourceable as ResourceableContract;
use Rzb\SocialAuth\Contracts\Sociable as SociableContract;
use Rzb\SocialAuth\Traits\Resourceable;
use Rzb\SocialAuth\Traits\Sociable;
// ...

class User extends AuthUser implements SociableContract, ResourceableContract
{
    use Resourceable;
    use Sociable;
    // ...
}
```

4- (optional) If you need to authenticate any model(s) other than the User, add/remove providers or tweak the exposed Routes, publish the config file

``` bash
$ php artisan vendor:publish --provider="Rzb\SocialAuth\SocialAuthServiceProvider" --tag="config"
```

## Usage

For most projects, you won't have to do anything at all after installation. The package automatically exposes the endpoints and enables Google, Facebook and Twitter providers for the User model.

If however you have different needs, you're free to tweak the configuration, use your own controller or even implement your own DB logic.

## Credits

- [rzb][link-author]
- [All Contributors][link-contributors]

[ico-version]: https://img.shields.io/packagist/v/rzb/socialauth.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rzb/socialauth.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rzb/socialauth/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/rzb/socialauth
[link-downloads]: https://packagist.org/packages/rzb/socialauth
[link-travis]: https://travis-ci.org/rzb/socialauth
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/rzb
[link-contributors]: ../../contributors
