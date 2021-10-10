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

    if(isset($_POST['delstat'])){
        if(isset($_POST['delstat']) == "okDelete"){
            require_once "../phpClasses/ProfileEdit.class.php";
            $proObj = new ProfileEdit();
            $prores = $proObj->deleteAccOTP($_SESSION['userid'], $_SESSION['umail']);
            unset($mailObj);
            if($prores == "1"){
                $userid = $_SESSION['userid'];
                header("Location:../accDeleteCode.php?delete=ok&uid=$userid"); // send mail
                exit();
            }
            else{
                header("Location:../profile.php?logout=err"); // error
                exit();
            }
        }
    }
    else{
        header("Location:../profile.php");
        exit();
    }