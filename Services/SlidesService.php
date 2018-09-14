<?php
namespace HappyR\Google\ApiBundle\Services;

/**
 * Class SlidesService
 *
 * This is the class that communicates with Slides api
 */
class SlidesService extends \Google_Service_Slides
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
     * Get a Presentation
     *
     * @param string $presentationId
     * @return \Google_Service_Slides_Presentation
     */
    public function getPresentation($presentationId)
    {
        return $this->presentations->get($presentationId);
    }

    /**
     * Get the presentation URL
     *
     * @param string $presentationId
     * @return string
     */
    public function getUrl($presentationId)
    {
        return sprintf('https://docs.google.com/presentation/d/%s/edit', $presentationId);
    }

    /**
     * UpdateTextStyleRequest
     *
     * @param $objectId
     * @param null $fontFamily
     * @param null $fontSize
     * @param null $foregroundColor
     * @return \Google_Service_Slides_Request
     */
    public function generateUpdateTextStyleRequest(
        $objectId,
        $fontFamily = null,
        $fontSize = null,
        $foregroundColor = null
    ) {
        $fields = [];
        $params = [
            'objectId' => $objectId,
            'textRange' => ['type' => 'ALL'],
        ];

        if ($fontFamily) {
            $params['style']['fontFamily'] = $fontFamily;
            $fields = ['fontFamily'];
        }
        if ($fontSize) {
            $params['style']['fontSize'] = $fontSize;
            $fields = ['fontSize'];
        }
        if ($foregroundColor) {
            $params['style']['foregroundColor'] = $foregroundColor;
            $fields = ['foregroundColor'];
        }
        if ($fields) {
            $params['fields'] = implode(',', $fields);
        }
        return new \Google_Service_Slides_Request(['updateTextStyle' => $params]);
    }

    /**
     * @param $objectId
     * @param $color
     * @return \Google_Service_Slides_Request
     */
    public function generateUpdateShapePropertiesRequest($objectId, $color)
    {
        return new \Google_Service_Slides_Request(
            [
                'updateShapeProperties' => [
                    'objectId' => $objectId,
                    "fields" => "shapeBackgroundFill.solidFill.color",
                    "shapeProperties" => [
                        "shapeBackgroundFill" => [
                            "solidFill" => [
                                "color" => $color
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * @param $placeholder
     * @param $text
     * @param $pageObjectIds
     * @return \Google_Service_Slides_Request
     */
    public function generateReplaceAllTextRequest($placeholder, $text, $pageObjectIds = null)
    {
        $params = [
            'containsText' => ['text' => $placeholder],
            'replaceText' => sprintf('%s', $text)
        ];
        if ($pageObjectIds) {
            $params['pageObjectIds'] = $pageObjectIds;
        }
        return new \Google_Service_Slides_Request(['replaceAllText' => $params]);
    }

    /**
     * @param $originSlideObjectId
     * @param $destinationSlideObjectId
     * @return \Google_Service_Slides_Request
     */
    public function generateDuplicateSlideRequest($originSlideObjectId, $destinationSlideObjectId)
    {
        return new \Google_Service_Slides_Request(
            [
                'duplicateObject' => [
                    'objectId' => $originSlideObjectId,
                    "objectIds" => [
                        $originSlideObjectId => $destinationSlideObjectId
                    ]
                ]
            ]
        );
    }

    /**
     * @param $objectId
     * @return \Google_Service_Slides_Request
     */
    public function generateDeleteObjectRequest($objectId)
    {
        return new \Google_Service_Slides_Request(
            [
                'deleteObject' => [
                    'objectId' => $objectId
                ]
            ]
        );
    }

    /**
     * @param $objectId
     * @param $applyMode
     * @param $scaleX
     * @param $scaleY
     * @param $shearX
     * @param $shearY
     * @param $translateX
     * @param $translateY
     * @return \Google_Service_Slides_Request
     */
    public function generateUpdatePageElementTransformRequest(
        $objectId,
        $applyMode,
        $scaleX,
        $scaleY,
        $shearX,
        $shearY,
        $translateX,
        $translateY
    ) {
        return new \Google_Service_Slides_Request(
            [
                "updatePageElementTransform" => [
                    "objectId" => $objectId,
                    "applyMode" => $applyMode,
                    "transform" => [
                        "scaleX" => $scaleX,
                        "scaleY" => $scaleY,
                        "shearX" => $shearX,
                        "shearY" => $shearY,
                        "translateX" => $translateX,
                        "translateY" => $translateY,
                        "unit" => "EMU"
                    ]
                ]
            ]
        );
    }

    /**
     * @param \Google_Service_Slides_Request[] $requests
     * @param $presentationId
     * @return \Google_Service_Slides_BatchUpdatePresentationResponse
     */
    public function batchUpdate(array $requests, $presentationId)
    {
        $batchUpdatePresentationRequest = new \Google_Service_Slides_BatchUpdatePresentationRequest(
            ['requests' => $requests]
        );
        return $this->presentations->batchUpdate($presentationId, $batchUpdatePresentationRequest);
    }
}
