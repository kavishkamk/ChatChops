<?php
    require_once "DbConnection.class.php";

    // this class use for check admin with passwords and create session for admin
    class AdminLoginHandle extends DbConnection{

        private $adminId;
        private $fname;
        private $lname;
        private $username;
        private $email;
        private $pwd;
        private $activeStatus;
        private $inputpwd;
        private $inputuname;

        // check admin user name, password and create session if admin details are correct
        public function checkAdminAccess($uname, $upwd){
            $this->inputpwd = $upwd;
            $this->inputuname = $uname;

            $getdetails = $this->getReleventDetais();

            if($getdetails == "ok"){
                // check blocked accounts
                if($this->activeStatus == 0){
                    return "2"; // account is deleted
                    exit();
                }
                else{
                    $pwdCheck = password_verify($this->inputpwd, $this->pwd); // check password
                    if($pwdCheck == false){
                        return "5"; // wrong password
                        exit();
                    }
                    else if($pwdCheck == true){

                        require_once "AdminOnlineOffline.class.php";

                        $onlineObj = new AdminOnlineOffline();
                        $onlineRes = $onlineObj->setAdminOnlie($this->adminId);

                        if($onlineRes == "1"){
                            session_unset();
                            session_destroy();
                            session_start();
                            require_once "AdminSessionHandle.class.php";
                            $sesObj = new  AdminSessionHandle();
                            $sessionVal = session_id(); // genarete session id
                            $sesResult = $sesObj->setSession($this->adminId, $sessionVal);
                            unset($sesObj);

                            if($sesResult == "1"){
                                
                                $_SESSION['adminid'] = $this->adminId; // set user id of the user table
                                $_SESSION['sessionId'] = $sessionVal; // set with record id to set offline time
                                $_SESSION['fname'] = $this->fname;
                                $_SESSION['lname'] = $this->lname;
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

        // get admin details using admin username or password from DB
        private function getReleventDetais(){
            $sqlQ = "SELECT admin_id, fname, lname, email, pwd, username, actSTatus FROM admins WHERE username=? OR email=?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ss", $this->inputuname, $this->inputuname);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->adminId = $row['admin_id'];
                    $this->fname = $row['fname'];
                    $this->lname = $row['lname'];
                    $this->pwd = $row['pwd'];
                    $this->email = $row['email'];
                    $this->username = $row['username'];
                    $this->activeStatus = $row['actSTatus'];
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

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }