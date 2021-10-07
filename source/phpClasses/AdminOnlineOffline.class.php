<?php
    require_once "DbConnection.class.php";

    // this class is used to set admin online states with time
    class AdminOnlineOffline extends DbConnection{

        // set user online status in the database
        public function setAdminOnlie($adminid){
            $val = 1;
            $res = $this->setOnlineOffline($adminid, $val);
            return $res;
            exit();
        }

        // set user offline status and add record to database
        public function setAdminOffline($adminid){
            // delete sessionid from database
            require_once "AdminSessionHandle.class.php";
            $sesObj = new AdminSessionHandle();
            $sesResult = $sesObj->deleteSesseion($adminid);
            unset($sesObj);

            $val = 0;
            $res = $this->setOnlineOffline($adminid, $val);
            return $res;
            exit();
        }

        // set online or offline status of the user table with time
        private function setOnlineOffline($adminid, $val){

            $sqlQ = "UPDATE admins SET online_status = ?, lastSeenDT = ? WHERE admin_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $offTime = date("Y-n-d H:i:s"); // acout cration date and time
                mysqli_stmt_bind_param($stmt, "isi", $val, $offTime, $adminid);
                mysqli_stmt_execute($stmt);
                return "1";
                exit();
            }

        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }