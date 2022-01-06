<?php
require_once "../phpClasses/DbConnection.class.php";

    // this class is used to analize private chat data
    class AnalizePriOnlineData extends DbConnection{

        // this method used to analize private user details
        public function analizePrivateMemberDetails($ldate){
            $this->calOnlineInHours($ldate);
            $this->calOnlineInDay($ldate);
            $this->calPriMsgInHours($ldate);
            $this->calPriMsgInDay($ldate);
        }

        // this function used to analize user online data accourding to hours in day
        private function calOnlineInHours($ldate){
            $recval = $this->check_analizeonlineeachdateh_Empty();
            if($recval != 0){
                $this->UpdateOnlineRecInHourInGivenDate($ldate); // update last update record (in previous analize)
                $this->analizeOnlineRecFromAfterLastAnalizeDate($ldate); // analize not analized records
            }
            else{
                $ldate = date('Y-n-d', strtotime($ldate . ' -1 day'));
                $this->analizeOnlineRecFromAfterLastAnalizeDate($ldate); // analize not analized records
            } 
        }

        // this function used to analize user online data accourding to months
        private function calOnlineInDay($ldate){
            $recval = $this->check_analizeonlineeachmonthd_Empty();
		
            if($recval != 0){
                $this->updateOnlineRecEachDayGivenMonth($ldate); // update last update record (in previous analize)
                $this->analizeOnlineRecAfterLastAnalizeDateInMonth($ldate);
            }
            else{
		    $ldate = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $ldate) ) ));
                $arr = explode("-",$ldate);
		    $mon = $arr[1];
                $ye = $arr[0];
                $day = $arr[2];
                $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
                $date=date_create("$ye-$mon-$day");   
                $resDate = date_format($date,"Y-n-d");
                $resDate = date('Y-n-d', strtotime($resDate . ' -1 day'));
                $this->analizeOnlineRecAfterLastAnalizeDateInMonth($resDate);
            }  
        }

        // this function used to analize private chat messages data accourding to months
        private function calPriMsgInDay($ldate){
            $recval = $this->check_analizePriMsgEachmonthd_Empty();

            if($recval != 0){
                $this->updatePriChatRecEachDayGivenMonth($ldate); // update last update record (in previous analize)
                $this->analizePriMsgRecAfterLastAnalizeDateInMonth($ldate);
            }
            else{
		$ldate = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $ldate) ) ));
                $arr = explode("-",$ldate);
                $mon = $arr[1];
                $ye = $arr[0];
                $day = $arr[2];
                $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
                $date=date_create("$ye-$mon-$day");       
                $resDate = date_format($date,"Y-n-d");
                $resDate = date('Y-n-d', strtotime($resDate . ' -1 day'));
                $this->analizePriMsgRecAfterLastAnalizeDateInMonth($resDate);
            }
        }

        // this function used to analize private chat data accourding to hours in day
        private function calPriMsgInHours($ldate){
            $recval = $this->check_analizePriMsgEachdateh_Empty();

            if($recval != 0){
                $this->UpdatePriMsgRecInHourInGivenDate($ldate); // update last update record (in previous analize)
                $this->analizePriMsgRecFromAfterLastAnalizeDate($ldate); // analize not analized records
            }
            else{
                $ldate = date('Y-n-d', strtotime($ldate . ' -1 day'));
                $this->analizePriMsgRecFromAfterLastAnalizeDate($ldate); // analize not analized records
            }
        }

        // this function used to analize each day number of private chat messages in month
        // that month take from given date
        // analize data are updated in data base related to that month
        private function updatePriChatRecEachDayGivenMonth($day){
            $arr3 = explode("-",$day);
            $mon = $arr3[1];
            $ye = $arr3[0];
            $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
            $date=date_create("$ye-$mon-1");     
            $dayTime = date_format($date,"Y-n-d");
            $i = 1;
            for(; $i <= $d; $i++){
                $numOnline = $this->getNumOfPriMsgInGivenDate($dayTime);
                $onlineCounts[$i] = $numOnline;
                $dayTime = date('Y-n-d', strtotime($dayTime . ' +1 day'));
   	        }
            for(; $i<=31; $i++){
                $onlineCounts[$i] = 0;
            }
            $this->updatePriChatRecInEachDayInGivenMonth($onlineCounts, $ye, $mon);
        }

        // this function used to analize each day number of online users in month
        // that month take from given date
        // analize data are updated in data base related to that month
        private function updateOnlineRecEachDayGivenMonth($day){
            $arr3 = explode("-",$day);
            $mon = $arr3[1];
            $ye = $arr3[0];
            $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
            $date=date_create("$ye-$mon-1");     
            $dayTime = date_format($date,"Y-n-d");
            $i = 1;
            for(; $i <= $d; $i++){
                $numOnline = $this->getNumOfOnlineUsersInGivenDate($dayTime);
                $onlineCounts[$i] = $numOnline;
                $dayTime = date('Y-n-d', strtotime($dayTime . ' +1 day'));
   	        }
            for(; $i<=31; $i++){
                $onlineCounts[$i] = 0;
            }
            $this->updateUserOnlineRecInEachDayInGivenMonth($onlineCounts, $ye, $mon);
        }

        // update analizeprimsgeachmonthd table
        private function updatePriChatRecInEachDayInGivenMonth($rec, $yea, $mon){
            $sqlQ = "UPDATE analizeprimsgeachmonthd SET d1 = ?, d2 = ?, d3 = ?, d4 = ?, d5 = ?, d6 = ?, d7 = ?,
            d8 = ?, d9 = ?, d10 = ?, d11 = ?, d12 = ?, d13 = ?, d14 = ?, d15 = ?, d16 = ?, d17 = ?, d18 = ?, d19 = ?,
            d20 = ?, d21 = ?, d22 = ?, d23 = ?, d24 = ?, d25 = ?, d26 = ?, d27 = ?, d28 = ?, d29 = ?, d30 = ?, d31 = ?
            WHERE recYear = ? AND recMonth = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
 
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiss", $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24], $rec[25], $rec[26], $rec[27], $rec[28], $rec[29], $rec[30], $rec[31], $yea, $mon);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();
            }
        }

        // update analizeonlineeachmonthd table
        private function updateUserOnlineRecInEachDayInGivenMonth($rec, $yea, $mon){
            $sqlQ = "UPDATE analizeonlineeachmonthd SET d1 = ?, d2 = ?, d3 = ?, d4 = ?, d5 = ?, d6 = ?, d7 = ?,
            d8 = ?, d9 = ?, d10 = ?, d11 = ?, d12 = ?, d13 = ?, d14 = ?, d15 = ?, d16 = ?, d17 = ?, d18 = ?, d19 = ?,
            d20 = ?, d21 = ?, d22 = ?, d23 = ?, d24 = ?, d25 = ?, d26 = ?, d27 = ?, d28 = ?, d29 = ?, d30 = ?, d31 = ?
            WHERE recYear = ? AND recMonth = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
 
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiss", $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24], $rec[25], $rec[26], $rec[27], $rec[28], $rec[29], $rec[30], $rec[31], $yea, $mon);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();
            }
        }

        // this function used to get number of private chat messages in given day
        private function getNumOfPriMsgInGivenDate($day){
            $sqlQ = "SELECT COUNT(p_id) AS ucount FROM private_message WHERE DATE(send_time) = ?;";
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

        // this function used to get number of online users in given day
        private function getNumOfOnlineUsersInGivenDate($day){
            $sqlQ = "SELECT COUNT(users.user_id) AS ucount FROM users WHERE users.user_id IN
            (SELECT DISTINCT user_user_act_id_map.user_id FROM
            (user_user_act_id_map INNER JOIN
            user_active_time ON user_user_act_id_map.active_id = user_active_time.active_id
            AND (DATE(user_active_time.online_date_and_time) = ? OR
            (DATE(user_active_time.online_date_and_time) < ? AND DATE(user_active_time.offline_date_and_time) >= ?))));";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
 
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "sss", $day, $day, $day);
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

        // this method used to analize private chat message record accourding to the date
        // this analize start after from day after of given date
        // until today this analize happen and recourds are store in analizeprimsgeachmonthd table
        private function analizePriMsgRecAfterLastAnalizeDateInMonth($day){
            $dayTime = date('Y-n-d', strtotime($day . ' +1 day'));
            // get dates until today 
		$dayTime = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $dayTime) ) ));
		echo $dayTime;
            $arr2 = explode("-",$dayTime);
            $mon = $arr2[1];
            $ye = $arr2[0];
            $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
            $date=date_create("$ye-$mon-1");      
            $resDate = date_format($date,"Y-n-d");
            $thisMonth = date('n');
            $thisYear = date('Y');
            if($thisMonth == 12){
                $nextYear =  $thisYear + 1;
                $nextMonth = 1;
            }
            else{
                $nextYear =  $thisYear;
                $nextMonth = $thisMonth + 1;
            }

            if(($nextMonth != 1 && $ye == $nextYear && $mon < $nextMonth) || ($nextMonth == 1 && $ye <= $nextYear)){
                while(true){
                    $this->setPriMsgRecInDayInGivenMonth($resDate, $d, $ye, $mon);
                    if($ye == date('Y') && $mon == date('n')){
                        break;
                    }
                    if($mon == 12){
                        $mon = 1;
                        $ye++;
                    }
                    else{
                        $mon++;
                    }
                    $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
                    $date=date_create("$ye-$mon-1");          
                    $resDate = date_format($date,"Y-n-d");
                }
            }
        }

        // this method used to analize user online record accourding to the date
        // this analize start after from day after of given date
        // until today this analize happen and recourds are store in analizeonlineeachmonthd table
        private function analizeOnlineRecAfterLastAnalizeDateInMonth($day){
            $dayTime = date('Y-n-d', strtotime($day . ' +1 day'));
           
            // get dates until today 
		$dayTime = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $dayTime) ) ));
            $arr2 = explode("-",$dayTime);
            $mon = $arr2[1] + 1;
            $ye = $arr2[0];
            $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
            $date=date_create("$ye-$mon-1");      
            $resDate = date_format($date,"Y-n-d");
            $thisMonth = date('n');
            $thisYear = date('Y');
            if($thisMonth == 12){
                $nextYear =  $thisYear + 1;
                $nextMonth = 1;
            }
            else{
                $nextYear =  $thisYear;
                $nextMonth = $thisMonth + 1;
            }

            if(($nextMonth != 1 && $ye == $nextYear && $mon < $nextMonth) || ($nextMonth == 1 && $ye <= $nextYear)){
                while(true){
                    $this->setOnlineRecInDayInGivenMonth($resDate, $d, $ye, $mon);
                    if($ye == date('Y') && $mon == date('n')){
                        break;
                    }
                    if($mon == 12){
                        $mon = 1;
                        $ye++;
                    }
                    else{
                        $mon++;
                    }
                    $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
                    $date=date_create("$ye-$mon-1");          
                    $resDate = date_format($date,"Y-n-d");
                }
            }
        }

        // this method used to analize private messages records accpirding to the hour
        // this analize start from day after of given date
        // until today this analize happen and records are store in analizeprimsgeachdateh table
        private function analizePriMsgRecFromAfterLastAnalizeDate($ldate){
            $dayTime = date('Y-n-d', strtotime($ldate . ' +1 day'));
            // get dates until today 
            while(true){
                $this->setPriMsgRecInHourInGivenDate($dayTime);
                if($dayTime >= date('Y-n-d')){
                	break;
                }
                $dayTime = date('Y-n-d', strtotime($dayTime . ' +1 day'));
            }
        }

        // this method used to analize user online records accourding to the hours
        // this analize start from day after of given date
        // until today this analize happen and records are store in analizeonlineeachdateh table
        private function analizeOnlineRecFromAfterLastAnalizeDate($ldate){
            
	        $dayTime = date('Y-n-d', strtotime($ldate . ' +1 day'));
            
           // get dates until today 
            while(true){
                $this->setOnlineRecInHourInGivenDate($dayTime);
                if($dayTime >= date('Y-n-d')){
                	break;
                }
            	$dayTime = date('Y-n-d', strtotime($dayTime . ' +1 day'));
            }
        }

        // set private chat records in given month
        private function setPriMsgRecInDayInGivenMonth($ldate, $numOfDays, $year, $mon){
            $day = $ldate;
            $i = 1;
            for(; $i <= $numOfDays; $i++){
                $numOnline = $this->getNumOfPriMsgInGivenDate($day);
                $onlineCounts[$i] = $numOnline;
                $day = date('Y-n-d', strtotime($day . ' +1 day'));
            }
            for(; $i<=31; $i++){
                $onlineCounts[$i] = 0;
            }
            $this->insertRecInto_analizeprimsgeachmonthd($year, $mon, $onlineCounts);
        }

        // set online records in given month
        private function setOnlineRecInDayInGivenMonth($ldate, $numOfDays, $year, $mon){
            $day = $ldate;
            $i = 1;
            for(; $i <= $numOfDays; $i++){
                $numOnline = $this->getNumOfOnlineUsersInGivenDate($day);
                $onlineCounts[$i] = $numOnline;
                $day = date('Y-n-d', strtotime($day . ' +1 day'));
            }
            for(; $i<=31; $i++){
                $onlineCounts[$i] = 0;
            }
            $this->insertRecInto_analizeonlineeachmonthd($year, $mon, $onlineCounts);
        }

        // this method use to analize private online records accourding to hour and insert in report table (analizeprimsgeachdateh)
        // this analize that private msg data in each hour in given date and then update the table using given date
        // this is for insert new analize data
        private function setPriMsgRecInHourInGivenDate($ldate){
            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x < 24; $x++) {
                $dayEndTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
                $numOnline = $this->getNumOfPriChatInGivenH($dayTime, $dayEndTime);
                $onlineCounts[$x + 1] = $numOnline;
                $dayTime = $dayEndTime;
            }
            $this->insertPriMsgRecords($onlineCounts, $ldate);
        }

        // this method use to analize user online recordes accourding to hour and insert in report table (analizeonlineeachdateh)
        // this analize that online user data in each hour in given date and then update the table using given date
        // this is for insert new analize data
        private function setOnlineRecInHourInGivenDate($ldate){
            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x < 24; $x++) {
                $dayEndTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
                $numOnline = $this->getOnlineUsersInGivenH($dayTime, $dayEndTime);
                $onlineCounts[$x + 1] = $numOnline;
                $dayTime = $dayEndTime;
            }
            $this->insertOnlineRecords($onlineCounts, $ldate);
        }

        // this method use to analize private msg recordes accourding to hour and update in report table (analizeprimsgeachdateh)
        // this analize that private messages data in each hour in given date and then update the table using given date
        // this is for update previous inserted row accorging to the given date
        private function UpdatePriMsgRecInHourInGivenDate($ldate){
            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x < 24; $x++) {
                $dayEndTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
                $numOnline = $this->getNumOfPriChatInGivenH($dayTime, $dayEndTime);
                $onlineCounts[$x + 1] = $numOnline;
                $dayTime = $dayEndTime;
            }
            $this->updateLastAddedPriMsgRecord($onlineCounts, $ldate);
        }

        // this method use to analize user online recordes accourding to hour and update in report table (analizeonlineeachdateh)
        // this analize that online user data in each hour in given date and then update the table using given date
        // this is for update previous inserted row accorging to the given date
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

        // this function used to insert data to analizeprimsgeachmonthd table
        private function insertRecInto_analizeprimsgeachmonthd($year, $mon, $rec){
            $sqlQ = "INSERT INTO analizeprimsgeachmonthd(recYear, recMonth, d1, d2, d3, d4, d5, d6, d7,
            d8, d9, d10, d11, d12, d13, d14, d15, d16, d17, d18, d19, d20, d21, d22, d23, d24, d25, d26,
            d27, d28, d29, d30, d31) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";

            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii", $year, $mon, $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24], $rec[25], $rec[26], $rec[27], $rec[28], $rec[29], $rec[30], $rec[31]);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();
            }
        }

        // this function used to insert data to analizeonlineeachmonthd table
        private function insertRecInto_analizeonlineeachmonthd($year, $mon, $rec){
            $sqlQ = "INSERT INTO analizeonlineeachmonthd(recYear, recMonth, d1, d2, d3, d4, d5, d6, d7,
            d8, d9, d10, d11, d12, d13, d14, d15, d16, d17, d18, d19, d20, d21, d22, d23, d24, d25, d26,
            d27, d28, d29, d30, d31) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";

            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii", $year, $mon, $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24], $rec[25], $rec[26], $rec[27], $rec[28], $rec[29], $rec[30], $rec[31]);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();
            }
        }

        // this method is used to insert records for nalizeprimsgeachdateh table (about privat chat msg analize)
        private function insertPriMsgRecords($rec, $day){
            $sqlQ = "INSERT INTO analizeprimsgeachdateh(recDate, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12,
            h13, h14, h15, h16, h17, h18, h19, h20, h21, h22, h23, h24)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "siiiiiiiiiiiiiiiiiiiiiiii", $day, $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24]);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();
            }
        }

        // this method is used to insert records for analizeonlineeachdateh table
        private function insertOnlineRecords($rec, $day){
            $sqlQ = "INSERT INTO analizeonlineeachdateh(recDate, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12,
            h13, h14, h15, h16, h17, h18, h19, h20, h21, h22, h23, h24)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "siiiiiiiiiiiiiiiiiiiiiiii", $day, $rec[1], $rec[2], $rec[3], $rec[4], $rec[5], $rec[6], $rec[7], $rec[8], $rec[9], $rec[10], $rec[11], $rec[12], $rec[13], $rec[14], $rec[15], $rec[16], $rec[17], $rec[18], $rec[19], $rec[20], $rec[21], $rec[22], $rec[23], $rec[24]);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "Success";
                exit();
            }

        }

        // this method used to update records that are used in report table (analizeprimsgeachdateh)
        private function updateLastAddedPriMsgRecord($rec, $day){
            $sqlQ = "UPDATE analizeprimsgeachdateh SET h1=?, h2=?, h3=?, h4=?, h5=?, h6=?, h7=?, h8=?, h9=?, h10=?,
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

        // this method is used to get number of privat chat messages in given hour in given date
        private function getNumOfPriChatInGivenH($sdate, $edate){
            $sqlQ = "SELECT COUNT(p_id) AS ucount FROM private_message WHERE send_time BETWEEN ? AND ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ss", $sdate, $edate);
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

        // this method is used to get number of online users in given hour in given date
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

        // this is for check this table has previous rec or not (analizeprimsgeachdateh)
        private function check_analizePriMsgEachdateh_Empty(){
            $sqlQ = "SELECT COUNT(recId) AS rcount FROM analizeprimsgeachdateh;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['rcount'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return 0;
                    exit();
                }
            }
        }

        // this is for check this table has previous rec or not (analizeprimsgeachmonthd)
        public function check_analizePriMsgEachmonthd_Empty(){
            $sqlQ = "SELECT COUNT(recId) AS rcount FROM analizeprimsgeachmonthd;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['rcount'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return 0;
                    exit();
                }
            }
        }

        // this is for check this table has previous rec or not
        public function check_analizeonlineeachmonthd_Empty(){
            $sqlQ = "SELECT COUNT(recId) AS rcount FROM analizeonlineeachmonthd;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['rcount'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return 0;
                    exit();
                }
            }
        }

        // this is for check this table has previous rec or not(analizeonlineeachdateh)
        private function check_analizeonlineeachdateh_Empty(){
            $sqlQ = "SELECT COUNT(recId) AS rcount FROM analizeonlineeachdateh;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['rcount'];
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
