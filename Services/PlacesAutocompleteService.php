<?php

namespace HappyR\Google\ApiBundle\Services;

/**
 * Class PlacesAutocompleteService
 *
 * This service helps you autocomplete with google places
 */
class PlacesAutocompleteService
{
    protected $baseUrl='https://maps.googleapis.com/maps/api/place/autocomplete/json?sensor=false';


    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->baseUrl.='&key='.$config['developer_key'];
    }

    /**
     * Autocomplete the location
     *
     *
     * @param $location
     *
     * @return string the response of a request to google
     */
    public function autocomplete($location)
    {
        $url=$this->baseUrl.'&input='.urlencode($location);
        $response=file_get_contents($url);

        $response=json_decode($response);

        if($response->status!='OK')
            return $location;

        return $response->predictions[0]->description;

    }
}
    