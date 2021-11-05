<?php

if(isset($_POST['check-membership']))
{
    require "publicRoomChat.class.php";

    $obj = new publicRoomChat();
    $result = $obj-> isMemberOfRoom($_POST['userid'], $_POST['roomid']);
    unset($obj);

    echo json_encode($result);
}


    //check whether a member of the selected chat room
    //if yes, show the msg bar, send button
    //else, show the join 

    /*
    include_once "../public-rooms/publicRoomChat.class.php";
    $roomchatObj = new publicRoomChat();
    
    $roomId = "<script>document.getElementById('roomId').value</salert>";

    if($roomId != ""){
        $checkmember = $roomchatObj-> isMemberOfRoom($_SESSION["userid"], $roomId);

        if($checkmember != "0" && $checkmember != "sqlerror"){
            echo '<span><button type="button" class="join-room">Join</button></span>';
            echo '<script>alert ("mem = '.$checkmember.'")</script>';
        }else{
            echo '<script>alert ("mem = '.$checkmember.'")</script>';
            
            //echo '<input type="text" id="msg" name="msg" placeholder="type a message"/>
            //<button type="button" id="send-msg" name="send-msg" class="send-msg"><i class="fas fa-paper-plane"></i></button>';
            
        }
        unset($roomchatObj);
    }
    else{
        echo '<input type="text" id="msg" name="msg" placeholder="type a message"/>
            <button type="button" id="send-msg" name="send-msg" class="send-msg"><i class="fas fa-paper-plane"></i></button>';
    }
    */
