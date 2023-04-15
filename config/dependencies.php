<?php

declare(strict_types=1);

use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

return [
    Keycloak::class => function () {
        return new Keycloak([
            'authServerUrl' => getenv('AUTH_SERVER_URL'),
            'realm' => getenv('REALM'),
            'clientId' => getenv('CLIENT_ID'),
            'clientSecret' => getenv('CLIENT_SECRET'),
            'redirectUri' => getenv('REDIRECT_URI'),
        ]);
    },
];