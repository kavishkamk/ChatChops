<?php

    require_once "DbConnection.class.php";

    // this class used to set user online, offline status with time and set login records
    class OnlineOffline extends DbConnection{

        // set user online status in the database
        public function setUserOnline($userid){
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

        // set user offline status and time in the DB with user id
        public function setOfflineStatusInDB($userid){
            $sqlQ = "UPDATE users SET onlineStatus = ?, last_seen = ? WHERE user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $val0 = 0;
                $offTime = date("Y-n-d H:i:s"); // acout cration date and time
                mysqli_stmt_bind_param($stmt, "isi", $val0, $offTime, $userid);
                mysqli_stmt_execute($stmt);
                $this->setLogoutTimeWithUserId($userid, $offTime);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }
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
                mysqli_stmt_bind_param($stmt, "isi", $val, $offTime, $userid);
                mysqli_stmt_execute($stmt);
                if($recId != 0){
                    $this->setLogoutTime($offTime, $recId);
                }
                $this->connclose($stmt, $conn);
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

        // set user offline time (logout time) using user id
        private function setLogoutTimeWithUserId($uid, $offTime){
            $lastid = $this->getLastIdOf_user_user_act_id_map($uid);
            $res = $this->setLogoutTime($offTime, $lastid);
        }

        // this method used to get last updated row number related to user in user_user_act_id_map table
        // for given user id
        private function getLastIdOf_user_user_act_id_map($uid){
            $sqlQ = "SELECT MAX(active_id) AS lastid FROM user_user_act_id_map WHERE active_id IN (SELECT active_id FROM user_user_act_id_map WHERE user_id=?);";
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
                    $this->connclose($stmt, $conn);
                    return $row['lastid'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "nouser";
                    exit();
                }
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }