<?php

namespace HappyR\Google\ApiBundle\Services;

/**
 * Class GeocodeService
 *
 * This service geocodes an address to coords and back
 */
class GeocodeService{
    protected $baseUrl='http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

    /**
     * geocode an address.
     *
     * Returns the response of a request to google
     */
    public function geocodeAddress($text, $raw=false){
        $response=@file_get_contents($this->baseUrl.urlencode($text));

        $response=json_decode($response);

        if($response->status!='OK')
            return null;

        if($raw)
            return $response->results;

        return $response->results[0]->geometry->location;

    }


    /**
     * Returns address from a coordinates
     */
    public function reverseGeocodeAddress($lat,$lang,$raw=false){
        $response=@file_get_contents($this->baseUrl.urlencode('latlng='.$lat.','.$lang));

        $response=json_decode($response);

        if($response->status!='OK')
            return null;

        if($raw)
            return $response->results;

        return $response->results[0]->formatted_address;


    }


}
    