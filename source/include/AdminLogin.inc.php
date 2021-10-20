<?php
    session_start();
    // this class us used to handle admin login

    if(isset($_POST['log-submit'])){
        $unameormail = test_input($_POST['unameormail']);
        $pwd = test_input($_POST['pwd']);

        if(empty($unameormail) || empty($pwd)){
            header("Location:../chatchop-org/adminlogin.php?adminlogstat=emptyfield");
            exit();
        }
        else{
            require_once "../phpClasses/AdminLoginHandle.class.php";

            $logObj = new AdminLoginHandle();
            $logresult = $logObj->checkAdminAccess($unameormail, $pwd);

            if($logresult == "0"){
                header("Location:../chatchop-org/adminlogin.php?adminlogstat=unotheride");
                unset($logObj);
                exit();
            }
            else if($logresult == "5"){
                header("Location:../chatchop-org/adminlogin.php?adminlogstat=unotheride");
                unset($logObj);
                exit();
            }
            else if($logresult == "1"){
                header("Location:../chatchop-dashboard/chatdashboardnew.php");
                unset($logObj);
                exit();
            }
            else if($logresult == "2"){
                header("Location:../chatchop-org/adminlogin.php?adminlogstat=unotheride");
                unset($logObj);
                exit();
            }
            else if($logresult == "3"){
                header("Location:../chatchop-org/adminlogin.php?adminlogstat=sqlerr");
                unset($logObj);
                exit();
            }
        }
    }
    else{
        header("Location:../chatchop-org/adminlogin.php");
        exit();
    }

    // filter inputs
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    