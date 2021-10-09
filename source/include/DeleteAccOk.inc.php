<?php

    session_start();

    // sesssion checking
    if(!isset($_SESSION['userid'])){
        header("Location:../login.php?logout=logoutok"); // no session
        exit();
    }
    else{
        require_once "../phpClasses/SessionHandle.class.php";
        $sessObj = new SessionHandle();
        $sessRes = $sessObj->checkSession($_SESSION['sessionId'], $_SESSION['userid']); // invalid session
        unset($sessObj);
        if($sessRes != "1"){
            header("Location:../login.php?logout=logoutok"); // no session
            exit();
        }
    }

    if(isset($_POST['delete-submit'])){
        $otpcode = test_input($_POST['verification']);
        $uid = test_input($_POST['uid']);

        if(!(empty($otpcode) || empty($uid))){
            require_once "../phpClasses/DeleteAcc.class.php";
            $delObj = new DeleteAcc();
            $delres = $delObj->checkOTP($otpcode, $uid);
            if($delres == "1"){
                $delres = $delObj->deleteAcc($uid);
                if($delres == "1"){
                    header("Location:../login.php?otpstatus=deleteacc");
                    unset($delObj);
                    exit();
                }
                else{
                    header("Location:../login.php?logstat=sqlerror");
                    unset($delObj);
                    exit();
                }
            }
            else{
                header("Location:../accDeleteCode.php?otpstatus=WrongOtp&uid=$uid");
                unset($delObj);
                exit();
            }
        }
        else{
            header("Location:../accDeleteCode.php?otpstatus=emptyfield&uid=$uid");
            exit();
        }
    }
    else{
        header("Location:../accDeleteCode.php?");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }