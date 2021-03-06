<?php
    require_once "DbConnection.class.php";

    class OTP extends DbConnection {

        private $otpCode;
        private $username;
        private $emailStatus;
        private $usermail;
        private $deleteStatus;

        // check user reserved OTP code with genarated OTP code to activate account
        public function checkOTP($otpCode, $username){
            $this->otpCode = $otpCode;
            $this->username = $username;

            $dbOTP = $this->getOTP();

            if($dbOTP == "sqlerror"){
                return "4"; // sql error
                exit();
            }
            else if($dbOTP == "nouser"){
                return "2"; // user not found
                exit();
            }
            else if($this->emailStatus == 1){
                return "3"; // alrady activated account
                exit();
            }
            else{
                if($dbOTP == $this->otpCode){
                    $activeStatus = $this->activateAcc();
                    if($activeStatus == "sqlerror"){
                        return "5"; // sql error

                        exit();
                    }
                    else if($activeStatus == "success"){
                        return "1"; // otp verification success
                        exit();
                    }
                }
                else{
                    return "0"; // wrong otp code
                    exit();
                }
            }
        }

        // check given OTP with given user email
        public function checkOTPWithEmail($otpCode, $usermail){
            $this->otpCode = $otpCode;
            $this->usermail = $usermail;

            $dbOTP = $this->getOTPWithEmail();

            if($dbOTP == "sqlerror"){
                return "4"; // sql error
                exit();
            }
            else if($dbOTP == "nouser"){
                return "2"; // user not found
                exit();
            }
            else if($this->deleteStatus == 1){
                return "6"; // deleted account
            }
            else{
                if($dbOTP == $this->otpCode){
                    return "1"; // otp verification success
                    exit();
                }
                else{
                    return "0"; // wrong otp code
                    exit();
                }
            }
        }

        // change OTP and Deactivate account
        public function chengeOTP($umail){
            $sqlQ = "UPDATE users SET active_status = ?, otpCode = ? WHERE email = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
				$this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $val0 = 0;
                $userotp = rand(100000 , 999999); // genatate OTP code
                mysqli_stmt_bind_param($stmt, "iis", $val0, $userotp, $umail);
                mysqli_stmt_execute($stmt);
				$this->connclose($stmt, $conn);
                $this->sendOTPWithChangedMail($umail);
                return "success";
                exit();
            }
        }

        private function getOTPWithEmail(){
            $sqlQ = "SELECT active_status, otpCode, deleteStatus FROM users WHERE email  = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $this->usermail);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($result)){
                    $this->emailStatus = $row['active_status'];
                    $this->deleteStatus = $row['deleteStatus'];
					$this->connclose($stmt, $conn);
                    return $row['otpCode'];
                }
                else{
					$this->connclose($stmt, $conn);
                    return "nouser";
                    exit();
                }
            }
        }

        // get relavent details to activate account using OTP code
        private function getOTP(){
            $sqlQ = "SELECT active_status, otpCode FROM users WHERE username = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $this->username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($result)){
                    $this->emailStatus = $row['active_status'];
					$this->connclose($stmt, $conn);
                    return $row['otpCode'];
                }
                else{
					$this->connclose($stmt, $conn);
                    return "nouser";
                    exit();
                }
            }
        }

        // activate user account
        private function activateAcc(){
            $sqlQ = "UPDATE users SET active_status = ? WHERE username = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
				$this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $val = 1;
                mysqli_stmt_bind_param($stmt, "is", $val, $this->username);
                mysqli_stmt_execute($stmt);
				$this->connclose($stmt, $conn);
                return "success";
                exit();
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

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }