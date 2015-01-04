<?php

namespace HappyR\Google\ApiBundle\Services;

/**
 * Class YoutubeService
 *
 * This is the class that communicates with YouTube api
 */
class YoutubeService extends \Google_Service_YouTube
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
     * Get status of a video
     * Return an array like this one:
     * array(
     *    "uploadStatus"        => "processed",
     *    "privacyStatus"       => "public",
     *    "license"             => "youtube",
     *    "embeddable"          => true,
     *    "publicStatsViewable" => true
     * )
     *
     * @param  string $videoId
     * @return array
     */
    public function getStatus($videoId)
    {
        $listResponse = $this->videos->listVideos('status', array('id' => $videoId));
        if (empty($listResponse)) {
            throw new \RuntimeException(sprintf('Could not find video with id %s', $videoId));
        }

        return $listResponse['modelData']['items'][0]['status'];
    }

    /**
     * Get thumbnails of a video
     * You can specify a format. If so, you get a single thumbnail of specified format,
     * otherwise you get an array with all five available formats
     *
     * @param  string $videoId
     * @param  string $format  "default", "medium", "high", "standard", "maxres" or null
     * @return array
     */
    public function getThumbnails($videoId, $format = null)
    {
        $listResponse = $this->videos->listVideos('snippet', array('id' => $videoId));
        if (empty($listResponse)) {
            throw new \RuntimeException(sprintf('Could not find video with id %s', $videoId));
        }
        $video = $listResponse['modelData']['items'][0];
        $videoSnippet = $video['snippet'];
        if (is_null($format)) {
            return $videoSnippet['thumbnails'];
        }
        if (!in_array($format, array('default', 'medium', 'high', 'standard', 'maxres'))) {
            throw new \InvalidArgumentException(sprintf('Invalid format "%s"', $format));
        }

        return $videoSnippet['thumbnails'][$format];
    }

    /**
     * Get related videos
     * See https://developers.google.com/youtube/v3/docs/search/list#response for returned value
     *
     * @param  string $videoId
     * @return array
     */
    public function getRelatedVideos($videoId)
    {
        $listResponse = $this->search->listSearch('snippet', array('relatedToVideoId' => $videoId, 'type' => 'video'));
        if (empty($listResponse)) {
            throw new \RuntimeException(sprintf('Could not find video with id %s', $videoId));
        }

        return $listResponse;
    }
}
