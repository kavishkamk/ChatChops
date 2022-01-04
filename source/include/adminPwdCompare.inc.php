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
        $compwd = test_input($_POST['ucpwd']);
        $uid = test_input($_POST['userid']);

        if(empty($upwd) || empty($compwd) || empty($uid)){
            header("Location:../chatchop-org/adminPwdChange.php?pwdtatus=emptyfield&userid=$uid");
            exit();
        }
        else{
            if($upwd == $compwd){
                require_once "../phpClasses/adminProfileEdit.class.php";
                $proObj = new AdminProfileEdit();
                $prores = $proObj->changePassword($uid, $upwd);
                unset($proObj);
                if($prores == "1"){
                    header("Location:../chatchop-org/adminProfileChange.php?pwdedit=ok");
                    exit();
                }
                else{
                    header("Location:../chatchop-org/adminProfileChange.php?pwdedit=err");
                    exit();
                }
            }
            else{
                header("Location:../chatchop-org/adminPwdChange.php?pwdtatus=wrongpwd&userid=$uid");
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
