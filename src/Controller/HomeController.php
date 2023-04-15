<?php

declare(strict_types=1);

namespace Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controle da página inicial
 */
readonly class HomeController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write(
            json_encode([
                'message' => 'Olá Mundo'
            ])
        );

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}