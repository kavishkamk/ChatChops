<?php
/*
    checkUniqueName($groupname)

    createPrivateGroup($groupname, $groupbio, $icon, $userid)
*/

require $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/phpClasses/DbConnection.class.php';

class privateGroupDbHandle extends DbConnection{
    private $group_id;
    private $group_name;
    private $created_date_time;
    private $bio;
    private $pgrp_status;
    private $group_icon;

    private $created_user_id;
    private $member_id;
    private $admin_id;
    private $member_status_id;

    private function getGroupID()
    {
        $name = $this->group_name;
        $sqlQ = "SELECT group_id FROM private_group WHERE group_name = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultcheck = mysqli_stmt_num_rows($stmt);

            if($resultcheck == 0){
                return $resultcheck;
                exit();
            }else{
                $result = mysqli_fetch_assoc($stmt);
                return $result['group_id'];
                exit();
            }
        }
    }

    //check whether the given name is free or already available
    public function checkUniqueName($name)
    {
        $sqlQ = "SELECT group_id FROM private_group WHERE group_name = ? AND pgrp_status= ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $st = 1;
        mysqli_stmt_bind_param($stmt, "si", $name, $st);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultcheck = mysqli_stmt_num_rows($stmt);
        $this->connclose($stmt, $conn);

        if($resultcheck == 0){
            return "0"; // free name
            exit();
        }
        else if($resultcheck == "sqlerror"){
            return "sqlerror";
            exit();
        }
        else {
            return "1"; // already have
            exit();
        }
        
    }

    public function createPrivateGroup($name, $bio, $icon, $createUserID)
    {
        $status = 1;
        $this-> group_name = $name;
        $this-> bio = $bio;
        $this-> created_user_id = $createUserID;
        
        $sqlQ = "INSERT INTO private_group(group_name, created_date_time, bio, pgrp_status, group_icon) 
            VALUES(?,?,?,?,?);";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        if($icon != "groupchat-icon.png"){
            rename("../private-group-icons/$icon", "../private-group-icons/$name.png");
            $icon = "$name.png";
        }
        $this-> group_icon = $icon;

        $createTime = date("Y-n-d H:i:s");
        $this-> created_date_time = $createTime;

        mysqli_stmt_bind_param($stmt, "sssis", $name, $createTime, $bio, $status, $icon);
        mysqli_stmt_execute($stmt);

        $groupid = mysqli_stmt_insert_id($stmt);
        $this-> group_id = $groupid;
        $this->connclose($stmt, $conn);

        $r1 = $this-> pri_group_admin_setup();
        if($r1 == "ok"){
            return "ok";
            exit();
        }else{
            return "sqlerror";
            exit();
        }
    }

        //insert data into, 
    //pub_grp_user_map, pub_grp_member, pub_grp_admin, pub_grp_mem_status, pub_group_mem_status_map
    private function pri_group_admin_setup()
    {
        $user = $this-> created_user_id;
        $group = $this-> group_id;

        $sqlQ = "INSERT INTO user_pgroup_map(created_user_id, group_id) VALUES(?,?);";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $user, $group);
        mysqli_stmt_execute($stmt);

        $q1 = "INSERT INTO p_group_member(group_id, user_id) VALUES(?,?);";
        
        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $group, $user);
        mysqli_stmt_execute($stmt);
        $memid = mysqli_stmt_insert_id($stmt);
        $this-> member_id = $memid;

        $q2 = "INSERT INTO pgrp_admin(memberId) VALUES(?);";

        if(!mysqli_stmt_prepare($stmt, $q2)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $memid);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_stmt_insert_id($stmt);
        $this-> admin_id = $admin;

        $q3 = "INSERT INTO pgrp_mem_status(addDate, actStatus) VALUES(?,?);";

        if(!mysqli_stmt_prepare($stmt, $q3)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        $time = $this->created_date_time;
        $s = 1;
        mysqli_stmt_bind_param($stmt, "si", $time, $s);
        mysqli_stmt_execute($stmt);
        $memStatusid = mysqli_stmt_insert_id($stmt);
        $this-> member_status_id = $memStatusid;

        $q4 = "INSERT INTO p_grp_mem_status_map(status_id, member_id) VALUES(?,?);";

        if(!mysqli_stmt_prepare($stmt, $q4)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $this-> member_status_id, $memid);
        mysqli_stmt_execute($stmt);

        $this->connclose($stmt, $conn);
        return "ok";
        exit();
    }

    //send the full dataset of the newly created group
    public function full_group_dataset()
    {
        $data = array();

        $data['group_id'] = $this-> group_id;
        $data['group_name'] = $this-> group_name;
        $data['created'] = $this-> created_date_time;
        $data['bio'] = $this-> bio;
        $data['group_icon'] = $this-> group_icon;
        $data['created_user_id'] = $this-> created_user_id;

        return $data;
        exit();
    }

    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

}