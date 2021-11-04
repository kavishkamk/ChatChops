<?php
    // this class for edit admin profile datas
    require_once "DbConnection.class.php";
    class AdminProfileEdit extends DbConnection {

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
                    $fRes = $this->changeFirstname($fname, $_SESSION['adminid']);
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
                    $LRes = $this->changeLastname($lname, $_SESSION['adminid']);
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
                    $unameRes = $regObj->isItAvailableUserName($uname, "admin");
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
                            $_SESSION['adminuname'] = $uname;
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

        // change admin first name
        private function changeFirstname($fname){
            $sqlQ = "UPDATE admins SET fname = ? WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $fname, $_SESSION['adminid']);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // chage admin last name
        private function changeLastname($lname){
            $sqlQ = "UPDATE admins SET lname = ? WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $lname, $_SESSION['adminid']);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // change admin username
        private function changeUsername($uname){
            $sqlQ = "UPDATE admins SET username = ? WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $uname, $_SESSION['adminid']);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

         // this function can use to change the mail.
        // but befor it you should enshure that email is not a availabale in the database
        public function changeUserMail($mail, $uid){
            $sqlQ = "UPDATE admins SET email = ?, actSTatus = ?, online_status	 = ? WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $val = 1; $val0 = 0;
                mysqli_stmt_bind_param($stmt, "siii", $mail, $val, $val0, $uid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }
        }

        // check user password
        public function CheckCurrentPwd($uid, $pwd){
            $sqlQ = "SELECT pwd FROM admins WHERE admin_id = ?;";
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
            $sqlQ = "UPDATE admins SET pwd = ? WHERE admin_id = ?;";
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

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }