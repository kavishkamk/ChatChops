<?php
    require_once "DbConnection.class.php";
    class FriendList extends DbConnection{

        public function getFriendList($uid){
            $sqlQ = "SELECT user_id, first_name, last_name, profilePicLink FROM users WHERE NOT user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "i", $uid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $this->connclose($stmt, $conn);
                return $result;
                exit();
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }