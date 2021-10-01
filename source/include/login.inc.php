<?php
    if(isset($_POST['log-submit'])){
        
        $unameormail = $_POST['unameormail'];
        $pwd = $_POST['pwd'];

        if(empty($unameormail) || empty($pwd)){
            header("Location:../login.php?logstat=emptyfield&username=$unameormail");
            exit();
        }
        else{
            require_once "../phpClasses/LoginHandle.class.php";

            $logObj = new LoginHandle();
            $logresult = $logObj->checkUserwithPasswerd($unameormail, $pwd);

            if($logresult == "0"){
                header("Location:../login.php?logstat=noacc&username=$unameormail");
                unset($logObj);
                exit();
            }
            else if($logresult == "5"){
                header("Location:../login.php?logstat=wrongpwd&username=$unameormail");
                unset($logObj);
                exit();
            }
            if($logresult == "1"){
                header("Location:../chatChops.php");
                unset($logObj);
                exit();
            }
            else if($logresult == "2"){
                header("Location:../login.php?logstat=deletedacc&username=$unameormail");
                unset($logObj);
                exit();
            }
            else if($logresult[0] == "4"){
                $uname = substr($logresult, 1, strlen($logresult) - 1);
                header("Location:../otpcode.php?logstat=notactived&username=$uname");
                unset($logObj);
                exit();
            }
            else if($logresult == "3"){
                header("Location:../login.php?logstat=sqlerror&username=$unameormail");
                unset($logObj);
                exit();
            }
            else if($logresult == "6"){
                header("Location:../login.php?logstat=activefail&username=$unameormail");
                unset($logObj);
                exit();
            }
        }

    }
    else{
        header("Location:../login.php");
        exit();
    }