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

    // add new members to the group
    private function add_new_member($groupid, $arr)
    {
        foreach($arr as $e){
            $userid = $e;

            //check whether this user was a member of the group
            // if yes, make the status=1
            // else add new entry

            $qn = "SELECT mem_id from p_group_member where group_id = ? and user_id = ?;";

            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $qn)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ii", $groupid, $userid);
            mysqli_stmt_execute($stmt);
            $res1 = mysqli_stmt_get_result($stmt);
            
            if($row1 = mysqli_fetch_assoc($res1)){
                $memberId = $row1['mem_id'];

                $qq = "SELECT pgrp_mem_status.statusId
                FROM (p_grp_mem_status_map 
                INNER JOIN pgrp_mem_status ON p_grp_mem_status_map.status_id = pgrp_mem_status.statusId)
                WHERE p_grp_mem_status_map.member_id = ?;";

                if(!mysqli_stmt_prepare($stmt, $qq)){
                    $this->connclose($stmt, $conn);
                    return "sqlerror";
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "i", $memberId);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($res)){
                    $statusid = $row['statusId'];

                    $qm = "UPDATE pgrp_mem_status 
                    SET actStatus = ?, addDate = ?
                    WHERE statusId = ?;";

                    $join_status = 1;
                    $joined = date("Y-n-d H:i:s");

                    if(!mysqli_stmt_prepare($stmt, $qm)){
                        $this->connclose($stmt, $conn);
                        return "sqlerror";
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt, "isi", $join_status, $joined, $statusid);
                    mysqli_stmt_execute($stmt);
                }else{
                    $this->connclose($stmt, $conn);
                    return "sqlerror";
                    exit();
                }
                continue;
            }

            else{
                $q1 = "INSERT INTO p_group_member(user_id, group_id) VALUES(?,?);";

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
            }
            
            $this->connclose($stmt, $conn);

        }
        return "ok";
        exit();
    }

    // remove a member from a private group
    private function user_remove($groupid, $arr, $admin_memberid)
    {
        foreach($arr as $e){
            $userid = $e;

            $q1 = "SELECT mem_id FROM p_group_member 
                WHERE user_id = ? AND group_id = ?;";

            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $q1)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ii", $userid, $groupid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);

            $memberid = $row['mem_id'];

            $q2 = "SELECT * FROM p_grp_mem_status_map WHERE member_id = ?;";

            if(!mysqli_stmt_prepare($stmt, $q2)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "i", $memberid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            
            $status_id = $row['status_id'];

            $q3 = "UPDATE pgrp_mem_status 
                    SET actStatus = ?
                    WHERE statusId = ?;";
            
            $activ = 0;
            if(!mysqli_stmt_prepare($stmt, $q3)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ii", $activ, $status_id);
            mysqli_stmt_execute($stmt);
            
            $q4 = "SELECT * FROM pgrp_admin WHERE memberId = ?;";

            if(!mysqli_stmt_prepare($stmt, $q4)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "i", $admin_memberid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            
            $admin_id = $row['adminId'];
            $removed = date("Y-n-d H:i:s");

            $q5 = "INSERT INTO p_group_user_remove
                    (member_id, admin_id, DateAndTime) VALUES 
                    (?, ?, ?);";

            if(!mysqli_stmt_prepare($stmt, $q5)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "iis", $memberid, $admin_memberid, $removed);
            mysqli_stmt_execute($stmt);

            $this->connclose($stmt, $conn);

        }
        
        return "ok";
        exit();
    }

    //update the new member list of a group
    public function update_member_list($groupid, $newlist, $oldlist, $admin_memberid)
    {
        $remove_arr = array_diff($oldlist, $newlist);
        $add_arr = array_diff($newlist, $oldlist);

        $remove = array();
        $add = array();

        $m=0;
        foreach($remove_arr as $w){
            $remove[$m] = $w;
            $m++;
        }

        $n=0;
        foreach($add_arr as $q){
            $add[$n] = $q;
            $n++;
        }

        $res1 =0;
        $res2 =0;

        if($remove != null) {
            $r1 = $this-> user_remove($groupid, $remove, $admin_memberid);

            if($r1 == "ok"){
                $res1 = 1;
            }
        }

        if($remove == null) $res1 = 2;
        if($add == null)    $res2 = 2;

        if($add != null) {
            $r2 = $this->add_new_member($groupid, $add);

            if($r2 == "ok") {
                $res2 = 1;
            }
        }

        $res = array();
        $res['remove_list'] = $remove;
        $res['add_list'] = $add;
        $res['remove_status'] = $res1;
        $res['add_status'] = $res2;

        return $res;
    }
    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}