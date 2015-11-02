<?php

namespace HappyR\Google\ApiBundle\Services;

class GroupsMigrationService extends \Google_Service_GroupsMigration
{
    public $client;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
        parent::__construct($client->getGoogleClient());
    }
}
