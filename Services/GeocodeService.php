<?php

namespace HappyR\Google\ApiBundle\Services;

/**
 * Class GeocodeService
 *
 * This service geocodes an address to coords and back
 */
class GeocodeService
{
    protected $baseUrl='http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

    /**
     * Geocode an address.
     *
     * @param string $text
     * @param bool $raw
     *
     * @return string|null the response of a request to google
     */
    public function geocodeAddress($text, $raw=false)
    {
        $response=@file_get_contents($this->baseUrl.urlencode($text));

        $response=json_decode($response);

        if($response->status!='OK'){
            return null;
        }

        if($raw){
            return $response->results;
        }

        return $response->results[0]->geometry->location;

    }

    /**
     * Returns address from a coordinates
     *
     * @param float $lat
     * @param float $lang
     * @param bool $raw
     *
     * @return string|null
     */
    public function reverseGeocodeAddress($lat,$lang,$raw=false)
    {
        $response=@file_get_contents($this->baseUrl.urlencode('latlng='.$lat.','.$lang));

        $response=json_decode($response);

        if($response->status!='OK'){
            return null;
        }

        if($raw){
            return $response->results;
        }

        return $response->results[0]->formatted_address;

    }

}
