<?php

    //delete account
    require_once "DbConnection.class.php";
    class DeleteAcc extends DbConnection{

        // check otp with DB OTP
        public function checkOTP($otpcode, $uid){
            $sqlQ = "SELECT otpCode FROM users WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $uid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($result)){
                    $dbotp = $row['otpCode'];
                    if($dbotp == $otpcode){
                        return "1"; // success
                    }
                    else{
                        return "0"; // wrong
                    }
                }
                else{
                    return "nouser"; // user not found
                    exit();
                }
            }
        }

        // delete account
        public function deleteAcc($uid){
            $sqlQ = "UPDATE users SET active_status = ?, deleteStatus = ?, onlineStatus = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                exit();
            }
            else{
                $val = 1; $val1 = 0;
                mysqli_stmt_bind_param($stmt, "iiis", $val1, $val, $val1, $uid);
                mysqli_stmt_execute($stmt);
                return "1"; // success
                exit();
            }
        }

        // delete account
        public function deleteAdminAcc($uid){
            $sqlQ = "UPDATE admins SET actSTatus = ?, online_status = ? WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                exit();
            }
            else{
                $val = 1; $val1 = 0;
                mysqli_stmt_bind_param($stmt, "iii", $val1, $val1, $uid);
                mysqli_stmt_execute($stmt);
                return "1"; // success
                exit();
            }
        }

    }