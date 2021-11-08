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

    if(isset($_POST['del-submit'])){
        if($_POST['delstat'] == "okDelete"){
            require_once "../phpClasses/DeleteAcc.class.php";
            $obj = new DeleteAcc();
            $delres = $obj->deleteAdminAcc($_SESSION['adminid']);
            if($delres == "1"){
                header("Location:../chatchop-org/adminlogin.php?otpstatus=deleteacc");
                unset($delObj);
                exit();
            }
            else{
                header("Location:../chatchop-org/adminlogin.php?logstat=sqlerror");
                unset($delObj);
                exit();
            }
        }
        else{
            header("Location:../chatchop-org/adminProfileChange.php");
            exit();
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