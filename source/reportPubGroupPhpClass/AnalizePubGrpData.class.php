<?php
require_once "../phpClasses/DbConnection.class.php";
    // this class is used to analize public gropu details
    class AnalizePubGrpData extends DbConnection{

        // this method used to analize public group chat details
        public function analizepubGrpDetails($ldate){
            $this->calPubGrpInHours($ldate);
           // $this->calPriGrpInDay($ldate);
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

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }