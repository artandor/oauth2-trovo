# Trovo Provider for OAuth 2.0 Client
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![codecov](https://codecov.io/gh/artandor/oauth2-trovo/branch/main/graph/badge.svg?token=o4quAc72Bl)](https://codecov.io/gh/artandor/oauth2-trovo)

This package provides Trovo OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).
It follows https://developer.trovo.live/docs

## Installation

To install, use composer:

```
composer require artandor/oauth2-trovo
```

## Usage

Usage is the same as The League's OAuth client, using `Artandor\Oauth2Trovo\Trovo` as the provider.

### Authorization Code Flow

```php
$provider = new Artandor\Oauth2Trovo\Trovo([
    'clientId'          => '{trovo-client-id}',
    'clientSecret'      => '{trovo-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getNickname());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

## Testing

Not yet

``` bash
$ ./vendor/bin/phpunit
```

## Credits

- [Nicolas Mylle](https://github.com/artandor)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
