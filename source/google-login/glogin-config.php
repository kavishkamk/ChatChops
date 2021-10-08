<?php

/*google login configuration data*/

$login_button = "";

require '../GoogleAuth/vendor/autoload.php'; 

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
            $_SESSION['user_first_name'] = $data['given_name'];
        }

        if(!empty($data['family_name'])){
            $_SESSION['user_last_name'] = $data['family_name'];
        }

        if(!empty($data['email'])){
            $_SESSION['user_email_address'] = $data['email'];
        }

        if(!empty($data['gender'])){
            $_SESSION['user_gender'] = $data['gender'];
        }

        if(!empty($data['picture'])){
            $_SESSION['user_image'] = $data['picture'];
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