<?php

namespace HappyR\Google\ApiBundle\Services;

use GoogleApi\Contrib\apiAnalyticsService;

/**
 * Class AnalyticsService
 *
 * This is the class that communicats with analytics api
 */
class AnalyticsService extends apiAnalyticsService{

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
    public function __construct(GoogleClient $client){
        $this->client=$client;
        parent::__construct($client->getGoogleClient());
    }


}
    