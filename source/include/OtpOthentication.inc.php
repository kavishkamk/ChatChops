<?php

    if(isset($_POST['otp-submit'])){
        $username = $_POST['username'];
        $otpcode = $_POST['verification'];

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