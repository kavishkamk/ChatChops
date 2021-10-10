<?php
    // this class for change user first, last and usernames
    session_start();

    // session checking
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

   if(isset($_POST['profile-submit'])){
        $fname = test_input($_POST['firstname']);
        $lname = test_input($_POST['lastname']);
        $uname = test_input($_POST['username']);

        if(empty($fname) && empty($lname) && empty($uname)){
            header("Location:../profile.php?proedit=allempty");
            exit();
        }
        else{
            require_once "../phpClasses/ProfileEdit.class.php";

            $proObj = new ProfileEdit();
            $prores = $proObj->changeUserProfile($fname, $lname, $uname);
            unset($proObj);

            if($prores == "3"){
                header("Location:../profile.php?proedits=unameok");
                exit();
            }
            else if($prores == "2"){
                header("Location:../profile.php?proedit=availableuname");
                exit();
            }
            else if($prores == "0"){
                header("Location:../profile.php?proedit=sqlerr");
                exit();
            }
            else if($prores == "1"){
                header("Location:../profile.php?proedits=success");
                exit();
            }
            else if($prores == "4"){
                header("Location:../profile.php?proedit=fnamechar");
                exit();
            }
            else if($prores == "5"){
                header("Location:../profile.php?proedit=fnamenum");
                exit();
            }
            else if($prores == "6"){
                header("Location:../profile.php?proedit=lnamechar");
                exit();
            }
            else if($prores == "7"){
                header("Location:../profile.php?proedit=lnamenum");
                exit();
            }
            else if($prores == "8"){
                header("Location:../profile.php?proedit=unamechar");
                exit();
            }
            else if($prores == "9"){
                header("Location:../profile.php?proedit=unamenum");
                exit();
            }
            else{
                header("Location:../profile.php?proedit=error");
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