<?php

declare(strict_types=1);

namespace Application\Middleware;

use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

/**
 * Middleware para validação da autenticação
 */
class AuthMiddleware
{
    /**
     * @param Keycloak $provider
     */
    public function __construct(private Keycloak $provider)
    {
    }


    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = new Response();
        $regex = '/^Bearer ((?:\.?(?:[A-Za-z0-9-_]+)){3})$/m';
        $access_token = $request->getHeader('authorization')[0] ?? '';

        if (!$request->hasHeader('authorization') || !preg_match_all($regex, $access_token, $matches, PREG_SET_ORDER)) {
            return $this->errorValidToken($response);
        }

        $access_token = str_replace('Bearer', '', $access_token);
        $token = new AccessToken(['access_token' => $access_token]);

        try {
            $resource_owner = $this->provider->getResourceOwner($token);
            $request = $request->withAttribute('resource_owner', $resource_owner);

            return $handler->handle($request);
        } catch (Exception) {
            return $this->errorValidToken($response);
        }
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function errorValidToken(Response $response): Response
    {
        $response->getBody()->write(json_encode(['message' => 'Invalid authorization token']));

        return $response->withStatus(403)
            ->withHeader('Content-Type', 'application/json');
    }
}