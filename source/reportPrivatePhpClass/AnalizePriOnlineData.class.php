<?php
require_once "../phpClasses/DbConnection.class.php";
    class AnalizePriOnlineData extends DbConnection{

        // this method used to analize private user details
        public function analizePrivateMemberDetails($ldate){
            $this->calOnlineInHours($ldate);
        }

        private function calOnlineInHours($ldate){
            $this->UpdateOnlineRecInHourInGivenDate($ldate); // update last update record
        }

        // this method use to analize user online recordes accourding to hour and set date in report table
        private function setOnlineRecInHourInGivenDate($ldate){
            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x <= 24; $x++) {
	            $dayTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
            }
        }

        // this method use to analize user online recordes accourding to hour and update in report table (analizeonlineeachdateh)
        private function UpdateOnlineRecInHourInGivenDate($ldate){

            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x < 24; $x++) {
                $dayEndTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
                $numOnline = $this->getOnlineUsersInGivenH($dayTime, $dayEndTime);
                $onlineCounts[$x + 1] = $numOnline;
                $dayTime = $dayEndTime;
            }
            $this->updateLastAddedRecord($onlineCounts, $ldate);
        }

        // this method used to update records that are used in report table (analizeonlineeachdateh)
        private function updateLastAddedRecord($rec, $day){
            $sqlQ = "UPDATE analizeonlineeachdateh SET h1=?, h2=?, h3=?, h4=?, h5=?, h6=?, h7=?, h8=?, h9=?, h10=?,
            h11=?, h12=?, h13=?, h14=?, h15=?, h16=?, h17=?, h18=?, h19=?, h20=?, h21=?, h22=?, h23=?, h24=? WHERE recDate=?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiiiiiiiiiiiiiis", $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24], $day);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "success";
                exit();
            }
        }

        // this method is used to get online users in given hours
        private function getOnlineUsersInGivenH($sdate, $edate){
            $sqlQ = "SELECT COUNT(users.user_id) AS ucount FROM users WHERE users.user_id IN
            (SELECT DISTINCT user_user_act_id_map.user_id FROM
            (user_user_act_id_map INNER JOIN user_active_time ON user_user_act_id_map.active_id = user_active_time.active_id
            AND ((user_active_time.online_date_and_time BETWEEN ? AND ?)
            OR (user_active_time.online_date_and_time < ? AND
            user_active_time.offline_date_and_time >= ?))));";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ssss", $sdate, $edate, $sdate, $sdate);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['ucount'];
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