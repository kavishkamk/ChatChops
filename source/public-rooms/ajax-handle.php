<?php

require "publicRoomChat.class.php";
require "showPrevMsgs.class.php";
require "dropDownMenu.class.php";
require "displayRoomList.class.php";

//get the last 100 msgs
if(isset($_POST['prev_msgs']))
{
    $roomid = $_POST['roomid'];

    $obj = new showPrevMsgs();
    $result = $obj-> parse_messages($roomid);
    unset($obj);
    echo json_encode($result);
}


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


//set the public room member id after joining
if(isset($_POST['set_memberId']))
{
    $obj = new publicRoomChat();
    $result = $obj-> isMemberOfRoom($_POST['userid'], $_POST['roomid']);
    unset($obj);
    echo json_encode($result);
}

//set group info for the popup window
if(isset($_POST['group_info']))
{
    $obj = new dropDownMenu();
    $res = $obj -> get_room_info($_POST['roomid']);
    unset($obj);
    echo json_encode($res);
}

//set admin info
if(isset($_POST['admin_info']))
{
    $obj = new dropDownMenu();
    $res = $obj -> get_admin_info($_POST['roomid']);
    unset($obj);
    echo json_encode($res);
}

//set member list for the popup window
if(isset($_POST['member_list']))
{
    $obj = new dropDownMenu();
    $res = $obj -> get_member_list_data($_POST['roomid']);
    unset($obj);
    echo json_encode($res);
}

//leave the chat room
if(isset($_POST['leave_room']))
{
    $obj = new dropDownMenu();
    $res = $obj -> leave_room($_POST['roomid'], $_POST['memberid']);
    unset($obj);
    echo json_encode($res);
}

//change the roomCount display when a new member joined the room
if(isset($_POST['mem_count_update']))
{
    $obj = new displayRoomList();
    $res = $obj -> getMemberCount($_POST['roomname']);
    unset($obj);
    echo json_encode($res);
}

//check whether the given user is the admin of that chat room or not
if(isset($_POST['find_admin']))
{
    $obj = new dropDownMenu();
    $res = $obj -> find_admin($_POST['member_id']);
    unset($obj);
    echo json_encode($res);
}

//get the room id for the given room name
if(isset($_POST['get_room_name']))
{
    $obj = new dropDownMenu();
    $res = $obj -> get_room_name($_POST['roomid']);
    unset($obj);
    echo json_encode($res);
}

//get the count of available chat rooms
if(isset($_POST['get_pubg_count']))
{
    $obj = new displayRoomList();
    $count = $obj->roomCount();
    unset($obj);
    echo json_encode($count);
}

//get full data of all the active chat rooms
if(isset($_POST['get_pubg_list']))
{
    $obj = new displayRoomList();
    $res = $obj->fullRoomSetData();
    unset($obj);
    echo json_encode($res);
}