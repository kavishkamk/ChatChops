<?php

require_once '../phpClasses/DbConnection.class.php';

class showPrevMsgs extends DbConnection {

    //get the last msgId for the given pubRoom id
    private function get_last_msg_id($roomid)
    {
        $sqlQ = "SELECT max(pub_grp_chat.msg_id) 
        FROM ((pub_grp_chat_mem_map 
        INNER JOIN pub_grp_member ON pub_grp_chat_mem_map.member_id = pub_grp_member.member_id) 
        INNER JOIN pub_grp_chat ON pub_grp_chat.msg_id = pub_grp_chat_mem_map.msg_id) 
        WHERE pub_grp_member.group_id = ?;";

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

        if($data = mysqli_fetch_assoc($result)){
            $this->connclose($stmt, $conn);
            return $data['max(pub_grp_chat.msg_id)'];
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return "0";
            exit();
        } 
    }

    //parse the messages one by one
    public function parse_messages($roomid)
    {
        $sqlQ = "SELECT * FROM(
            SELECT pub_grp_member.user_id, 
            users.username, 
            users.profilePicLink,
            pub_grp_member.group_id, 
            public_group.group_name,
            pub_grp_member.member_id,
            pub_grp_chat.msg_id, 
            pub_grp_chat.msg
                FROM ((pub_grp_chat_mem_map 
                INNER JOIN pub_grp_member ON pub_grp_chat_mem_map.member_id = pub_grp_member.member_id) 
                INNER JOIN pub_grp_chat ON pub_grp_chat.msg_id = pub_grp_chat_mem_map.msg_id), users, public_group
                WHERE pub_grp_member.group_id = ? AND 
                pub_grp_member.user_id = users.user_id AND 
                pub_grp_member.group_id = public_group.group_id
                ORDER BY pub_grp_chat.msg_id DESC LIMIT 100) T
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
                            'roomId' => $row['group_id'],
                            'roomname' => $row['group_name'],
                            'roomMemberId' => $row['member_id'],
                            'msg' => $row['msg']);
            $i++;
        }
        $this->connclose($stmt, $conn);
        return $data;
        exit();
    }

    //set all the pubRoom chat message data
    public function set_messages($data)
    {
        /*var data = {
            msgType: msgType,
            senderId: senderId,
            username: username,
            propic: propic,
            roomId: roomId,
            roomname: roomname,
            roomMemberId: roomMemberId,
            msg: msg
        };*/
    }

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}

/*
======= get last 10 rows
SELECT * 
FROM ( SELECT * 
        FROM yourTableName 
        ORDER BY id 
        DESC LIMIT 10 )
Var1 ORDER BY id ASC;

SELECT pub_grp_chat.msg_id, pub_grp_chat.msg
        FROM ((pub_grp_chat_mem_map 
        INNER JOIN pub_grp_member ON pub_grp_chat_mem_map.member_id = pub_grp_member.member_id) 
        INNER JOIN pub_grp_chat ON pub_grp_chat.msg_id = pub_grp_chat_mem_map.msg_id) 
        WHERE pub_grp_member.group_id = 1 
        ORDER BY pub_grp_chat.msg_id DESC LIMIT 3;

SELECT pub_grp_member.user_id, 
users.username, 
users.profilePicLink,
pub_grp_member.group_id, 
public_group.group_name,
pub_grp_member.member_id,
pub_grp_chat.msg_id, 
pub_grp_chat.msg
        FROM ((pub_grp_chat_mem_map 
        INNER JOIN pub_grp_member ON pub_grp_chat_mem_map.member_id = pub_grp_member.member_id) 
        INNER JOIN pub_grp_chat ON pub_grp_chat.msg_id = pub_grp_chat_mem_map.msg_id), users, public_group
        WHERE pub_grp_member.group_id = 1 AND 
        pub_grp_member.user_id = users.user_id AND 
        pub_grp_member.group_id = public_group.group_id
        ORDER BY pub_grp_chat.msg_id DESC LIMIT 3;

// get all the data which needs to display prev msgs in a pubRoom chat
SELECT * FROM(
SELECT pub_grp_member.user_id, 
users.username, 
users.profilePicLink,
pub_grp_member.group_id, 
public_group.group_name,
pub_grp_member.member_id,
pub_grp_chat.msg_id, 
pub_grp_chat.msg
        FROM ((pub_grp_chat_mem_map 
        INNER JOIN pub_grp_member ON pub_grp_chat_mem_map.member_id = pub_grp_member.member_id) 
        INNER JOIN pub_grp_chat ON pub_grp_chat.msg_id = pub_grp_chat_mem_map.msg_id), users, public_group
        WHERE pub_grp_member.group_id = 1 AND 
        pub_grp_member.user_id = users.user_id AND 
        pub_grp_member.group_id = public_group.group_id
        ORDER BY pub_grp_chat.msg_id DESC LIMIT 30) T
ORDER by msg_id ASC;


*/