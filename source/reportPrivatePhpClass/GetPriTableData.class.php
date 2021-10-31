<?php
require_once "../phpClasses/DbConnection.class.php";

    class GetPriTableData extends DbConnection{

        // this method used to each day number of online users in given month
        public function getMonthOnlineUsers($month, $year){
            $sqlQ = "SELECT * FROM analizeonlineeachmonthd WHERE recYear = ? AND recMonth = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ss", $year, $month);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                $this->connclose($stmt, $conn);
                return $row;
                exit();
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }