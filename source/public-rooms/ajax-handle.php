<?php

require "publicRoomChat.class.php";

//check whether the given user is already a member of the given chat room
if(isset($_POST['check_membership']))
{
    $obj = new publicRoomChat();
    $result = $obj-> isMemberOfRoom($_POST['userid'], $_POST['roomid']);
    unset($obj);

    echo json_encode($result);
}


//add a new member to the given chat room
if(isset($_POST['new_member']))
{
    $obj = new publicRoomChat();
    $result = $obj-> newMemberJoin($_POST['userid'], $_POST['roomname']);
    unset($obj);
    
    echo json_encode($result);
}
