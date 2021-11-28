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
    public function save_member_list($arr)
    {
        /******************** */
        
    }

    private function connclose($stmt, $conn){
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}