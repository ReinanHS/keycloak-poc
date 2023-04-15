<?php

declare(strict_types=1);

namespace Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

/**
 * Controller responsável pela autenticação
 */
readonly class AuthController
{
    /**
     * @param Keycloak $provider
     */
    public function __construct(private Keycloak $provider)
    {
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $auth_url = $this->provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $this->provider->getState();

        return $response->withStatus(303)
            ->withHeader('Location', $auth_url);
    }
}