<?php
require_once "../phpClasses/DbConnection.class.php";
    // this class is used to analize public gropu details
    class AnalizePubGrpData extends DbConnection{

        // this method used to analize public group chat details
        public function analizepubGrpDetails($ldate){
            $this->calPubGrpInHours($ldate);
            //$this->calPubGrpInDay($ldate);
        }

        // this function used to analize user public group data accourding to months
        private function calPubGrpInDay($ldate){
            $recval = $this->check_analizePubGrpachmonthd_Empty();
            
            if($recval != 0){
                $this->updatePubGrpMsgRecEachDayGivenMonth($ldate); // update last update record (in previous analize)
                $this->analizePubGrpMsgRecAfterLastAnalizeDateInMonth($ldate);
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
                $this->analizePubGrpMsgRecAfterLastAnalizeDateInMonth($resDate);
            }  
        }

        // this function used to analize each day number of public group chat messages in month
        // that month take from given date
        // analize data are updated in data base related to that month
        private function updatePubGrpMsgRecEachDayGivenMonth($day){
            $arr3 = explode("-",$day);
            $mon = $arr3[1];
            $ye = $arr3[0];
            $d=cal_days_in_month(CAL_GREGORIAN,$mon,$ye);
            $date=date_create("$ye-$mon-1");     
            $dayTime = date_format($date,"Y-n-d");
            $i = 1;
            for(; $i <= $d; $i++){
                $numOnline = $this->getNumOfPubGrpMsgInGivenDate($dayTime);
                $onlineCounts[$i] = $numOnline;
                $dayTime = date('Y-n-d', strtotime($dayTime . ' +1 day'));
   	        }
            for(; $i<=31; $i++){
                $onlineCounts[$i] = 0;
            }
            $this->updateUserPubGrpMsgRecInEachDayInGivenMonth($onlineCounts, $ye, $mon);
        }

        // update analizepubgrpmsgeachmonthd table
        private function updateUserPubGrpMsgRecInEachDayInGivenMonth($rec, $yea, $mon){
            $sqlQ = "UPDATE analizepubgrpmsgeachmonthd SET d1 = ?, d2 = ?, d3 = ?, d4 = ?, d5 = ?, d6 = ?, d7 = ?,
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

        // this method used to analize public chat message record accourding to the date
        // this analize start after from day after of given date
        // until today this analize happen and recourds are store in analizepubgrpmsgeachmonthd table
        private function analizePubGrpMsgRecAfterLastAnalizeDateInMonth($day){
            $dayTime = date('Y-n-d', strtotime($day . ' +1 day'));
           
            // get dates until today 
            $dayTime = date('Y-m-d',(strtotime ( '+1 month' , strtotime ( $dayTime) ) ));
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
                    $this->setPubGrpMsgRecInDayInGivenMonth($resDate, $d, $ye, $mon);
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

        // set public group chat records in given month
        private function setPubGrpMsgRecInDayInGivenMonth($ldate, $numOfDays, $year, $mon){
            $day = $ldate;
            $i = 1;
            for(; $i <= $numOfDays; $i++){
                $numOnline = $this->getNumOfPubGrpMsgInGivenDate($day);
                $onlineCounts[$i] = $numOnline;
                $day = date('Y-n-d', strtotime($day . ' +1 day'));
            }
            for(; $i<=31; $i++){
                $onlineCounts[$i] = 0;
            }
            $this->insertRecInto_analizepubgrpmsgeachmonthd($year, $mon, $onlineCounts);
        }

        // this function used to get number of private group chat messages in given day
        private function getNumOfPubGrpMsgInGivenDate($day){
            $sqlQ = "SELECT COUNT(msg_id) AS ucount FROM pub_grp_chat WHERE DATE(date_time) = ?;";
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

        // this function used to insert data to analizepubgrpmsgeachmonthd table
        private function insertRecInto_analizepubgrpmsgeachmonthd($year, $mon, $rec){
            $sqlQ = "INSERT INTO analizepubgrpmsgeachmonthd(recYear, recMonth, d1, d2, d3, d4, d5, d6, d7,
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

        // this function used to analize public gropu chat data accourding to hours in day
        private function calPubGrpInHours($ldate){
            $recval = $this->check_analizePubGrpEachdateh_Empty();
            if($recval != 0){
                $this->UpdatePubGrpChatRecInHourInGivenDate($ldate); // update last update record (in previous analize)
                $this->analizePubGrpMsgRecFromAfterLastAnalizeDate($ldate); // analize not analized records
            }
            else{
                $ldate = date('Y-n-d', strtotime($ldate . ' -1 day'));
                $this->analizePubGrpMsgRecFromAfterLastAnalizeDate($ldate); // analize not analized records
            } 
        }

        // this method use to analize user public group chat recordes accourding to hour and update in report table (analizepubgrpmsgeachdateh)
        // this analize that public group chat data in each hour in given date and then update the table using given date
        // this is for update previous inserted row accorging to the given date
        private function UpdatePubGrpChatRecInHourInGivenDate($ldate){

            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x < 24; $x++) {
                $dayEndTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
                $numOnline = $this->getPubGrpMsgInGivenH($dayTime, $dayEndTime);
                $onlineCounts[$x + 1] = $numOnline;
                $dayTime = $dayEndTime;
            }
            $this->updateLastAddedPubGrpMsgRecord($onlineCounts, $ldate);
        }

        // this method used to update records that are used in report table (analizepubgrpmsgeachdateh)
        private function updateLastAddedPubGrpMsgRecord($rec, $day){
            $sqlQ = "UPDATE analizepubgrpmsgeachdateh SET h1=?, h2=?, h3=?, h4=?, h5=?, h6=?, h7=?, h8=?, h9=?, h10=?,
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

        // this method used to analize public gropu chat records accourding to the hours
        // this analize start from day after of given date
        // until today this analize happen and records are store in analizepubgrpmsgeachdateh table
        private function analizePubGrpMsgRecFromAfterLastAnalizeDate($ldate){
            $dayTime = date('Y-n-d', strtotime($ldate . ' +1 day'));
           // get dates until today 
            while(true){
                $this->setPubGrpMsgRecInHourInGivenDate($dayTime);
                if($dayTime >= date('Y-n-d')){
                	break;
                }
                $dayTime = date('Y-n-d', strtotime($dayTime . ' +1 day'));
            }
        }

        // this method use to analize public group chat recordes accourding to hour and insert in report table (analizepubgrpmsgeachdateh)
        // this analize that public group chat data in each hour in given date and then update the table using given date
        // this is for insert new analize data
        private function setPubGrpMsgRecInHourInGivenDate($ldate){
            $date=date_create("$ldate 00:00:00");          
            $dayTime = date_format($date,"Y-n-d H:i:s");
            for ($x = 0; $x < 24; $x++) {
                $dayEndTime =  date('Y-n-d H:i:s', strtotime($dayTime)+3600);
                $numOnline = $this->getPubGrpMsgInGivenH($dayTime, $dayEndTime);
                $onlineCounts[$x + 1] = $numOnline;
                $dayTime = $dayEndTime;
            }
            $this->insertPubGrpMsgRecords($onlineCounts, $ldate);
        }

        // this method is used to insert records for analizepubgrpmsgeachdateh table (about public group chat msg analize)
        private function insertPubGrpMsgRecords($rec, $day){
            $sqlQ = "INSERT INTO analizepubgrpmsgeachdateh(recDate, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12,
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

        // this method is used to get number of public group chat messages in given hour in given date
        private function getPubGrpMsgInGivenH($sdate, $edate){
            $sqlQ = "SELECT COUNT(msg_id) AS ucount FROM pub_grp_chat WHERE date_time BETWEEN ? AND ?;";
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

        // this is for check this table has previous rec or not(analizepubgrpmsgeachdateh)
        private function check_analizePubGrpEachdateh_Empty(){
            $sqlQ = "SELECT COUNT(recId) AS rcount FROM analizepubgrpmsgeachdateh;";
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
        public function check_analizePubGrpachmonthd_Empty(){
            $sqlQ = "SELECT COUNT(recId) AS rcount FROM analizepubgrpmsgeachmonthd;";
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
