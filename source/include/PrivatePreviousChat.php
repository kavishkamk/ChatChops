<?php
    // this script used to get previous unreaded msg fro given user
    // call from ajax function and return result to it
    if(isset($_POST['premsreq'])){
        
        require "../phpClasses/PrivateChatHandle.class.php";
        $priObj = new PrivateChatHandle(); // this for get privat chat detais
        $datas = $priObj->getUnreadPrivatMessage( $_POST['sender'], $_POST['reserver']); // get friend lisg
        unset($priObj);
        echo json_encode($datas);
    }