<?php

namespace App;
use GuzzleHttp\Client;

class BlobApi
{
    var $client;
    var $blobImage;
    var $imageUrl;
    var $remoteImageId;
    var $deleteResponse;

    public function __construct($blobImage = null, $remoteImageId = null) 
    {
        $this->blobImage = $blobImage;
        $this->remoteImageId = $remoteImageId;

        $this->client = new Client(
            array( 
                'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ),
                "headers" => [ 'content-type' => 'application/json'],
        ));

    }

    public function get_image_url()
    {
        try
        {
            $url = "https://nyscengageapi.azurewebsites.net/api/partners/imageUpload";
            $data = ["base64" => $this->blobImage]; 

            $response = $this->client->post($url, [ 
                "body" => json_encode( $data ) 
            ]);

            $results = json_decode((string) $response->getBody()->getContents(), true);

            $this->imageUrl = $results;
        }
        catch (RequestException $e){
            return $e;
        }
    }

    public function delete_image_url()
    {
        try
        {
            $url = "https://nyscengageapi.azurewebsites.net/api/partners/imageDelete";
            $data = ["imageId" => $this->remoteImageId]; 

            $response = $this->client->post($url, [ 
                "body" => json_encode( $data ) 
            ]);

            $results = json_decode((string) $response->getBody()->getContents(), true);

            $this->deleteResponse = $results;
        }
        catch (RequestException $e){
            return $e;
        }
    }
}
