<?php
    // registration script and error handel and set paramiters in the url to get desitions
    require "../phpClasses/Register.class.php";

    if(isset($_POST['register-submit'])){

        $firstName = test_input($_POST['firstname']);
        $lastName = test_input($_POST['lastname']);
        $usermail = test_input($_POST['uemail']);
        $username = test_input($_POST['username']);
        $userpwd = test_input($_POST['upassword']);
        $comfirmPwd = test_input($_POST['confirm-password']);
        $impic = test_input($_POST['foo']);

        $regObj = new Register($firstName, $lastName, $usermail, $username, $userpwd, $comfirmPwd);

        $checkInput = $regObj -> checkRegInput();

        // all input values are full fill given conditions
        if($checkInput == 0){

            require_once "../phpClasses/RegisterDbHandle.class.php";

            $regHandlerObj = new RegisterDbHandle();
            $userCheck = $regHandlerObj->isItAvailableEmail($usermail, "user");

            if($userCheck == "1"){
                header("Location:../registration.php?signerror=abailableEmail&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");     
            }
            else if($userCheck == "0"){
                $unameCheck = $regHandlerObj->isItAvailableUserName($username, "user");
                // alrady available user name
                if($unameCheck == "1"){
                    header("Location:../registration.php?signerror=abailableuname&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
                }
                // this user name is not avilable in database, so ok
                else if($unameCheck == "0"){
                    $regres = $regHandlerObj->registerUser($firstName, $lastName, $usermail, $userpwd, $username, $impic);

                    if($regres == "sqlerror"){
                        header("Location:../registration.php?signerror=sqlerror");
                    }
                    // register success
                    else if($regres == "Success"){
                        require_once "../phpClasses/MailHandle.class.php";

                        $mailObj = new MailHandle();
                        $sendotpResult = $mailObj->sendOTP($usermail);

                        // OTP send succes // redirect page to get OTP code
                        if($sendotpResult == "SENDOTP"){
                            header("Location:../otpcode.php?otpsend=sendok&username=$username");   
                        }
                        // OTP send with errors
                        else if($sendotpResult == "sqlerror"){
                            header("Location:../login.php?signerror=sqlerror");
                        }
                        else if($sendotpResult == "noemail"){
                            header("Location:../login.php?signerror=notaregistermail&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
                        }
                        else if($sendotpResult == "OTPSENDERROR"){
                            header("Location:../login.php?signerror=otpsenderror");
                        }

                        unset($mailObj);
                    }
                }
                else if($unameCheck == "sqlerror"){
                    header("Location:../registration.php?signerror=sqlerror");
                }
            }
            else if($userCheck == "sqlerror"){
                header("Location:../registration.php?signerror=sqlerror");
            }
            unset($regHandlerObj);
        }
        // user inputs not compleate given conditions
        else if($checkInput == 1){
            header("Location:../registration.php?signerror=emptyfield&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic"); 
        }
        else if($checkInput == 2){
            header("Location:../registration.php?signerror=wrongmail&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 3){
            header("Location:../registration.php?signerror=wrongfname&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 4){
            header("Location:../registration.php?signerror=errlname&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 5){
            header("Location:../registration.php?signerror=errusername&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 6){
            header("Location:../registration.php?signerror=errpwd&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 7){
            header("Location:../registration.php?signerror=fnamemax&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 8){
            header("Location:../registration.php?signerror=lnamemax&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 9){
            header("Location:../registration.php?signerror=unamemax&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }

        unset($regObj);
        exit();

    }
    else{
        header("Location:../registration.php");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
