<?php

declare(strict_types=1);

namespace Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stevenmaguire\OAuth2\Client\Provider\KeycloakResourceOwner;

/**
 * Controle da página do usuário
 */
readonly class UserController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        /**
         * @var KeycloakResourceOwner $resource_owner
         */
        $resource_owner = $request->getAttribute('resource_owner');

        $response->getBody()->write(
            json_encode([
                'message' => 'Profile info',
                'data' => $resource_owner->toArray(),
            ])
        );

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}