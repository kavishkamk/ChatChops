<?php
    require_once "DbConnection.class.php";

    class SessionHandle extends DbConnection {
        
        // set given session to the database
        public function setSession($userid, $sessionVal){

            $delres = $this->deleteSesseion($userid);

            if($delres == "1"){
                $sqlQ = "INSERT INTO user_session(users_id, session_id, session_expire) VALUES(?,?,?);";
                $conn = $this->connect();
                $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                    $this->connclose($stmt, $conn);
                    return "sqlerror";
                    exit();
                }
                else{
                    $sessionExp = date("Y-n-d H:i:s", strtotime('+6 hours')); // session expire time
                    mysqli_stmt_bind_param($stmt, "iss", $userid, $sessionVal, $sessionExp);
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

        // this is used to remove privious session from DB
        public function deleteSesseion($userid){
            $sqlQ = "DELETE FROM user_session WHERE users_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $userid);
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