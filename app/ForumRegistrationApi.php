<?php

namespace App;
use GuzzleHttp\Client;

class ForumRegistrationApi
{
    var $client, $auth_email, $auth_hash, $guzzleClient, $user_data, $login_email, $login_password, $login_details;

    public function __construct($email = null, $hash = null, $login_email = null, $login_password = null) 
    {
        $this->auth_email = $email;

        $this->auth_hash = $hash;

        $this->client = new Client(
            array( 
                'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ),
                "headers" => [ 'content-type' => 'application/json'],
        ));

        $this->login_email = $login_email;

        $this->login_password = $login_password;
    }

    public function get_loggedin_user_details()
    {
        if (config('app.env') === 'local') { $app_url = "https://localhost:44315"; } 
            else if(config('app.env') === 'production'){ $app_url = "nyscengageapi.azurewebsites.net"; }

        try
        {
            $url = $app_url . "/api/Authenticate/GetRegisterDetails";
            $data = ["emailaddress" => $this->auth_email, "hash" => $this->auth_hash];   
            $response = $this->client->post($url, [ 
                "body" => json_encode( $data ) 
            ]);
            $results = json_decode((string) $response->getBody()->getContents(), true);

            $this->user_data = $results;
        }
        catch (RequestException $e){
            $response = $this->StatusCodeHandling($e);
            return $response;
        }
    }

    public function get_correct_login_credentials()
    {
        if (config('app.env') === 'local') { $app_url = "https://localhost:44315"; } 
            else if(config('app.env') === 'production'){ $app_url = "nyscengageapi.azurewebsites.net"; }

        try
        {
            $url = $app_url . "/api/Authenticate/Login";
            $data = ["emailaddress" => $this->login_email, "password" => $this->login_password];   
            $response = $this->client->post($url, [ 
                "body" => json_encode( $data ) 
            ]);
            $results = json_decode((string) $response->getBody()->getContents(), true);

            $this->login_details = $results;
        }
        catch (RequestException $e){
            $response = $this->StatusCodeHandling($e);
            return $response;
        }
    }

    public function StatusCodeHandling($e)
    {
        if ($e->getResponse()->getStatusCode() == ‘400’)
        {
            $this->get_loggedin_user_details();
        } 
        elseif ($e->getResponse()->getStatusCode() == ‘422’)
        {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } 
        elseif ($e->getResponse()->getStatusCode() == ‘500’)
        {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } 
        elseif ($e->getResponse()->getStatusCode() == ‘401’)
        {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } 
        elseif ($e->getResponse()->getStatusCode() == ‘403’)
        {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } 
        else
        {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        }
    }
}
