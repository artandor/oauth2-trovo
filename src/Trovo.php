<?php

namespace Artandor\Oauth2Trovo;

use Exception;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\RequestInterface;
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
        return 'https://open.trovo.live'.self::AUTHORIZE_PATH;
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->basicUrl.self::OAUTH_TOKEN_PATH;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->basicUrl.self::USER_RESOURCE;
    }

    protected function getDefaultScopes(): array
    {
        return ['user_details_self'];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new Exception($response->getBody());
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): TrovoResourceOwner
    {
        return new TrovoResourceOwner($response);
    }

    /**
     * Returns the string that should be used to separate scopes when building the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return '+';
    }

    /**
     * Returns authorization headers for the 'bearer' grant.
     *
     * @param AccessTokenInterface|string|null $token Either a string or an access token instance
     *
     * @return array
     */
    protected function getAuthorizationHeaders($token = null)
    {
        return [
            'Authorization' => 'OAuth '.$token,
        ];
    }

    /**
     * Returns the default headers used by this provider.
     *
     * Typically, this is used to set 'Accept' or 'Content-Type' headers.
     *
     * @return array
     */
    protected function getDefaultHeaders()
    {
        return [
            'client-id' => $_ENV['OAUTH_TROVO_CLIENT_ID'],
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params Query string parameters
     *
     * @return RequestInterface
     */
    protected function getAccessTokenRequest(array $params)
    {
        $method = $this->getAccessTokenMethod();
        $url = $this->getAccessTokenUrl($params);
        $options = $this->optionProvider->getAccessTokenOptions($this->getAccessTokenMethod(), $params);
        $options['body'] = json_encode($params);

        return $this->getRequest($method, $url, $options);
    }
}
