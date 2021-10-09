<?php 
    require_once "DbConnection.class.php";

    // this class used to genarate new OTP and update database
    class OTPResend extends DbConnection {

        private $username;
        private $emaistatus;
        private $usermail;

        // this method used to resend OTP code
        public function resendOTP($username){
            $this->username = $username;

            $checkuser = $this->checkuser();

            if($checkuser == "sqlerror"){
                return "3";
                exit();
            }
            else if($checkuser == "nouser"){
                return "2"; // user not found
                exit();
            }
            else if($checkuser == "ok"){
                $otpChande = $this->genarateAndchageOTPcode();
                if($this->emailstatus == "1"){
                    return "0";
                }
                else if($otpChande == "success"){
                    $sendres = $this->sendMailWithChangedMail();
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
                else if($otpChande == "sqlerror"){
                    return "3";
                    exit();
                }
            }
        }

        // check user is alrady register or not 
        private function checkuser(){
            $sqlQ = "SELECT active_status, email FROM users WHERE username = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $this->username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($result)){
                    $this->emaistatus = $row['active_status'];
                    $this->usermail = $row['email'];
                    $this->connclose($stmt, $conn);
                    return "ok"; // user found
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "nouser"; // no user match
                    exit();
                }
            }
        }

        // genarate and change new OTP code for user accout activation
        private function genarateAndchageOTPcode(){
            $sqlQ = "UPDATE users SET otpCode = ? WHERE username = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $userotp = rand(100000 , 999999); // genatate OTP code
                mysqli_stmt_bind_param($stmt, "is", $userotp, $this->username);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "success";
                exit();
            }
        }

        // send updated OTP with email
        private function sendMailWithChangedMail(){
            require_once "MailHandle.class.php";

            $mailObj = new MailHandle();
            $sendres = $mailObj->sendOTP($this->usermail);
            return $sendres;
            unset($mailObj);
            exit();
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }