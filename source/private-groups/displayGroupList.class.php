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

    //load all the data of the group list of given userid
    public function load_group_list($userid)
    {
        $q1 = "SELECT   aa.mem_id,
                        private_group.group_id, 
                        private_group.group_name, 
                        private_group.created_date_time,
                        private_group.bio,
                        private_group.group_icon 
                FROM private_group INNER JOIN 
                (SELECT DISTINCT p_group_member.group_id, p_group_member.mem_id 
                FROM ((p_grp_mem_status_map 
                INNER JOIN p_group_member ON 
                p_group_member.mem_id = p_grp_mem_status_map.member_id)
                INNER JOIN pgrp_mem_status ON 
                pgrp_mem_status.statusId = p_grp_mem_status_map.status_id) 
                WHERE p_group_member.user_id = ? AND pgrp_mem_status.actStatus = ?) 
                as aa ON aa.group_id = private_group.group_id AND private_group.pgrp_status = ?;";
        
        $status =1;

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        mysqli_stmt_bind_param($stmt, "iii", $userid, $status, $status);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = array();
        $i =0;
        while($row = mysqli_fetch_assoc($result)){
            $data[$i] = array('member_id'=>$row['mem_id'],
                            'group_id' => $row['group_id'],
                            'group_name' => $row['group_name'],
                            'created' => $row['created_date_time'],
                            'bio' => $row['bio'],
                            'icon' => $row['group_icon']);
            $i++;
        }
        $this->connclose($stmt, $conn);
        return $data;
        exit();

    }

    //check whether the given user is a member or the admin
    public function check_admin($memberid)
    {
        $q1 = "SELECT * FROM pgrp_admin WHERE memberId = ?";
        
        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $status =1;
        mysqli_stmt_bind_param($stmt, "i", $memberid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
            //this user is the admin of this group
            $this->connclose($stmt, $conn);
            return "1";
            exit();
        }else{
            //this user is not the admin of this group
            $this->connclose($stmt, $conn);
            return "0";
            exit();
        }
    }



    private function connclose($stmt, $conn){
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}