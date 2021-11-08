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

    if(isset($_POST['email-submit'])){
        $umail = test_input($_POST['umeil']);

        if(empty($umail)){
            header("Location:../chatchop-org/adminProfileChange.php?mailedit=empty");
            exit();
        }
        else if(!filter_var($umail, FILTER_VALIDATE_EMAIL)){
            header("Location:../chatchop-org/adminProfileChange.php?mailedit=invalid");
            exit();
        }
        else{
            // check user mail is availabale or not
            require_once "../phpClasses/RegisterDbHandle.class.php";
            $regObj = new RegisterDbHandle();
            $regRes = $regObj->isItAvailableEmail($umail, "admin");
            unset($regObj);

            if($regRes == "0"){
                require_once "../phpClasses/AdminProfileEdit.class.php";

                $proObj = new AdminProfileEdit();
                $prores = $proObj->changeUserMail($umail, $_SESSION['adminid']);
                unset($proObj);

                if($prores == "1"){
                    $uname = $_SESSION['adminuname'];
                    header("Location:../chatchop-org/adminProfileChange.php?mailedit=s");
                    exit();
                }
                else{
                    header("Location:../chatchop-org/adminProfileChange.php");
                    exit();
                }
            }
            else if($regRes == "1"){
                header("Location:../chatchop-org/adminProfileChange.php?mailedit=avilablemail");
                exit();
            }
            else if($regRes == "sqlerror"){
                header("Location:../chatchop-org/adminProfileChange.php?mailedit=sqlerr");
                exit();
            }
        }
    }
    else{
        header("Location:../chatchop-org/adminProfileChange.php?");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }