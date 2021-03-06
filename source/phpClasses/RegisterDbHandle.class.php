<?php
require_once "DbConnection.class.php";
   
   class RegisterDbHandle extends DbConnection {

        // to comfirm, given email is not in the database
        public function isItAvailableEmail($mail, $utype){
            if($utype == "user"){
                $sqlQ = "SELECT user_id FROM users WHERE email = ?;";
            }
            else if($utype == "admin"){
                $sqlQ = "SELECT admin_id FROM admins WHERE email = ?;";
            }
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "s", $mail);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultcheck = mysqli_stmt_num_rows($stmt);
                if($resultcheck == 0){
                    $this->connclose($stmt, $conn);
                    return "0"; // not a available in this moment
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "1"; // alrady have
                    exit();
                }
            }
        }

        // check, given user name is not available in the database
        public function isItAvailableUserName($uname, $utype){
            if($utype == "user"){
                $sqlQ = "SELECT user_id FROM users WHERE username = ?;";
            }
            else if($utype == "admin"){
                $sqlQ = "SELECT admin_id FROM admins WHERE username = ?;";
            }
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "s", $uname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultcheck = mysqli_stmt_num_rows($stmt);
                if($resultcheck == 0){
                    $this->connclose($stmt, $conn);
                    return "0"; // ok to use
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "1"; // user name available
                    exit();
                }
            }
        }

        // register user
        /**$google var added to check whether the registration type is manual or google authentication */
        public function registerUser($fname, $lname, $mail, $uPwd, $uname, $propic, $google){
            $sqlQ = "INSERT INTO users(first_name, last_name, email, pwd, last_seen, username, profilePicLink, created_time, otpCode) VALUES(?,?,?,?,?,?,?,?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $hashedPwd = password_hash($uPwd, PASSWORD_DEFAULT); // hashing password
                //rename user profile emage with username if user upload image
                if($propic != "unknownPerson.jpg" && $google == "0"){
                    rename("../profile-pic/$propic","../profile-pic/$uname.png");
                    $propic = "$uname.png";
                }
                $createTime = date("Y-n-d H:i:s"); // acout cration date and time
                $userotp = rand(100000 , 999999); // genatate OTP code
                mysqli_stmt_bind_param($stmt, "ssssssssi", $fname, $lname, $mail, $hashedPwd, $createTime, $uname, $propic , $createTime, $userotp);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();

            }
        }

        // register user
        public function adminRegisterUser($fname, $lname, $mail, $uPwd, $uname){
            $sqlQ = "INSERT INTO admins(fname, lname, email, pwd, lastSeenDT, username, actSTatus) VALUES(?,?,?,?,?,?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $hashedPwd = password_hash($uPwd, PASSWORD_DEFAULT); // hashing password
                $createTime = date("Y-n-d H:i:s"); // acout cration date and time
                $val = 1;
                mysqli_stmt_bind_param($stmt, "ssssssi", $fname, $lname, $mail, $hashedPwd, $createTime, $uname, $val);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();

            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
   }