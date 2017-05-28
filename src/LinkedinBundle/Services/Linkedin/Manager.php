<?php

namespace LinkedinBundle\Services\Linkedin;


use LinkedIn\LinkedIn;

class Manager
{
    /**
     * @var LinkedIn
     */
    private $client;

    public function __construct($apiKey, $apiSecrete, $callbackUrl)
    {
        $this->client = new LinkedIn(
            [
                'api_key'      => $apiKey,
                'api_secret'   => $apiSecrete,
                'callback_url' => $callbackUrl
            ]
        );
    }

    public function getLoginUrl()
    {
        return $this->client->getLoginUrl(
            [
                LinkedIn::SCOPE_BASIC_PROFILE,
                LinkedIn::SCOPE_EMAIL_ADDRESS,
                LinkedIn::SCOPE_NETWORK
            ]
        );
    }
}