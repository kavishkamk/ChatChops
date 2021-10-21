<?php
    require_once "DbConnection.class.php";
    class GetDataToReport extends DbConnection {

        // get number of activate account or deactivated accounts of users using active_status
        // $status = 0 for offline users, $status = 1 for online users
        public function getNumberOfusers($status){
            $sqlQ = "SELECT COUNT(user_id) as numOfUsers FROM users WHERE active_status=?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $status);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['numOfUsers'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return 0;
                    exit();
                }
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }