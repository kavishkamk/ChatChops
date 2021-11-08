<?php
    require_once "DbConnection.class.php";

    // this class used to handle admin session

    // set admin session in database
    class AdminSessionHandle extends DbConnection {

        // set given admin session to the database
        public function setSession($adminid, $sessionVal){

            $delres = $this->deleteSesseion($adminid);

            if($delres == "1"){
                $sqlQ = "INSERT INTO admin_session(admin_id, session_id, session_expire) VALUES(?,?,?);";
                $conn = $this->connect();
                $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                    $this->connclose($stmt, $conn);
                    return "sqlerror";
                    exit();
                }
                else{
                    $sessionExp = date("Y-n-d H:i:s", strtotime('+6 hours')); // session expire time
                    mysqli_stmt_bind_param($stmt, "iss", $adminid, $sessionVal, $sessionExp);
                    mysqli_stmt_execute($stmt);
                    $this->connclose($stmt, $conn);
                    return "1";
                    exit();
                }
            }
            else{
                return "sqlerror";
                exit();
            }
        }

        // this is used to remove privious session of admin from DB
        public function deleteSesseion($adminid){
            $sqlQ = "DELETE FROM admin_session WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $adminid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }
        }

        // this function for check sessions
        public function checkSession($sessionval, $uid){
            $sqlQ = "SELECT session_id, session_expire FROM admin_session WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $uid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    if($sessionval == $row['session_id']){
                        if($row['session_expire'] < date("Y-n-d H:i:s")){
                            $delres = $this->deleteSesseion($uid);
                            $this->connclose($stmt, $conn);
                            return "sessionexp"; // session expired
                            exit();
                        }
                        else{
                            $this->connclose($stmt, $conn);
                            return "1"; // session ok
                            exit();
                        }
                    }
                    else{
                        $this->connclose($stmt, $conn);
                        return "noaccess"; // no session
                        exit();
                    }
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