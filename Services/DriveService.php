<?php

namespace HappyR\Google\ApiBundle\Services;

/**
 * Class DriveService
 *
 * This is the class that communicates with Drive api
 */
class DriveService extends \Google_Service_Drive
{
    /**
     * @var GoogleClient client
     */
    public $client;

    /**
     * Constructor
     * @param GoogleClient $client
     */
    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
        parent::__construct($client->getGoogleClient());
    }

    /**
     * @param $presentationOriginId
     * @param $name
     * @return \Google_Service_Drive_DriveFile
     */
    public function copy($presentationOriginId, $name)
    {
        return $this->files->copy(
            $presentationOriginId,
            new \Google_Service_Drive_DriveFile(['name' => $name])
        );
    }

    /**
     * @param $fileId
     * @param $type
     * @param $role
     * @param $value
     * @return \Google_Service_Drive_Permission
     */
    public function addPermission($fileId, $type, $role, $value)
    {
        $permissions = ['type' => $type, 'role' => $role];

        switch ($type) {
            case 'domain':
                $permissions['domain'] = $value;
                break;
            case 'user':
            default:
                $permissions['emailAddress'] = $value;
        }

        return $this->permissions->create(
            $fileId,
            new \Google_Service_Drive_Permission($permissions),
            array('fields' => 'id')
        );
    }
}
