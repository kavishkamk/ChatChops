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

        // get analized records from analizeonlineeachmonthd for given week
        public function getWeekOnlineUsers($year, $month, $sdate, $nd){
            $arr = array();
            $resarr = array();
            $sdate = $sdate + 0;
            if($sdate + 6 <= $nd){
                for($i = $sdate; $i < $sdate + 7; $i++){
                    $arr[] = "d".$i;
                }
                $sqlQ = "SELECT $arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6] FROM analizeonlineeachmonthd WHERE recYear = ? AND recMonth = ?;";
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
                    if($row != NULL){
                        for($r = 0; $r < 7; $r++){
                            $resarr[$r] = $row[$arr[$r]];
                        }
                    }
                    return $resarr;
                    exit();
                }
            }
            else{
                $count = 0;
                for($i = $sdate; $i <= $nd; $i++){
                    $val = "d".$i;
                    $resarr[] = $this->getWeekOnlineUserData($val, $year, $month);
                    $count++;
                }
                if($month == 12){
                    $month = 1;
                    $year = $year + 1;
                }
                else{
                    $month = $month + 1;
                }
                for($j = 1; $j <= (7 - $count); $j++){
                    $val = "d".$j;
                    $resarr[] = $this->getWeekOnlineUserData($val, $year, $month);
                }
                return $resarr;
            }
        }

        private function getWeekOnlineUserData($val, $year, $month){
            $sqlQ = "SELECT $val FROM analizeonlineeachmonthd WHERE recYear = ? AND recMonth = ?;";
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

                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row[''.$val.'']; // user found
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return 0; // no user match
                    exit();
                }
            }
        }

        // get analize record from analizeonlineeachdateh for given date
        public function getDayOnlineUsers($day){
            $sqlQ = "SELECT * FROM analizeonlineeachdateh WHERE recDate = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $day);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                $this->connclose($stmt, $conn);
                return $row;
                exit();
            }
        }

        /// this method used to each day number of private chat messages in given month
        public function getMonthPriChatMsg($month, $year){
            $sqlQ = "SELECT * FROM analizeprimsgeachmonthd WHERE recYear = ? AND recMonth = ?;";
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

        // get analized records from analizeprimsgeachmonthd for given week
        public function getWeekPriChatMsg($year, $month, $sdate, $nd){
            $arr = array();
            $resarr = array();
            $sdate = $sdate + 0;
            if($sdate + 6 <= $nd){
                for($i = $sdate; $i < $sdate + 7; $i++){
                    $arr[] = "d".$i;
                }
                $sqlQ = "SELECT $arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6] FROM analizeprimsgeachmonthd WHERE recYear = ? AND recMonth = ?;";
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
                    if($row != NULL){
                        for($r = 0; $r < 7; $r++){
                            $resarr[$r] = $row[$arr[$r]];
                        }
                    }
                    return $resarr;
                    exit();
                }
            }
            else{
                $count = 0;
                for($i = $sdate; $i <= $nd; $i++){
                    $val = "d".$i;
                    $resarr[] = $this->getData($val, $year, $month);
                    $count++;
                }
                if($month == 12){
                    $month = 1;
                    $year = $year + 1;
                }
                else{
                    $month = $month + 1;
                }
                for($j = 1; $j <= (7 - $count); $j++){
                    $val = "d".$j;
                    $resarr[] = $this->getData($val, $year, $month);
                }
                return $resarr;
            }
        }

        private function getData($val, $year, $month){
            $sqlQ = "SELECT $val FROM analizeprimsgeachmonthd WHERE recYear = ? AND recMonth = ?;";
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

                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row[''.$val.'']; // user found
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return 0; // no user match
                    exit();
                }
            }
        }

        // get analize record from analizeonlineeachdateh for given date
        public function getDayPriChatMsg($day){
            $sqlQ = "SELECT * FROM analizeprimsgeachdateh WHERE recDate = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $day);
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