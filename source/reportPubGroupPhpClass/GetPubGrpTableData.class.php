<?php
require_once "../phpClasses/DbConnection.class.php";

    class GetPubGrpTableData extends DbConnection{

        // this method used to each day number of public group chat messages in given month
        public function getMonthPubGrpMsg($month, $year){
            $sqlQ = "SELECT * FROM analizepubgrpmsgeachmonthd WHERE recYear = ? AND recMonth = ?;";
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

        // get analized records from analizepubgrpmsgeachmonthd for given week
        public function getWeekPubChatMsg($year, $month, $sdate, $nd){
            $arr = array();
            $resarr = array();
            $sdate = $sdate + 0;
            if($sdate + 6 <= $nd){
                for($i = $sdate; $i < $sdate + 7; $i++){
                    $arr[] = "d".$i;
                }
                $sqlQ = "SELECT $arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6] FROM analizepubgrpmsgeachmonthd WHERE recYear = ? AND recMonth = ?;";
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
                    $resarr[] = $this->getPubGrpData($val, $year, $month);
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
                    $resarr[] = $this->getPubGrpData($val, $year, $month);
                }
                return $resarr;
            }
        }

        private function getPubGrpData($val, $year, $month){
            $sqlQ = "SELECT $val FROM analizepubgrpmsgeachmonthd WHERE recYear = ? AND recMonth = ?;";
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

        // get analize record from analizepubgrpmsgeachdateh for given date
        public function getDayPubGrpChatMsg($day){
            $sqlQ = "SELECT * FROM analizepubgrpmsgeachdateh WHERE recDate = ?;";
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