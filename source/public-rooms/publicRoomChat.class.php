<?php

require_once '../phpClasses/DbConnection.class.php';

class publicRoomChat extends DbConnection {
    
    //check whether the given user is a member of the given chatRoom
    public function isMemberOfRoom($userid, $roomid)
    {
        $sqlQ = "SELECT pub_grp_member.member_id
            FROM ((pub_group_mem_status_map
            INNER JOIN pub_grp_member ON pub_group_mem_status_map.member_id = pub_grp_member.member_id)
            INNER JOIN pub_grp_mem_status ON pub_group_mem_status_map.status_id = pub_grp_mem_status.status_id)
            WHERE pub_grp_mem_status.active = ? 
            AND pub_grp_member.group_id = ?
            AND pub_grp_member.user_id = ?;";
        
        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }

        $active = 1;
        mysqli_stmt_bind_param($stmt, "iii", $active, $roomid, $userid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($res)){
            $this-> connclose($stmt, $conn);
            return $row['member_id'];
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return "0";
            exit();
        }
        
    }
    
    
    //get the room id of the given room name
    public function getRoomId($name)
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
        $res = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($res)){
            $this-> connclose($stmt, $conn);
            return $row['group_id'];
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return "0";
            exit();
        }
    }


    //insert new member details into DB
    public function newMemberJoin($userid, $roomname)
    {
        //pub grp member
        //pub grp member status
        //pub grp member- mem status map

        $roomid = $this-> getRoomId($roomname);

        $re = $this-> isMemberOfRoom($userid, $roomid);
        if($re != "0" && $re != "sqlerror"){
            return "sqlerror";
            exit();
        }

        $sqlQ = "INSERT INTO pub_grp_member(group_id, user_id) VALUES(?,?);";
        
        $joined = date("Y-n-d H:i:s");
        $status = 1;
        
        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $roomid, $userid);
        mysqli_stmt_execute($stmt);
        
        //get the member id
        $memberId = mysqli_stmt_insert_id($stmt);
        
        $sqlQ1 = "INSERT INTO pub_grp_mem_status(DateAndTime, active) VALUES(?,?);";
        if(!mysqli_stmt_prepare($stmt, $sqlQ1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "si", $joined, $status);
        mysqli_stmt_execute($stmt);
        
        //get the status id
        $statusId = mysqli_stmt_insert_id($stmt);
        
        $sqlQ2 = "INSERT INTO pub_group_mem_status_map(status_id, member_id) VALUES(?,?);";
        if(!mysqli_stmt_prepare($stmt, $sqlQ2)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $statusId, $memberId);
        mysqli_stmt_execute($stmt);

        $this->connclose($stmt, $conn);
        return $memberId;
        exit();
    }


    //store the sending messages in the DB
    public function storeMsgs($memId, $msg)
    {
        //public group chat
        //public group chat- member map
        /***************************** */
        $time = date("Y-n-d H:i:s");
        $sqlQ = "INSERT INTO pub_grp_chat(msg, date_time) VALUES(?,?);";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $msg, $time);
        mysqli_stmt_execute($stmt);

        //get the message id
        $msgId = mysqli_stmt_insert_id($stmt);

        $sqlQ1 = "INSERT INTO pub_grp_chat_mem_map(msg_id, member_id) VALUES(?,?);";
        if(!mysqli_stmt_prepare($stmt, $sqlQ1)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $msgId, $memId);
        mysqli_stmt_execute($stmt);
        
        $this->connclose($stmt, $conn);
        return $msgId;
        exit();
    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}