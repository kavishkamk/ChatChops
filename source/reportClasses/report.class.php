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
            $sqlQ = "SELECT lastData FROM analizereords WHERE (SELECT MAX(dataId) FROM analizereords);";
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

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }