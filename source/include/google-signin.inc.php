<?php
require "../google-login/glogin-config.php";
require "../phpClasses/Register.class.php";

if(isset($_POST['pswd-submit']))
{
    //user data
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $email = $_SESSION['email'];
    $pic   = $_SESSION['picture'];

    $pswd = $_POST['pswd'];
    $confpswd = $_POST['confirm-pswd'];

    //set the username from email address
    $username = substr($email, 0, strpos($email, '@'));

    $registerObj = new Register($fname, $lname, $email, $username, $pswd, $confpswd);
    $checkInput = $registerObj->checkRegInput();

    if($checkInput == 0){
        require_once "../phpClasses/RegisterDbHandle.class.php";

        $regHandlerObj = new RegisterDbHandle();
        $userCheck = $regHandlerObj->isItAvailableEmail($email);

        if($userCheck == "1"){
            header("Location:../registration.php?signerror=abailableEmail&firstname=$fname&lastname=$lname&umail=$email&username=$username&picn=$pic");     
        }
        else if($userCheck == "0"){
            
            //check whether the username available or not
            //if its available, generate a random number and add that at the end of the username
            //and check again and again in the while loop
            //until it generate a unique username
            while(true)
            {
                $unameCheck = $regHandlerObj->isItAvailableUserName($username);
                // already available user name
                if($unameCheck == "1"){
                    $random = rand(100, 999);
                    $username = $username.$random;
                }else{
                    $unameCheck = "0";
                    break;
                }
            }

            if($unameCheck == "0"){
                $regres = $regHandlerObj->registerUser($fname, $lname, $email, $pswd, $username, $pic, "1");

                if($regres == "sqlerror"){
                    header("Location:../registration.php?signerror=sqlerror");
                }
            }

            /*****************code the rest comparing register.inc.php**************** */

        }
    }    

}

?>