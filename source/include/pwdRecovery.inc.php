<?php
    if(isset($_POST['pwd-rec-submit'])){
        $umail = test_input($_POST['unameormail']);
        if(!empty($umail)){
            require_once "../phpClasses/RegisterDbHandle.class.php";
            $obj = new RegisterDbHandle();
            $emailres = $obj->isItAvailableEmail($umail, "user");
            if($emailres == "1"){
                require_once "../phpClasses/OTP.class.php";
                $otpObj = new OTP();
                $otpObj->chengeOTP($umail);
                unset($otpObj);
                header("Location:../otpVerify.php?otpsend=sendok&usermail=$umail");
            }
            else{
                header("Location:../pwdRecover.php?res=notava"); // not availabel email
            }
            unset($obj);
            exit();
        }
        else{
            header("Location:../pwdRecover.php?res=empty");
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