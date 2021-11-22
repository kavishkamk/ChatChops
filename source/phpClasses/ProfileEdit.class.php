<?php
    // this class for edit users profile datas
    require_once "DbConnection.class.php";
    class ProfileEdit extends DbConnection {

        // change first name, last name, user name
        public function changeUserProfile($fname, $lname, $uname){
            $res = 0;
            if(!empty($fname)){
                if(!preg_match("/^[a-zA-Z]*$/", $fname)){
                    return "4"; // invalid characters
                    exit();
                }
                else if(strlen($fname) > 30){
                    return "5"; // characters shoud be <30
                    exit();
                }
                else{
                    $fRes = $this->changeFirstname($fname, $_SESSION['userid']);
                    if($fRes == "1"){
                        $_SESSION['fname'] = $fname;
                        $res = "1"; //success
                    }
                    else{
                        $res = "0"; // error
                    }
                }
            }
            if(!empty($lname)){
                if(!preg_match("/^[a-zA-Z]*$/", $lname)){
                    return "6"; // invalid characters
                    exit();
                }
                else if(strlen($lname) > 30){
                    return "7"; // characters shoud be <30
                    exit();
                }
                else{
                    $LRes = $this->changeLastname($lname, $_SESSION['userid']);
                    if($LRes == "1"){
                        $_SESSION['lname'] = $lname;
                        $res = "1"; // success
                    }
                    else{
                        $res = "0"; // error
                    }
                }
            }
            if(!empty($uname)){
                if(!preg_match("/^[a-zA-Z0-9]*$/", $uname)){
                    return "8"; // invalid characters
                    exit();
                }
                else if(strlen($uname) > 50){
                    return "9"; // characters shoud be 50>
                    exit();
                }
                else{
                    require_once "RegisterDbHandle.class.php";
                    $regObj = new RegisterDbHandle();
                    $unameRes = $regObj->isItAvailableUserName($uname, "user");
                    unset($regObj);
                    if($unameRes == "sqlerror"){
                        $res = "0"; // sql error
                    }
                    else if($unameRes == "1"){
                        $res = "2";
                    }
                    else if($unameRes == "0"){
                        $uRes = $this->changeUsername($uname);
                        if($uRes == "1"){
                            $preUserName = $_SESSION['uname'];
                            $_SESSION['uname'] = $uname;
                            $this->chageProfilePhotoNames($uname, $preUserName);
                            $res = "3";
                        }
                        else{
                            $res = "0";
                        }
                    }
                }
            }

            return $res;
            exit();
        }

        // change user first name
        private function changeFirstname($fname){
            $sqlQ = "UPDATE users SET first_name = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $fname, $_SESSION['userid']);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // chage user last name
        private function changeLastname($lname){
            $sqlQ = "UPDATE users SET last_name = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $lname, $_SESSION['userid']);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // change user username
        private function changeUsername($uname){
            $sqlQ = "UPDATE users SET username = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $uname, $_SESSION['userid']);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // change profile picture names with new username
        private function chageProfilePhotoNames($username, $preUname){
            if($_SESSION['profileLink'] !=  "unknownPerson.jpg"){
                $this->changeProfileLink("$username.png", $_SESSION['userid']);
                $_SESSION['profileLink'] =  "$username.png";
                if(file_exists("../profile-pic/$preUname.png")){
                    rename("../profile-pic/$preUname.png" ,"../profile-pic/$username.png");
                }
            }
        }

        // change profile picture link in database
        public function changeProfileLink($proLink, $userid){
            $sqlQ = "UPDATE users SET profilePicLink = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $proLink, $userid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // this function can use to change the mail.
        // but befor it you should enshure that email is not a availabale in the database
        public function changeUserMail($mail, $uid){
            $sqlQ = "UPDATE users SET email = ?, active_status = ?, otpCode = ?, onlineStatus = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $val = 0;
                $userotp = rand(100000 , 999999); // genatate OTP code
                mysqli_stmt_bind_param($stmt, "siiii", $mail, $val, $userotp, $val, $uid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);

                $sendres = $this->sendOTPWithChangedMail($mail);

                if($sendres == "SENDOTP"){
                    return "1";
                    exit();
                }
                else if($sendres == "sqlerror"){
                    return "3";
                    exit();
                }
                else if($sendres == "noemail"){
                    return "4";
                    exit();
                }
                else if($sendres = "OTPSENDERROR"){
                    return "5";
                }
            }
        }

        // send otp to delete account
        public function deleteAccOTP($uid, $mail){
            $sqlQ = "UPDATE users SET otpCode = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $userotp = rand(100000 , 999999); // genatate OTP code
                mysqli_stmt_bind_param($stmt, "ii", $userotp, $uid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);

                $sendres = $this->sendOTPWithChangedMail($mail);

                if($sendres == "SENDOTP"){
                    return "1";
                    exit();
                }
                else if($sendres == "sqlerror"){
                    return "3";
                    exit();
                }
                else if($sendres == "noemail"){
                    return "4";
                    exit();
                }
                else if($sendres = "OTPSENDERROR"){
                    return "5";
                }
            }
        }

        // send updated OTP with email
        private function sendOTPWithChangedMail($mail){
            require_once "MailHandle.class.php";

            $mailObj = new MailHandle();
            $sendres = $mailObj->sendOTP($mail);
            return $sendres;
            unset($mailObj);
            exit();
        }

        // check user password
        public function CheckCurrentPwd($uid, $pwd){
            $sqlQ = "SELECT pwd FROM users WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                $this->connclose($stmt, $conn);
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $uid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $pwdCheck = password_verify($pwd, $row['pwd']); // check password
                    if($pwdCheck == false){
                        return "4";
                        $this->connclose($stmt, $conn);
                        exit(); // wrong password
                    }
                    else if($pwdCheck == true){
                        return "1";
                        $this->connclose($stmt, $conn);
                        exit(); // ok
                    }
                    else{
                        return "5";
                        $this->connclose($stmt, $conn);
                        exit(); // something was wrang
                    }
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "usernotfund";
                    exit();
                }
            }
        }

        // change user password
        public function changePassword($uid, $pwd){
            $sqlQ = "UPDATE users SET pwd = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $hashpwd = password_hash($pwd, PASSWORD_DEFAULT); // hashing password
                mysqli_stmt_bind_param($stmt, "si", $hashpwd, $uid);
                mysqli_stmt_execute($stmt);
                return "1"; // sql error
                exit();
            }
        }

        public function changePwdAndActiveAcc($email, $pwd){
            $sqlQ = "UPDATE users SET pwd = ?, active_status = ? WHERE email = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $val1 = 1;
                $hashpwd = password_hash($pwd, PASSWORD_DEFAULT); // hashing password
                mysqli_stmt_bind_param($stmt, "sis", $hashpwd, $val1, $email);
                mysqli_stmt_execute($stmt);
                return "1"; // sql error
                exit();
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }