<?php
    require_once "../phpClasses/DbConnection.class.php";
    class GetDataToPriGroupReport extends DbConnection {

        // get number of active or deactivated private groups
        // $status = 1 for active groups , $status = 0 for deactive groups
        public function getNumberOfActivePriGroup($status){
            $sqlQ = "SELECT COUNT(group_id) as numOfGroups FROM private_group WHERE pgrp_status=?;";
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
                    return $row['numOfGroups'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "error";
                    exit();
                }
            }
        }

        public function getNumOfAllCreatedGroups(){
            $sqlQ = "SELECT COUNT(group_id) as numOfGroups FROM private_group;";
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
                    return $row['numOfGroups'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "error";
                    exit();
                }
            }
        }

        // this can be used to get number of created groups in given date
        // $val = 1 for created accounts , $val = 0 for deleted accounts
        public function numOfCridDelPriGroupGivenDate($date, $val){
            $sqlQ = "SELECT COUNT(group_id) as numOfGroups FROM private_group WHERE DATE(created_date_time) = ? AND pgrp_status = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "si", $date, $val);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['numOfGroups'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "error";
                    exit();
                }
            }

        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }