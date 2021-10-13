<?php

namespace Artandor\Oauth2Trovo;

use Exception;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Trovo extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public const USER_RESOURCE = '/openplatform/getuserinfo';
    public const OAUTH_TOKEN_PATH = '/openplatform/exchangetoken';
    public const AUTHORIZE_PATH = '/page/login.html';

    private $basicUrl = 'https://open-api.trovo.live';

    public function getBaseAuthorizationUrl(): string
    {
        return "https://open.trovo.live" . self::AUTHORIZE_PATH;
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->basicUrl . self::OAUTH_TOKEN_PATH;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->basicUrl . self::USER_RESOURCE;
    }

    protected function getDefaultScopes(): array
    {
        return ['user_details_self'];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            dump($response->getBody(), $response->getStatusCode());
            throw new Exception($response->getBody());
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): TrovoResourceOwner
    {
        return new TrovoResourceOwner($response);
    }
}
