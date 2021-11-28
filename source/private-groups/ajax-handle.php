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
}

//save the member list of newly created private group in the DB
if(isset($_POST['members_save']))
{
    $memlist = $_POST['memlist'];
    $groupid = $_POST['group_id'];

    $obj = new displayGroupList();
    $res = $obj-> save_member_list($groupid, $memlist);
    unset($obj);
    echo json_encode($res);
}

//user asked to load his group list
if(isset($_POST['load_group_list']))
{
    $userid = $_POST['userid'];
    $obj = new displayGroupList();
    $res = $obj -> load_group_list($userid);
    unset($obj);
    echo json_encode($res);
}