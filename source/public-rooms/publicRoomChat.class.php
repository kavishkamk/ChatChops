<?php

require_once '../phpClasses/DbConnection.class.php';

class publicRoomChat extends DbConnection {
    
    //check whether the given user is a member of the given chatRoom
    public function isMemberOfRoom($userid, $roomid)
    {
        /*
        $q2 = "SELECT member_id from pub_grp_member where user_id = ? and group_id = ?;";

        

        $qq = "SELECT * FROM pub_group_user_remove WHERE member_id = ?;";

        if(!mysqli_stmt_prepare($stmt, $qq)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        $active = 1;
        mysqli_stmt_bind_param($stmt, "i", $mem);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($res)){// this user is removed by the admin
            $this-> connclose($stmt, $conn);
            return "-1";
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return $mem;
            exit();
        }
        */

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

    //check whether the given user is an old member
    private function checkOldMember($userid, $roomid)
    {
        /**
         * if already a member -> return mem id
         * else return "0";
         */

         return 0;
    }

    //insert new member details into DB
    public function newMemberJoin($userid, $roomname)
    {
        //pub grp member
        //pub grp member status
        //pub grp member- mem status map

        $roomid = $this-> getRoomId($roomname);
        $joined = date("Y-n-d H:i:s");

        /**
         * get member id -> (userid, grpid)
         * if member id available -> make status 1
         * else create new member id -> the already writen way
         */
        
        $qn = "SELECT member_id from pub_grp_member where group_id = ? and user_id = ?;";

        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $qn)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ii", $roomid, $userid);
        mysqli_stmt_execute($stmt);
        $res1 = mysqli_stmt_get_result($stmt);
        
        if($row1 = mysqli_fetch_assoc($res1)){
            $memberId = $row1['member_id'];
            /******* */

            $qq = "SELECT pub_grp_mem_status.status_id
                FROM (pub_group_mem_status_map 
                INNER JOIN pub_grp_mem_status ON pub_group_mem_status_map.status_id = pub_grp_mem_status.status_id)
                WHERE pub_group_mem_status_map.member_id = ?;";

            if(!mysqli_stmt_prepare($stmt, $qq)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            mysqli_stmt_bind_param($stmt, "i", $memberId);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($res)){
                $statusid = $row['status_id'];

                $qm = "UPDATE pub_grp_mem_status 
                SET active = ?, DateAndTime = ?
                WHERE status_id = ?;";

                $join_status = 1;
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
            $this-> connclose($stmt, $conn);
            return $memberId;
            exit();
        }

        $re = $this-> isMemberOfRoom($userid, $roomid);
        if($re != "0" && $re != "sqlerror"){
            return "sqlerror";
            exit();
        }

        $sqlQ = "INSERT INTO pub_grp_member(group_id, user_id) VALUES(?,?);";
        
        $status = 1;
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