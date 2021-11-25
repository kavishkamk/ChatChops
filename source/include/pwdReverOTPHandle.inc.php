<?php

    // check outhentication
    if(isset($_POST['otp-submit'])){
        $usermail = test_input($_POST['usermail']);
        $otpcode = test_input($_POST['verification']);
        $pwd = test_input($_POST['upwd']);
        $cpwd = test_input($_POST['ucpwd']);

        if(empty($usermail) || empty($otpcode) || empty($pwd) || empty($cpwd)){
            header("Location:../otpVerify.php?otpstatus=emptyfield&usermail=$usermail");
            exit();
        }
        else if($pwd != $cpwd){
            header("Location:../otpVerify.php?otpstatus=Wrong&usermail=$usermail");
            exit();
        }
        else{
            require_once "../phpClasses/OTP.class.php";
            $OTPob = new OTP();
            $otpCheck = $OTPob->checkOTPWithEmail($otpcode, $usermail);
            unset($OTPob);
            if($otpCheck ==  "1"){
                require_once "../phpClasses/ProfileEdit.class.php";
                $proObj = new ProfileEdit();
                $proObj -> changePwdAndActiveAcc($usermail, $pwd);
                unset($proObj);
                header("Location:../login.php?otpstatus=pwdchanged");  
            }
            else if($otpCheck ==  "0"){
                header("Location:../otpVerify.php?otpstatus=WrongOtp&usermail=$usermail");
            }
            else if($otpCheck ==  "2"){
                header("Location:../pwdRecover.php?res=usernotfound");
            }
            else if($otpCheck ==  "4"){
                header("Location:../otpVerify.php?otpstatus=sqlError&usermail=$usermail");
            }
            else if($otpCheck == "6"){
                header("Location:../pwdRecover.php?res=deleted&usermail=$usermail");
            }
            exit();
        }
    }
    else{
        header("Location:../otpVerify.php");
        exit();
    }

    // filter inputs
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }