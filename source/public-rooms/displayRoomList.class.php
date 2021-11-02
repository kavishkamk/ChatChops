<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/phpClasses/DbConnection.class.php';

class displayRoomList extends DbConnection{

    private $rooms_set = array();

    //return the #of active rooms
    public function roomCount()
    {
        $sqlQ = "SELECT group_id, group_name, created_date_and_time, bio, icon_link FROM public_group WHERE pubgrp_status = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        $active = 1;
        mysqli_stmt_bind_param($stmt, "i", $active);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $count =0;
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['group_id'];
            $name = $row['group_name'];
            $time = $row['created_date_and_time'];
            $bio = $row['bio'];
            $icon = $row['icon_link'];

            $this->rooms_set[$count] = array("id"=>$id, "name"=>$name, "time"=>$time, "bio"=>$bio, "icon"=>$icon);
            $count++;
        }

        //fullRoomSetData($rooms_set);
        return $count;
        exit();
    }


    //return an array of full data of every chat room
    public function fullRoomSetData()
    {
        return $this-> rooms_set;
    }


    //return group id for the given group name
    private function getRoomId($name)
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
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['group_id'];
    }


    //return how many members are joined in a chat room
    public function getMemberCount($name)
    {
        $groupID = $this-> getRoomId($name);

        $sqlQ = "SELECT pub_grp_member.member_id
            FROM ((pub_group_mem_status_map
            INNER JOIN pub_grp_member ON pub_group_mem_status_map.member_id = pub_grp_member.member_id)
            INNER JOIN pub_grp_mem_status ON pub_group_mem_status_map.status_id = pub_grp_mem_status.status_id)
            WHERE pub_grp_mem_status.active = ? AND pub_grp_member.group_id = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        $active = 1;
        mysqli_stmt_bind_param($stmt, "ii", $active, $groupID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultcheck = mysqli_stmt_num_rows($stmt);
        return $resultcheck;

    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

}