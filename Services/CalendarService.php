<?php

namespace HappyR\Google\ApiBundle\Services;

class CalendarService extends \Google_Service_Calendar
{
    public $client;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
        parent::__construct($client->getGoogleClient());
    }
}