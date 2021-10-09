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

    if(isset($_POST['pwd-submit'])){
        $upwd = test_input($_POST['upwd']);
        $compwd = test_input($_POST['ucpwd']);
        $uid = test_input($_POST['userid']);

        if(empty($upwd) || empty($compwd) || empty($uid)){
            header("Location:../pwdChange.php?pwdtatus=emptyfield&userid=$uid");
            exit();
        }
        else{
            if($upwd == $compwd){
                require_once "../phpClasses/ProfileEdit.class.php";
                $proObj = new ProfileEdit();
                $prores = $proObj->changePassword($uid, $upwd);
                unset($proObj);
                if($prores == "1"){
                    header("Location:../profile.php?pwdedit=ok");
                    exit();
                }
                else{
                    header("Location:../profile.php?pwdedit=err");
                    exit();
                }
            }
            else{
                header("Location:../pwdChange.php?pwdtatus=wrongpwd&userid=$uid");
                exit();
            }
        }
    }
    else{
        header("Location:../profile.php");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }