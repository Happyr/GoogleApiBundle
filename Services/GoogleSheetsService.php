<?php

namespace HappyR\Google\ApiBundle\Services;

use REverse\GSheets\Client as GoogleSheetsClient;
use REverse\GSheets\SpreadSheets;

class GoogleSheetsService
{
    /**
     * @var GoogleSheetsClient
     */
    private $googleSheetsClient;

    /**
     * @var Spreadsheets
     */
    private $spreadsheets;

    /**
     * GoogleSheetsService constructor.
     * @param GoogleClient $googleClient
     */
    public function __construct(GoogleClient $googleClient)
    {
        $this->googleSheetsClient = new GoogleSheetsClient($googleClient->getGoogleClient());
    }

    public function setSpreadsheets($spreadsheetsId)
    {
        if (empty($spreadsheetsId)) {
            throw new \InvalidArgumentException(sprintf('$spreadsheetsId must not be empty'));
        }

        $this->spreadsheets = new SpreadSheets($this->googleSheetsClient, $spreadsheetsId);
    }

    public function hasSpreadsheet()
    {
        return $this->spreadsheets !== null;
    }

    public function writeRow($value, $sheet, $row, $optParams = [])
    {
        $this->spreadsheets->writeRow($value, $sheet, $row, $optParams);
    }


    public function clearRow($sheet, $row)
    {
        $this->spreadsheets->clearRow($sheet, $row);
    }

    /**
     * @return GoogleSheetsClient
     */
    public function getGoogleSheetsClient()
    {
        return $this->googleSheetsClient;
    }

    /**
     * @return Spreadsheets
     */
    public function getSpreadsheets()
    {
        return $this->spreadsheets;
    }
}
