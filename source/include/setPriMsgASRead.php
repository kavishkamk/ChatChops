<?php
    
    if(isset($_POST['msgid'])){
        require "../phpClasses/PrivateChatHandle.class.php";
        $priObj = new PrivateChatHandle();
        $priObj->setPrivatMsgAsRead($_POST['msgid']);
        unset($priObj);
    }
    