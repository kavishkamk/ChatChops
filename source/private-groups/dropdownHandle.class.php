<?php

require_once '../phpClasses/DbConnection.class.php';

class dropdownHandle extends DbConnection {

    //get all the group admin info
    public function get_admin_info($groupid)
    {
        //first_name, last_name, username, created_time, profilePicLink
        
        $sqlQ = "SELECT users.first_name, users.last_name, users.username, users.created_time, users.profilePicLink 
                FROM ((p_group_member 
                INNER JOIN pgrp_admin ON p_group_member.mem_id = pgrp_admin.memberId) 
                INNER JOIN users ON p_group_member.user_id = users.user_id) 
                WHERE p_group_member.group_id = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $groupid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($data = mysqli_fetch_assoc($result)){
            $this->connclose($stmt, $conn);
            return $data;
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return "0";
            exit();
        } 
    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}