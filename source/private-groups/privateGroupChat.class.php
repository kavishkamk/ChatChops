<?php

require_once '../phpClasses/DbConnection.class.php';

class privateGroupChat extends DbConnection {

    //store the sending messages in the DB
    public function storeMsgs($memId, $msg)
    {
        $time = date("Y-n-d H:i:s");
        $sqlQ = "INSERT INTO p_group_chat(msg, send_time) VALUES(?,?);";

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

        $sqlQ1 = "INSERT INTO pgroup_mem_map(msg_id, member_id) VALUES(?,?);";
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

    // return the previous messages from the DB
    public function prev_msgs($roomid)
    {
        $sqlQ = "SELECT * FROM(
            SELECT p_group_member.user_id, 
            users.username, 
            users.profilePicLink,
            p_group_member.group_id, 
            private_group.group_name,
            p_group_member.mem_id,
            p_group_chat.msg_id, 
            p_group_chat.msg
                FROM ((pgroup_mem_map 
                INNER JOIN p_group_member ON pgroup_mem_map.member_id = p_group_member.mem_id) 
                INNER JOIN p_group_chat ON p_group_chat.msg_id = pgroup_mem_map.msg_id), users, private_group
                WHERE p_group_member.group_id = ? AND 
                p_group_member.user_id = users.user_id AND 
                p_group_member.group_id = private_group.group_id
                ORDER BY p_group_chat.msg_id DESC LIMIT 100) T
            ORDER by msg_id ASC;";
        
        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlQ)){
            $this->connclose($stmt, $conn);
            return "sqlerror";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $roomid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = array();
        $i =0;
        while($row = mysqli_fetch_assoc($result)){
            $data[$i] = array('senderId' => $row['user_id'],
                            'username' => $row['username'],
                            'propic' => $row['profilePicLink'],
                            'groupid' => $row['group_id'],
                            'groupname' => $row['group_name'],
                            'memberid' => $row['mem_id'],
                            'msg' => $row['msg']);
            $i++;
        }
        $this->connclose($stmt, $conn);
        return $data;
        exit();
    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}