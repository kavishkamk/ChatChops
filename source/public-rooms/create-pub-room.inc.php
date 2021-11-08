<?php

session_start();

require "../phpClasses/PublicRoom.class.php";
//require $_SERVER['DOCUMENT_ROOT'].'/chatchops/source/phpClasses/PublicRoom.class.php';

if(isset($_POST['pub-room-submit'])){

    $groupname = test_input($_POST['groupname']);
    $groupbio = test_input($_POST['groupbio']);
    $icon = test_input($_POST['foo']);

    $roomObj = new PublicRoom($groupname, $groupbio);

    $validate = $roomObj-> validate();
    
    if($validate == 0){
        //valid inputs
        require_once "pubRoomDbHandle.class.php";

        $pubObj = new pubRoomDbHandle();
        $checkName = $pubObj-> checkUniqueName($groupname);

        //name is valid
        if($checkName == 0){
            $create = $pubObj-> createPubRoom($groupname, $groupbio, $icon, $_SESSION['userid']);

            if($create == "ok"){
                header("Location: http://localhost/chatchops/source/insideUI/chatChops.php?status=ok");
            }else{
                header("Location: http://localhost/chatchops/source/insideUI/chatChops.php?status=wrong");
            }
        }else{
            header("Location:create-pub-room.php?error=notavailable&groupname=$groupname&groupbio=$groupbio&picn=$icon");
        }
        
        //send to db and check errors
        //if all good, create a popup in chatUI saying successful
    }
    else if($validate == 1){
        header("Location:create-pub-room.php?error=emptyfields&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 2){
        header("Location:create-pub-room.php?error=wrongname&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 4){
        header("Location:create-pub-room.php?error=namemax&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 5){
        header("Location:create-pub-room.php?error=biomax&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else{
        header("Location:../insideUI/chatChops.php?status=wrong&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }

    unset($roomObj);
    exit();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}