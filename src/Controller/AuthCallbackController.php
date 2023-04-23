<?php

declare(strict_types=1);

namespace Application\Controller;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

/**
 * Controller responsável por receber as informações de autenticação do Keycloak
 */
readonly class AuthCallbackController
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
        $params = $request->getQueryParams();

        if (array_diff(['state', 'session_state', 'code'], array_keys($params))) {
            $response->getBody()->write(json_encode(['message' => 'Invalid parameter']));
            return $response->withStatus(200);
        }

        $auth_data = $this->getAuthToken($params['state'], $params['session_state'], $params['code']);
        $response->getBody()->write(json_encode($auth_data));

        return $response->withStatus($auth_data['status'])
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param string $stage
     * @param string $session_state
     * @param string $code
     * @return array
     */
    private function getAuthToken(string $stage, string $session_state, string $code): array
    {
        $token = null;

        // Tente obter um token de acesso (usando a concessão do código de autorização)
        try {
            $token = $this->provider->getAccessToken('authorization_code', ['code' => $code]);
        } catch (Exception $exception) {
            return [
                'status' => 500,
                'message' => 'Failed to get access token: ' . $exception->getMessage(),
            ];
        }

        // Opcional: agora que você tem um token, pode pesquisar os dados do perfil de um usuário
        try {
            // We got an access token, let's now get the user's details
            $user_data = $this->provider->getResourceOwner($token);
        } catch (Exception $exception) {
            return [
                'status' => 500,
                'message' => 'Failed to get resource owner: ' . $exception->getMessage(),
            ];
        }

        return [
            'status' => 200,
            'user_data' => $user_data->toArray(),
            'token' => $token->getToken(),
            'token_expires' => $token->getExpires(),
        ];
    }
}
