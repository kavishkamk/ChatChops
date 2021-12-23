<?php
    // this used for set message as read in private message
    if(isset($_POST['frid'])){
        require "../phpClasses/FriendList.class.php";
        $priObj = new FriendList();
        $priObj->blockFriend($_POST['userid'], $_POST['frid']);
        unset($priObj);
    }