<?php
    // for reseng OTP code
    if(isset($_POST['reotp-submit'])){
        $usermail = test_input($_POST['usermail']);

        if(empty($usermail)){
            header("Location:../login.php?reotpstatus=emptyusername");
            exit();
        }
        else{
            require_once "../phpClasses/OTPResend.class.php";
            $resendObj = new OTPResend();
            $resendres = $resendObj->resendOTPWithEmail($usermail);
            // 1 = success, 
            if($resendres == "1"){
                header("Location:../otpVerify.php?reotpstatus=ok&usermail=$usermail");
            }
            else if($resendres == "2"){
                header("Location:../login.php?reotpstatus=nouser");
            }
            else if($resendres = "0"){
                header("Location:../login.php?reotpstatus=alradyactive");
            }
            else if($resendres == "3"){
                header("Location:../otpVerify.php?reotpstatus=sqlerror&usermail=$usermail");
            }
            else if($resendres == "4"){
                header("Location:../login.php?reotpstatus=emailnotfound");
            }
            else if($resendres == "5"){
                header("Location:../otpVerify.php?reotpstatus=otpsenderr&usermail=$usermail");
            }
            unset($resendObj);
            exit();
        }
    }
    else{
        header("Location:../login.php");
        exit();
    }

    // filter inputs
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }