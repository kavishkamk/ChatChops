<?php

    // this class for change emial address
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

    if(isset($_POST['pwd-change-submit'])){
        $upwd = test_input($_POST['upassword']);

        if(empty($upwd)){
            header("Location:../profile.php?pwdedit=empty");
            exit();
        }
        else{
            require_once "../phpClasses/ProfileEdit.class.php";
            $proObj = new ProfileEdit();
            $pwdres = $proObj->CheckCurrentPwd($_SESSION['userid'], $upwd);
            unset($proObj);

            if($pwdres == "1"){
                $uid = $_SESSION['userid'];
                header("Location:../pwdChange.php?pwdchange=want&userid=$uid");
                exit();
            }
            else if($pwdres == "4"){
                header("Location:../profile.php?pwdedit=wrongpwd");
                exit();
            }
            else if($pwdres == "usernotfund"){
                header("Location:../profile.php?pwdedit=nouser");
                exit();
            }
            else if($pwdres == "sqlerror" || $pwdres == "5"){
                header("Location:../profile.php?pwdedit=sqlerr");
                exit();
            }
        }
    }
    else{
        header("Location:../profile.php?");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }