<?php 
    // this is for show data sumary
    session_start();

    if(!isset($_SESSION['adminid'])){
         header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok"); // no session
         exit();
    }
    else{
        require_once "../phpClasses/AdminSessionHandle.class.php";
        $sessObj = new AdminSessionHandle();
        $sessRes = $sessObj->checkSession($_SESSION['sessionId'], $_SESSION['adminid']); // invalid session
        unset($sessObj);
        if($sessRes != "1"){
            header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok"); // no session
            exit();
        }
    }

    if(isset($_POST['pwd-submit'])){
        $upwd = test_input($_POST['upwd']);

        if(empty($upwd)){
            header("Location:../chatchop-org/adminProfileChange.php?pwdedit=empty");
            exit();
        }
        else{
            require_once "../phpClasses/AdminProfileEdit.class.php";
            $proObj = new AdminProfileEdit();
            $pwdres = $proObj->CheckCurrentPwd($_SESSION['adminid'], $upwd);
            unset($proObj);

            if($pwdres == "1"){
                $uid = $_SESSION['adminid'];
                header("Location:../chatchop-org/adminPwdChange.php?pwdchange=want&userid=$uid");
                exit();
            }
            else if($pwdres == "4"){
                header("Location:../chatchop-org/adminProfileChange.php?pwdedit=wrongpwd");
                exit();
            }
            else if($pwdres == "usernotfund"){
                header("Location:../chatchop-org/adminProfileChange.php?pwdedit=nouser");
                exit();
            }
            else if($pwdres == "sqlerror" || $pwdres == "5"){
                header("Location:../chatchop-org/adminProfileChange.php?pwdedit=sqlerr");
                exit();
            }
        }
    }
    else{
        header("Location:../chatchop-org/adminProfileChange.php");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }