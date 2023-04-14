<?php

namespace Application\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write('keycloak');
        return $response;
    }
}