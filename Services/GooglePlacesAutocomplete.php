<?php

namespace HappyR\Google\ApiBundle\Services;

class GooglePlacesAutocomplete{
    protected $baseUrl='https://maps.googleapis.com/maps/api/place/autocomplete/json?sensor=false';
	
	
	public function __construct($config){
		$this->baseUrl.='&key='.$config['developer_key'];
	}
   
    /**
     * Autocomplete the location
     * 
     * Returns the response of a request to google
     */
    public function autocomplete($location){
    	$url=$this->baseUrl.'&input='.urlencode($location);
        $response=file_get_contents($url);

        $response=json_decode($response);

        if($response->status!='OK')
            return $location;		
        
        return $response->predictions[0]->description;
        
    }
}
    