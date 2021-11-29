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

    //get all the members' details
    public function get_member_list_data($grpid)
    {
        //fname, lname, username, propic 

        $sqlQ = "SELECT users.first_name, users.last_name, users.username, users.profilePicLink 
                FROM users INNER JOIN 
                (SELECT distinct p_group_member.user_id FROM 
                ((p_grp_mem_status_map INNER JOIN 
                p_group_member ON p_group_member.mem_id = p_grp_mem_status_map.member_id) 
                INNER JOIN pgrp_mem_status ON pgrp_mem_status.statusId = p_grp_mem_status_map.status_id) 
                WHERE p_group_member.group_id = ? AND pgrp_mem_status.actStatus = ?) 
                as aa ON users.user_id = aa.user_id;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $active =1;
        mysqli_stmt_bind_param($stmt, "ii", $grpid, $active);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = array();
        $i =0;
        while($row = mysqli_fetch_assoc($result)){
            $data[$i] = array('fname' => $row['first_name'],
                            'lname' => $row['last_name'],
                            'username' => $row['username'],
                            'propic' => $row['profilePicLink']);
            $i++;
        }
        $this->connclose($stmt, $conn);
        return $data;
        exit();
    }

    // member leave the group
    public function leave_group($grpid, $memid)
    {

    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}