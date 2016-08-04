<?php

namespace HappyR\Google\ApiBundle\Services;

use Psr\Log\LoggerInterface;

/**
 * Class GoogleClient
 *
 * This is the google client that is used by almost every api
 */
class GoogleClient
{
    /**
     * @var \Google_Client client
     */
    protected $client;

    /**
     * @param array $config
     */
    public function __construct(array $config, LoggerInterface $symfonyLogger = null)
    {
        // True if objects should be returned by the service classes.
        // False if associative arrays should be returned (default behavior).
        $config['use_objects'] = true;

        $client = new \Google_Client($config);
        if ($symfonyLogger) {
            //BC for Google API 1.0
            if (class_exists('\Google_Logger_Psr')) {
                $googleLogger = new \Google_Logger_Psr($client, $symfonyLogger);
                $client->setLogger($googleLogger);
            } else {
                $client->setLogger($symfonyLogger);
            }
        }

        $client->setApplicationName($config['application_name']);
        $client->setClientId($config['oauth2_client_id']);

        switch ($config['type']) {
            case 'web':
                $client->setClientSecret($config['oauth2_client_secret']);
                $client->setRedirectUri($config['oauth2_redirect_uri']);
                $client->setDeveloperKey($config['developer_key']);
                break;

            case 'service':
                $client->setAccessType('offline');

                if (isset($config['getenv']) && true === $config['getenv']) {
                    $client->useApplicationDefaultCredentials();

                } else if (isset($config['json_file'])) {
                    $client->setAuthConfigFile($config['json_file']);

                } else if (class_exists('\Google_Auth_AssertionCredentials')) {
                    //BC for Google API 1.0
                    $client->setAssertionCredentials(
                        new \Google_Auth_AssertionCredentials(
                            $config['oauth2_client_email'],
                            $config['oauth2_scopes'],
                            $config['oauth2_private_key']
                        )
                    );
                } else {
                    $client->setScopes($config['oauth2_scopes']);
                    $client->setAuthConfig(
                        array(
                            'type'         => 'service_account',
                            'client_id'    => $config['oauth2_client_id'],
                            'client_email' => $config['oauth2_client_email'],
                            'private_key'  => $config['oauth2_private_key'],
                        )
                    );
                }
                break;
        }

        $this->client = $client;
    }

    /**
     * @return \Google_Client
     */
    public function getGoogleClient()
    {
        return $this->client;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this -> client -> setAccessToken($accessToken);
    }

    /**
     * @param string|null $code
     */
    public function authenticate($code = null)
    {
        $this->client->authenticate($code);
    }

    /**
     * Construct the OAuth 2.0 authorization request URI.
     * @return string
     */
    public function createAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Get the OAuth 2.0 access token.
     * @return string $accessToken JSON encoded string in the following format:
     * {"access_token":"TOKEN", "refresh_token":"TOKEN", "token_type":"Bearer",
     *  "expires_in":3600,"id_token":"TOKEN", "created":1320790426}
     */
    public function getAccessToken()
    {
        return $this->client->getAccessToken();
    }

    /**
     * Returns if the access_token is expired.
     * @return bool Returns True if the access_token is expired.
     */
    public function isAccessTokenExpired()
    {
        return $this->client->isAccessTokenExpired();
    }
}
