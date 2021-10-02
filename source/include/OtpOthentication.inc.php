<?php

    // check outhentication
    if(isset($_POST['otp-submit'])){
        $username = test_input($_POST['username']);
        $otpcode = test_input($_POST['verification']);

        if(empty($username) || empty($otpcode)){
            header("Location:../otpcode.php?otpstatus=emptyfield&username=$username");
            exit();
        }
        else{
            require_once "../phpClasses/OTP.class.php";
            $OTPob = new OTP();
            $otpCheck = $OTPob->checkOTP($otpcode, $username);

            if($otpCheck ==  "1"){
                header("Location:../login.php?otpstatus=otpverified");
                unset($OTPob);
                exit();
            }
            else if($otpCheck ==  "0"){
                header("Location:../otpcode.php?otpstatus=WrongOtp&username=$username");
                unset($OTPob);
                exit();
            }
            else if($otpCheck ==  "3"){
                header("Location:../login.php?otpstatus=alradyactived");
                unset($OTPob);
                exit();
            }
            else if($otpCheck ==  "2"){
                header("Location:../login.php?otpstatus=usernotfound");
                unset($OTPob);
                exit();
            }
            else if($otpCheck ==  "4"){
                header("Location:../login.php?otpstatus=sqlError");
                unset($OTPob);
                exit();
            }
            else if($otpCheck ==  "5"){
                header("Location:../login.php?otpstatus=sqlError1");
                unset($OTPob);
                exit();
            }
        }
    }
    else{
        header("Location:../otpcode.php");
        exit();
    }

    // filter inputs
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }