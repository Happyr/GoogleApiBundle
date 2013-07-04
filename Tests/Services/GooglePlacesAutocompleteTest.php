<?php

namespace HappyR\Google\ApiBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use HappyR\GoogleApiBundle\Services\GooglePlacesAutocomplete;

class DefaultControllerTest extends WebTestCase
{
    public function testAutocomplete()
    {
        $service=new GooglePlacesAutocomplete(array('developer_key'=>''));
        $result=$service->autocomplete("Kungsgatan");

        $this->assertNotNull($result);

    }
}
