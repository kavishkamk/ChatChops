<?php

/*google signup configuration data*/

$login_button = "";

//root = /htdocs/
require $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/GoogleAuth/vendor/autoload.php'; 

$clientID = '98629541381-h09adtlbp8nru45h09if0i8b7m9l3qd0.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/chatchops/source/google-login/logged-in.php';

//creating client request for google
$client = new Google_Client();
$client -> setClientId($clientID);
$client -> setClientSecret($clientSecret);
$client -> setRedirectUri($redirectUri);

//getting name and email
$client-> addScope('profile');
$client-> addScope('email');

session_start();

if(isset($_GET['code']))
{
	$token = $client-> fetchAccessTokenWithAuthCode($_GET['code']);
	$client->setAccessToken($token);
	
    //check there is any error occur during geting authentication token.
    if(!isset($token['error']))
    {
        //Set the access token used for requests
        $client->setAccessToken($token['access_token']);

        //Store "access_token" value in $_SESSION variable for future use.
        $_SESSION['access_token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($client);

        //Get user profile data from google
        $data = $google_service->userinfo->get();

        //Get profile data and store into $_SESSION variable
        if(!empty($data['given_name'])){
            $_SESSION['fname'] = $data['given_name'];
        }

        if(!empty($data['family_name'])){
            $_SESSION['lname'] = $data['family_name'];
        }

        if(!empty($data['email'])){
            $_SESSION['email'] = $data['email'];
        }

    }

    if(!isset($_SESSION['access_token'])){
        //Create a URL to obtain user authorization
        $login_button = $client->createAuthUrl();
    }

}
else{

}



?>