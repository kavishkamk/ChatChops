<?php

require $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/phpClasses/DbConnection.class.php';

class pubRoomDbHandle extends DbConnection{

    private $group_id;
    private $group_name;
    private $created_date_and_time;
    private $bio;
    private $pubgrp_status;
    private $icon_link;
    private $created_user_id;
    private $member_id;
    private $admin_id;
    private $member_status_id;

    private function getGroupID()
    {
        $name = $this->group_name;
        $sqlQ = "SELECT group_id FROM public_group WHERE group_name = ?;";

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

    public function checkUniqueName($name)
    {
        $sqlQ = "SELECT group_id FROM public_group WHERE group_name = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $name);
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

    public function createPubRoom($name, $bio, $icon, $createUserID)
    {
        $status = 1;
        $this-> group_name = $name;
        $this-> bio = $bio;
        $this-> created_user_id = $createUserID;
        
        $sqlQ = "INSERT INTO public_group(group_name, created_date_and_time, bio, pubgrp_status, icon_link) 
            VALUES(?,?,?,?,?);";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        if($icon != "groupchat-icon.png"){
            rename("../group-icons/$icon","../group-icons/$name.png");
            $icon = "$name.png";
        }
        $this-> icon_link = $icon;

        $createTime = date("Y-n-d H:i:s");
        $this-> created_date_and_time = $createTime;

        mysqli_stmt_bind_param($stmt, "sssis", $name, $createTime, $bio, $status, $icon);
        mysqli_stmt_execute($stmt);

        $groupid = mysqli_stmt_insert_id($stmt);
        $this-> group_id = $groupid;
        $this->connclose($stmt, $conn);

        $r1 = $this-> pub_group_admin_setup();
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
    private function pub_group_admin_setup()
    {
        $user = $this-> created_user_id;
        $group = $this-> group_id;

        $sqlQ = "INSERT INTO pub_grp_user_map(created_user_id, group_id) VALUES(?,?);";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ii", $user, $group);
        mysqli_stmt_execute($stmt);

        $q1 = "INSERT INTO pub_grp_member(group_id, user_id) VALUES(?,?);";
        
        if(!mysqli_stmt_prepare($stmt, $q1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $group, $user);
        mysqli_stmt_execute($stmt);
        $memid = mysqli_stmt_insert_id($stmt);
        $this-> member_id = $memid;

        $q2 = "INSERT INTO pub_grp_admin(member_id) VALUES(?);";

        if(!mysqli_stmt_prepare($stmt, $q2)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $memid);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_stmt_insert_id($stmt);
        $this-> admin_id = $admin;

        $q3 = "INSERT INTO pub_grp_mem_status(DateAndTime, active) VALUES(?,?);";

        if(!mysqli_stmt_prepare($stmt, $q3)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        $time = $this->created_date_and_time;
        $s = 1;
        mysqli_stmt_bind_param($stmt, "si", $time, $s);
        mysqli_stmt_execute($stmt);
        $memStatusid = mysqli_stmt_insert_id($stmt);
        $this-> member_status_id = $memStatusid;

        $q4 = "INSERT INTO pub_group_mem_status_map(status_id, member_id) VALUES(?,?);";

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


    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}