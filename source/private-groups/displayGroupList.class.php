<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/phpClasses/DbConnection.class.php';

class displayGroupList extends DbConnection{
    
    //get the friend list of a given userid
    //userid, fname, lname, username, propic
    public function get_username($userid)
    {
        $sqlQ = "SELECT username FROM users WHERE user_id = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $userid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_assoc($res);

        $this->connclose($stmt, $conn);
        return $result['username'];
        exit();
    }

    //store the new member list of a private group in the DB
    public function save_member_list($groupid, $arr)
    {
        /******************** */
        foreach($arr as $e){
            $userid = $e;
            $q1 = "INSERT INTO p_group_member(user_id, group_id) VALUES(?,?);";
            
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $q1)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ii", $userid, $groupid);
            mysqli_stmt_execute($stmt);

            $memberId = mysqli_stmt_insert_id($stmt);

            $q2 = "INSERT INTO pgrp_mem_status(addDate, actStatus) VALUES(?,?);";

            $tim = date("Y-n-d H:i:s");
            $status =1;

            if(!mysqli_stmt_prepare($stmt, $q2)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "si", $tim, $status);
            mysqli_stmt_execute($stmt);

            $statusid = mysqli_stmt_insert_id($stmt);

            $q3 = "INSERT INTO p_grp_mem_status_map(status_id, member_id) VALUES(?,?);";

            if(!mysqli_stmt_prepare($stmt, $q3)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ii", $statusid, $memberId);
            mysqli_stmt_execute($stmt);

            $this->connclose($stmt, $conn);

        }
        return "ok";
        exit();
    }

    private function connclose($stmt, $conn){
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}