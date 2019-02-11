<?php

namespace Services;

use HappyR\Google\ApiBundle\Services\GoogleClient;
use PHPUnit\Framework\TestCase;

class GoogleClientTest extends TestCase
{
    public function testInConstructorIfAuthConfigFilePathIsEmptyTheAssociatedMethodMustNotBeCalled()
    {
        $config = [
            'auth_config_file_path' => null,
            'application_name' => null,
            'oauth2_client_id' => null,
            'oauth2_client_secret' => null,
            'oauth2_redirect_uri' => null,
            'developer_key' => null,
        ];

        new GoogleClient($config);

        $this->assertTrue(true);
    }

    public function testInConstructorIfAuthConfigFilePathIsFillTheAssociatedMethodMustThrowNotExistingFileMethod()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $config = [
            'auth_config_file_path' => 'foofile.json',
            'application_name' => null,
            'oauth2_client_id' => null,
            'oauth2_client_secret' => null,
            'oauth2_redirect_uri' => null,
            'developer_key' => null,
        ];

        new GoogleClient($config);

        $this->assertTrue(true);
    }
}
