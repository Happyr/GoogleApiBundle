<?php

namespace HappyR\Google\ApiBundle\Services;

use GoogleApi\Contrib\AnalyticsService as baseAnalyticsService;

/**
 * Class AnalyticsService
 *
 * This is the class that communicates with analytics api
 */
class AnalyticsService extends baseAnalyticsService
{
    /**
     * @var GoogleClient client
     *
     *
     */
    public $client;

    /**
     * Constructor
     * @param GoogleClient $client
     */
    public function __construct(GoogleClient $client)
    {
        $this->client=$client;
        parent::__construct($client->getGoogleClient());
    }

}
