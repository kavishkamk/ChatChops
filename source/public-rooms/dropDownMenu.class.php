<?php

require_once '../phpClasses/DbConnection.class.php';

class dropDownMenu extends DbConnection {

    //get all the room info
    public function get_room_info($roomid)
    {
        //created_date_and_time, bio, icon_link
        $sqlQ = "SELECT created_date_and_time, bio, icon_link 
                FROM public_group WHERE group_id = ?";
        
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
            return $data;
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return "0";
            exit();
        }
    }

    //get all the room admin info
    public function get_admin_info($roomid)
    {
        //first_name, last_name, username, created_time, profilePicLink
        
        $sqlQ = "SELECT users.first_name, users.last_name, users.username, users.created_time, users.profilePicLink 
                    FROM ((pub_grp_member 
                    INNER JOIN pub_grp_admin ON pub_grp_member.member_id = pub_grp_admin.member_id) 
                    INNER JOIN users ON pub_grp_member.user_id = users.user_id) 
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
            return $data;
            exit();
        }else{
            $this-> connclose($stmt, $conn);
            return "0";
            exit();
        } 
    }

    //get all the members' details
    public function get_member_list_data($roomid)
    {
        //fname, lname, username, propic
        
        $sqlQ = "SELECT users.first_name, users.last_name, users.username, users.profilePicLink 
                FROM (users INNER JOIN pub_grp_member ON pub_grp_member.user_id = users.user_id) 
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
    
    //user leave the chat room
    public function leave_room($roomid, $memid)
    {

    }



    //connection closing
    private function connclose($stmt, $conn)
    {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}