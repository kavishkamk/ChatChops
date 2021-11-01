<?php
    require_once "../phpClasses/DbConnection.class.php";
    class GetDataToPubGroupReport extends DbConnection {

        // get number of active or deactivated public groups
        // $status = 1 for active groups , $status = 0 for deactive groups
        public function getNumberOfActivePubGroup($status){
            $sqlQ = "SELECT COUNT(group_id) as numOfGroups FROM public_group WHERE pubgrp_status=?;";
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

        // get number of all public groups
        public function getNumOfAllCreatedPubGroups(){
            $sqlQ = "SELECT COUNT(group_id) as numOfGroups FROM public_group;";
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

        // this can be used to get number of created public groups in given date
        // $val = 1 for created accounts , $val = 0 for deleted accounts
        public function numOfCridDelpubGroupGivenDate($date, $val){
            $sqlQ = "SELECT COUNT(group_id) as numOfGroups FROM public_group WHERE DATE(created_date_and_time) = ? AND pubgrp_status = ?;";
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