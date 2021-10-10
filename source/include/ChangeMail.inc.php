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

    if(isset($_POST['email-change-submit'])){
        $umail = test_input($_POST['uemail']);

        if(empty($umail)){
            header("Location:../profile.php?mailedit=empty");
            exit();
        }
        else if(!filter_var($umail, FILTER_VALIDATE_EMAIL)){
            header("Location:../profile.php?mailedit=invalid");
            exit();
        }
        else{
            // check user mail is availabale or not
            require_once "../phpClasses/RegisterDbHandle.class.php";
            $regObj = new RegisterDbHandle();
            $regRes = $regObj->isItAvailableEmail($umail, "user");
            unset($regObj);

            if($regRes == "0"){
                require_once "../phpClasses/ProfileEdit.class.php";

                $proObj = new ProfileEdit();
                $prores = $proObj->changeUserMail($umail, $_SESSION['userid']);
                unset($proObj);

                if($prores == "1"){
                    $uname = $_SESSION['uname'];
                    header("Location:../otpcode.php?otpsend=sendok&username=$uname");
                    exit();
                }
                else{
                    header("Location:../login.php?otpstatus=sqlError");
                    exit();
                }
            }
            else if($regRes == "1"){
                header("Location:../profile.php?mailedit=avilablemail");
                exit();
            }
            else if($regRes == "sqlerror"){
                header("Location:../profile.php?mailedit=sqlerr");
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