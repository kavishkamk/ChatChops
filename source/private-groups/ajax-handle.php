<?php

require_once "../phpClasses/PrivateChatHandle.class.php";
require_once "displayGroupList.class.php";
require_once "dropdownHandle.class.php";
require_once "privateGroupChat.class.php";

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

//check whether the given user is a member or the admin
if(isset($_POST['check_admin']))
{
    $memberid = $_POST['member_id'];

    $obj = new displayGroupList();
    $res = $obj -> check_admin($memberid);
    unset($obj);
    echo json_encode($res);
}

//get the admin info of the given group
if(isset($_POST['admin_data']))
{
    $grp = $_POST['group_id'];

    $obj = new dropdownHandle();
    $res = $obj -> get_admin_info($grp);
    unset($obj);
    echo json_encode($res);
}

// get all the member details
if(isset($_POST['member_list']))
{
    $grp = $_POST['group_id'];

    $obj = new dropdownHandle();
    $res = $obj -> get_member_list_data($grp);
    unset($obj);
    echo json_encode($res);
}

// handle the member leaving
if(isset($_POST['leave_group']))
{
    $grp = $_POST['group_id'];
    $mem = $_POST['member_id'];

    $obj = new dropdownHandle();
    $res = $obj -> leave_group($grp, $mem);
    unset($obj);
    echo json_encode($res);
}

//return the member count of a given private group
if(isset($_POST['member_count']))
{
    $grp = $_POST['group_id'];
    $obj = new displayGroupList();
    $res = $obj -> get_member_count($grp);
    unset($obj);
    echo json_encode($res);
}

//get the members' userid list of a given private group
if(isset($_POST['member_userid_list']))
{
    $grp = $_POST['group_id'];
    $obj = new dropdownHandle();
    $res = $obj -> get_member_userid_list($grp);
    unset($obj);
    echo json_encode($res);
}

//get the last 100 msgs
if(isset($_POST['prev_msgs']))
{
    $grp = $_POST['groupid'];

    $obj = new privateGroupChat();
    $result = $obj-> prev_msgs($grp);
    unset($obj);
    echo json_encode($result);
}