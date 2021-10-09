<?php
    // this class for login handle
    require_once "DbConnection.class.php";

    class LoginHandle extends DbConnection {

        private $username;
        private $userpwd;
        private $user_id;
        private $fname;
        private $lname;
        private $dbpwd;
        private $activeStatus;
        private $deleteStatus;
        private $registerdUName;
        private $logInsertId;
        private $profilelink;
        private $umail;
        
        public function checkUserwithPasswerd($username, $pwd){
            $this->username = $username;
            $this->userpwd = $pwd;

            $getdetails = $this->getReleventDetais();

            if($getdetails == "ok"){
                // check blocked accounts
                if($this->deleteStatus == 1){
                    return "2"; // account is deleted
                    exit();
                }
                else{
                    if($this->activeStatus == 1){
                        $pwdCheck = password_verify($this->userpwd, $this->dbpwd); // check password
                        if($pwdCheck == false){
                            return "5"; // wrong password
                            exit();
                        }
                        else if($pwdCheck == true){

                            require_once "OnlineOffline.class.php";

                            $onlineObj = new OnlineOffline();
                            $onlineRes = $onlineObj->setUserOnline($this->user_id);
                            $this->setLoginRecords();
                            $this->setMappingTable();
                            if($onlineRes == "1"){
                                session_unset();
                                session_destroy();
                                session_start();
                                require_once "SessionHandle.class.php";
                                $sesObj = new SessionHandle();
                                $sessionVal = session_id(); // genarete session id
                                $sesResult = $sesObj->setSession($this->user_id, $sessionVal);
                                unset($sesObj);

                                if($sesResult == "1"){
                                
                                    $_SESSION['userid'] = $this->user_id; // set user id of the user table
                                    $_SESSION['onlineRecordid'] = $this->logInsertId; // set with record id to set offline time
                                    $_SESSION['sessionId'] = $sessionVal; // set with record id to set offline time
                                    $_SESSION['fname'] = $this->fname;
                                    $_SESSION['lname'] = $this->lname;
                                    $_SESSION['profileLink'] = $this->profilelink;
                                    $_SESSION['uname'] = $this->registerdUName;
                                    $_SESSION['umail'] = $this->umail;
                                    return "1"; // login success
                                }
                                else{
                                    return "3"; // sql error
                                }
                            }
                            else{
                                return "3"; // sql error
                                exit();
                            }
                            unset($onlineObj);
                            exit();
                        }
                        else{
                            return "5";
                            exit(); // something was wrang
                        }
                    }
                    else{
                        // actount created, but still deactivated
                        require_once "../phpClasses/OTPResend.class.php";
                        $resendObj = new OTPResend();
                        $resendres = $resendObj->resendOTP($this->registerdUName);

                        if($resendres == "1"){
                            return "4$this->registerdUName"; // OTP code resend successfully
                        }
                        else if($resendres == "2"){
                            return "0"; // user not found
                        }
                        else if($resendres == "3"){
                            return "3"; // sql error
                        }
                        else if($resendres == "5"){
                            return "6"; // OTPsenderror
                        }
                        unset($resendObj);
                        exit();
                    }
                }
            }
            else if($getdetails == "usernotfund"){
                return "0"; // user not found
                exit();
            }
            else if($getdetails == "sqlerror"){
                return "3"; // sql error
                exit();
            }
        }

        private function getReleventDetais(){
            $sqlQ = "SELECT user_id, first_name, last_name, email, pwd, username, active_status, profilePicLink, deleteStatus FROM users WHERE username=? OR email=?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ss", $this->username, $this->username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->user_id = $row['user_id'];
                    $this->fname = $row['first_name'];
                    $this->lname = $row['last_name'];
                    $this->dbpwd = $row['pwd'];
                    $this->activeStatus = $row['active_status'];
                    $this->profilelink = $row['profilePicLink'];
                    $this->deleteStatus = $row['deleteStatus'];
                    $this->registerdUName = $row['username'];
                    $this->umail = $row['email'];
                    $this->connclose($stmt, $conn);
                    return "ok";
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "usernotfund";
                    exit();
                }
            }
        }

        // when user login insert that data to the relavent table
        private function setLoginRecords(){
            $sqlQ = "INSERT INTO user_active_time(online_date_and_time, offline_date_and_time) VALUES(?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $onlieTime = date("Y-n-d H:i:s"); // acout log date and time
                mysqli_stmt_bind_param($stmt, "ss", $onlieTime, $onlieTime);
                mysqli_stmt_execute($stmt);
                $this->logInsertId = mysqli_stmt_insert_id($stmt);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }
        }

        // update mapping table
        private function setMappingTable(){
            $sqlQ = "INSERT INTO user_user_act_id_map(user_id, active_id) VALUES(?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ss", $this->user_id, $this->logInsertId);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }

        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }