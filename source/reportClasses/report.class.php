<?php
    // this class contains methods that used to get details for analize datas
    require_once "../phpClasses/DbConnection.class.php";

    class RepoerDetails extends DbConnection{

        // this class used to set last analize time in analizereords table
        public function setLastAnalizeTime(){
            $sqlQ = "INSERT INTO analizereords(lastData) VALUES(?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $anzTime = date("Y-n-d H:i:s");
                mysqli_stmt_bind_param($stmt, "s", $anzTime);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return $anzTime;
                exit();
            }
        }

        // get last analize date and time
        public function getLastAnalizeTime(){
            $sqlQ = "SELECT lastData FROM analizereords WHERE dataId IN (SELECT MAX(dataId) FROM analizereords);";
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
                    return $row['lastData'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "noRec";
                    exit();
                }
            }
        }

        // get last analize date
        public function getLastAnalizeDate(){
            $sqlQ = "SELECT DATE(lastData) AS lday FROM analizereords WHERE (SELECT MAX(dataId) FROM analizereords);";
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
                    return $row['lday'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "noRec";
                    exit();
                }
            }
        }

        // this method used to analize all system data and store that in relavant tables
        public function analizeSystemData(){
            $ldate = $this->getLastAnalizeDate(); // get last updated date and time
            echo $ldate;
                echo '<span>......</span>';
                echo '<br>';
            
            if($ldate == "sqlerror" || $ldate == "noRec"){
                $ldate = $this->getFirstOnlineDate();
                echo '<span>I am in</span>';
                echo $ldate;
                echo '<span>......</span>';
                echo '<br>';
                if ($ldate == 0) {
                    $ldate = date("Y-n-d");
                }
                echo $ldate;
                echo '<span>......</span>';
                echo '<br>';
            }

            if($ldate == "sqlerror" || $ldate == "noRec"){
                return "Empty";
                exit();
            }
            else{
                // analize private chat data
                require_once "../reportPrivatePhpClass/AnalizePriOnlineData.class.php";
                $anzPriOn = new AnalizePriOnlineData();
                $anzPriOn->analizePrivateMemberDetails($ldate);
                unset($anzPriOn);
                require_once "../reportPriGroupPhpClass/AnalizePriGrpData.class.php";
                $anzPriGrp = new AnalizePriGrpData();
                //$anzPriGrp->analizePriGrpDetails($ldate);
                unset($anzPriGrp);
                require_once "../reportPubGroupPhpClass/AnalizePubGrpData.class.php";
                $anzPubGrp = new AnalizePubGrpData();
                //$anzPubGrp->analizepubGrpDetails($ldate);
                unset($anzPubGrp);
                return "susses";
                exit();
            }
        }

        // get first record date
        public function getFirstOnlineDate(){
            $sqlQ = "SELECT DATE(online_date_and_time) as fdate FROM user_active_time LIMIT ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $val1 = 1;
                mysqli_stmt_bind_param($stmt, "i", $val1);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['fdate'];
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
