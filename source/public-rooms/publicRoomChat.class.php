<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/phpClasses/DbConnection.class.php';

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

    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}