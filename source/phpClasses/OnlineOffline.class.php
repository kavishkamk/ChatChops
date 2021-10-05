<?php

    require_once "DbConnection.class.php";

    class OnlineOffline extends DbConnection{

        // set user online status in the database
        public function setUserOnlie($userid){
            $val = 1; $recId = 0;
            $res = $this->setOnlineOffle($userid, $val, $recId);
            return $res;
            exit();
        }

        // set user offline status and add record to database
        public function setUserOffline($userid, $recId){
            // delete sessionid from database
            require_once "SessionHandle.class.php";
            $sesObj = new SessionHandle();
            $sesResult = $sesObj->deleteSesseion($userid);
            unset($sesObj);

            $val = 0;
            $res = $this->setOnlineOffle($userid, $val, $recId);
            return $res;
            exit();
        }

        // set online or offline status of the user table with time
        private function setOnlineOffle($userid, $val, $recId){

            $sqlQ = "UPDATE users SET onlineStatus = ?, last_seen = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $offTime = date("Y-n-d H:i:s"); // acout cration date and time
                mysqli_stmt_bind_param($stmt, "iss", $val, $offTime, $userid);
                mysqli_stmt_execute($stmt);
                if($recId != 0){
                    $this->setLogoutTime($offTime, $recId);
                }
                return "1";
                exit();
            }

        }

        // update offline time of the user in the database
        private function setLogoutTime($offTime, $recId){
            $sqlQ = "UPDATE user_active_time SET offline_date_and_time = ? WHERE active_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $offTime, $recId);
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