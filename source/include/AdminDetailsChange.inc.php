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

    if(isset($_POST['profile-submit'])){
        $fname = test_input($_POST['fname']);
        $lname = test_input($_POST['lname']);
        $uname = test_input($_POST['uname']);

        if(empty($fname) && empty($lname) && empty($uname)){
            header("Location:../chatchop-org/adminProfileChange.php?proedit=allempty");
            exit();
        }
        else{
            require_once "../phpClasses/AdminProfileEdit.class.php";

            $proObj = new AdminProfileEdit();
            $prores = $proObj->changeUserProfile($fname, $lname, $uname);
            unset($proObj);

            if($prores == "3"){
                header("Location:../chatchop-org/adminProfileChange.php?proedits=unameok");
                exit();
            }
            else if($prores == "2"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=availableuname");
                exit();
            }
            else if($prores == "0"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=sqlerr");
                exit();
            }
            else if($prores == "1"){
                header("Location:../chatchop-org/adminProfileChange.php?proedits=success");
                exit();
            }
            else if($prores == "4"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=fnamechar");
                exit();
            }
            else if($prores == "5"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=fnamenum");
                exit();
            }
            else if($prores == "6"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=lnamechar");
                exit();
            }
            else if($prores == "7"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=lnamenum");
                exit();
            }
            else if($prores == "8"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=unamechar");
                exit();
            }
            else if($prores == "9"){
                header("Location:../chatchop-org/adminProfileChange.php?proedit=unamenum");
                exit();
            }
            else{
                header("Location:../chatchop-org/adminProfileChange.php?proedit=error");
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