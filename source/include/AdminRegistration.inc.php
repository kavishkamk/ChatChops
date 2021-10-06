<?php
    // registration of admin, error handel and set paramiters in the url to get desitions
    require "../phpClasses/Register.class.php";

    if(isset($_POST['register-submit'])){
        $firstName = test_input($_POST['firstname']);
        $lastName = test_input($_POST['lastname']);
        $usermail = test_input($_POST['uemail']);
        $username = test_input($_POST['username']);
        $userpwd = test_input($_POST['upassword']);
        $comfirmPwd = test_input($_POST['confirm-password']);

        $regObj = new Register($firstName, $lastName, $usermail, $username, $userpwd, $comfirmPwd);
        $checkInput = $regObj -> checkRegInput();

        // all input values are full fill given conditions
        if($checkInput == 0){
            require_once "../phpClasses/RegisterDbHandle.class.php";

            $regHandlerObj = new RegisterDbHandle();
            $userCheck = $regHandlerObj->isItAvailableEmail($usermail, "admin");

            if($userCheck == "1"){
                header("Location:../chatchop-org/adminRegistration.php?signerror=abailableEmail&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");     
            }
            else if($userCheck == "0"){
                $unameCheck = $regHandlerObj->isItAvailableUserName($username, "admin");
                // alrady available user name
                if($unameCheck == "1"){
                    header("Location:../chatchop-org/adminRegistration.php?signerror=abailableuname&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
                }
                // this user name is not avilable in database, so ok
                else if($unameCheck == "0"){
                    $regres = $regHandlerObj->adminRegisterUser($firstName, $lastName, $usermail, $userpwd, $username);
                    if($regres == "sqlerror"){
                        header("Location:../chatchop-org/adminRegistration.php?signerror=sqlerror");
                    }
                    // register success
                    else if($regres == "Success"){
                        header("Location:../chatchop-org/adminRegistration.php?register=success");
                    }
                }
                else if($unameCheck == "sqlerror"){
                    header("Location:../chatchop-org/adminRegistration.php?signerror=sqlerror");
                }
            }
            else if($userCheck == "sqlerror"){
                header("Location:../chatchop-org/adminRegistration.php?signerror=sqlerror");
            }
            unset($regHandlerObj);
        }
        else if($checkInput == 1){
            header("Location:../chatchop-org/adminRegistration.php?signerror=emptyfield&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic"); 
        }
        else if($checkInput == 2){
            header("Location:../chatchop-org/adminRegistration.php?signerror=wrongmail&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 3){
            header("Location:../chatchop-org/adminRegistration.php?signerror=wrongfname&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 4){
            header("Location:../chatchop-org/adminRegistration.php?signerror=errlname&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 5){
            header("Location:../chatchop-org/adminRegistration.php?signerror=errusername&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 6){
            header("Location:../chatchop-org/adminRegistration.php?signerror=errpwd&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 7){
            header("Location:../chatchop-org/adminRegistration.php?signerror=fnamemax&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 8){
            header("Location:../chatchop-org/adminRegistration.php?signerror=lnamemax&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }
        else if($checkInput == 9){
            header("Location:../chatchop-org/adminRegistration.php?signerror=unamemax&firstname=$firstName&lastname=$lastName&umail=$usermail&username=$username&picn=$impic");
        }

        unset($regObj);
        exit();
    }
    else{
        header("Location:");
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }