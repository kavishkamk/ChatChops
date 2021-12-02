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
        $sqlQ = "SELECT pgrp_mem_status.statusId
                FROM ((p_grp_mem_status_map 
                    INNER JOIN p_group_member ON p_grp_mem_status_map.member_id = p_group_member.mem_id) 
                    INNER JOIN pgrp_mem_status ON p_grp_mem_status_map.status_id = pgrp_mem_status.statusId)
                WHERE p_group_member.group_id = ? AND p_group_member.mem_id = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $grpid, $memid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        $row = mysqli_fetch_assoc($res);
        $status_id = $row['statusId'];

        $q1 = "UPDATE pgrp_mem_status 
                SET actStatus = ?
                WHERE statusId = ?;";
        
        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $active =0;
        mysqli_stmt_bind_param($stmt, "ii", $active, $status_id);
        mysqli_stmt_execute($stmt);

        $q2 = "INSERT INTO private_group_leave(date_and_time) VALUES(?);";
        
        $left = date("Y-n-d H:i:s");

        if(!mysqli_stmt_prepare($stmt, $q2)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $left);
        mysqli_stmt_execute($stmt);

        $leave_id = mysqli_stmt_insert_id($stmt);

        $q3 = "INSERT INTO p_group_leave_mem_map(leave_id, member_id) VALUES(?,?);";
        
        if(!mysqli_stmt_prepare($stmt, $q3)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $leave_id, $memid);
        mysqli_stmt_execute($stmt);

        $this->connclose($stmt, $conn);
        return 1;
        exit();
    }

    // get the user ids of all the members in a private group
    public function get_member_userid_list($grpid)
    {
        $q1 = "SELECT distinct p_group_member.user_id FROM 
        ((p_grp_mem_status_map INNER JOIN 
        p_group_member ON p_group_member.mem_id = p_grp_mem_status_map.member_id) 
        INNER JOIN pgrp_mem_status ON pgrp_mem_status.statusId = p_grp_mem_status_map.status_id) 
        WHERE p_group_member.group_id = ? AND pgrp_mem_status.actStatus = ?";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $status =1;
        mysqli_stmt_bind_param($stmt, "ii", $grpid, $status);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $data = array();
        $i=0;

        while($row = mysqli_fetch_assoc($res)){
            $data[$i]=$row['user_id'];
            $i++;
        }
        $this->connclose($stmt, $conn);
        return $data;
        exit();
    }

    // admin delete the private group
    public function delete_group($roomid)
    {
        $q = "UPDATE private_group 
            SET pgrp_status = ?
            WHERE group_id = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $q)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $activ = 0;
        mysqli_stmt_bind_param($stmt, "ii", $activ, $roomid);
        mysqli_stmt_execute($stmt);

        $q1 = "INSERT INTO p_grp_delete(group_id, date_time) VALUES (?, ?);";

        $deleted  = date("Y-n-d H:i:s");

        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "is", $roomid, $deleted);
        mysqli_stmt_execute($stmt);

        $this->connclose($stmt, $conn);
        return 1; //deleted
        exit();
    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}