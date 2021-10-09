<?php
    // this class for edit users profile datas
    require_once "DbConnection.class.php";
    class ProfileEdit extends DbConnection {

        public function changeUserProfile($fname, $lname, $uname){
            $res = 0;
            if(!empty($fname)){
                $fRes = $this->changeFirstname($fname, $_SESSION['userid']);
                if($fRes == "1"){
                    $_SESSION['fname'] = $fname;
                    $res = "1";
                }
                else{
                    $res = "0";
                }
            }
            if(!empty($lname)){
                $LRes = $this->changeLastname($lname, $_SESSION['userid']);
                if($LRes == "1"){
                    $_SESSION['lname'] = $lname;
                    $res = "1";
                }
                else{
                    $res = "0";
                }
            }
            if(!empty($uname)){
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

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }