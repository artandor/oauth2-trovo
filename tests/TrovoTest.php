<?php

namespace Artandor\Oauth2Trovo\Test;

use Artandor\Oauth2Trovo\Trovo;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;

class BrimeTest extends TestCase
{
    private Trovo $provider;

    public function tearDown(): void
    {
        parent::tearDown();
    }

    protected function setUp(): void
    {
        $this->provider = new Trovo([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none'
        ]);
    }

    public function testGetResourceOwnerDetailsUrl()
    {
        $token = new AccessToken(['access_token' => 'mock_access_token']);
        $url = $this->provider->getResourceOwnerDetailsUrl($token);
        $uri = parse_url($url);
        $this->assertEquals(Trovo::USER_RESOURCE, $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals(Trovo::OAUTH_TOKEN_PATH, $uri['path']);
    }

    public function testGetBaseAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals(Trovo::AUTHORIZE_PATH, $uri['path']);
    }
}