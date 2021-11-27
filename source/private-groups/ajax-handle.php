<?php

require_once "../phpClasses/PrivateChatHandle.class.php";
require_once "displayGroupList.class.php";

//get the friend list of a given userid
if(isset($_POST['set_friend_list']))
{
    $userid = $_POST['userid'];

    $obj = new PrivateChatHandle();
    $result = $obj-> getFriendList($userid);
    unset($obj);
    echo json_encode($result);
    exit();
    /*
    if($result != "sqlerror"){
        $data = array();
        $obj1 = new displayGroupList();

        $i=0;
        while($result[$i]){
            $row = $result[$i];
            $id = $row['user_id'];

            $username = $obj1 -> get_username($id);
            $row['username'] = $username;
            
            $data[$i] = $row;
            $i++;
        }
        unset($obj1);
        echo json_encode($data);
    }
    */
    
}