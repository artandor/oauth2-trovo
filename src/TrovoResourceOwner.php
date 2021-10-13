<?php

namespace Artandor\Oauth2Trovo;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class TrovoResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->getValueByKey($this->response, 'userId');
    }

    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'email');
    }

    public function getChannelId()
    {
        return $this->getValueByKey($this->response, 'channelId');
    }

    public function toArray()
    {
        return $this->response;
    }
}
