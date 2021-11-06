<?php
    require "header.php";
    
?>

<!-- This is a chat interface -->
<main>
    <div class = "chatContainer" id="chat">
        <div class="chat" id="private-chat" style="grid-column:1 / 2; grid-row: 1 / 3">
        </div>

        <div class="chat" id="chat-gui" style="grid-column:2 / 3; grid-row: 1 / 3">
            <!-- ------------------------------>
            <div class="chat-title">
                <span id="reserver-name"></span> <!-- to set reserver name -->
                
                <i class="fas fa-ellipsis-v"></i> <!-- list icon -->

                <!-- public room join button -->
                <button type="button" onclick= 'pubRoom_join()' class="join-room" id="join-room-btn" style="visibility:hidden;">Join</button>
            </div>
            
            <div class= "alert-msg" id = "alert-msg" style="visibility:hidden;"></div>
            <div class="chat-message-list" id="pri-chat-message-list">
                
            </div>

            <div class="chat-body" id="chat-form">
                <form class="chat-form"  onkeydown="return event.key != 'Enter';">
                    <input type="hidden" id="senderId" name="senderId" value="<?php echo ''.$_SESSION["userid"].'';?>"> <!--sender id-->
                    <input type="hidden" id="msgType" name="msgType" value=""> <!-- set message type -->

                    <!-- when select a public chat room -->
                    <input type="hidden" id="roomId" name="roomId" value=""> <!-- set room id -->
                    <input type="hidden" id="roomMemberId" name="roomMemberId" value=""> <!-- set room member id -->


                    <!-- when select a private group -->
                    <!----------------------------------->

                    <input type="text" id="msg" name="msg" placeholder="type a message"/>
                    <button type="button" id="send-msg" name="send-msg" class="send-msg" style="visibility: visible;"><i class="fas fa-paper-plane"></i></button>  
                </form>
            </div>
        
        </div>

        <div class="chat" id="private-group" style="grid-column:3 / 4; grid-row: 1 / 2">
            <div class= "topic">
                Private Groups
            </div>

            <div style= "position: right">
                <a href="#">
                <button id= "grp">Create Group</button>
                </a>
            </div>
        </div>
        
        <div class="chat" id="public-group" style="grid-column:3 / 4; grid-row: 2 / 3">
            <div class= "topic">
                Public Chat Rooms
            </div>

            <div style= "position: right">
                <a href="../public-rooms/create-pub-room.php">
                <button id= "rm">Create Room</button>
                </a>
            </div>

            <div class= "pub-room-list" style="min-width: 400px; max-height: 225px; overflow-x: visible; overflow-y: scroll;">
                <?php

                include_once "../public-rooms/displayRoomList.class.php";
                $roomObj = new displayRoomList();
                $count = $roomObj->roomCount();
                
                $roomname;
                
                if($count == 0){
                    echo '<div class= "room">No chat rooms</div>';
                }
                else if($count == "sqlerror"){
                    echo '<script>alert ("Something went wrong");</script>';
                }
                else{
                    $arr = $roomObj-> fullRoomSetData();
                    /*
                    echo "<pre>";
                    print_r($arr);
                    echo "</pre>";*/

                    for($i=0; $i<$count; $i++){
                        $roomData = $arr[$i];
                        $roomname = $arr[$i]['name'];
                        $icon = $arr[$i]['icon'];
                        $memCount = $roomObj-> getMemberCount($roomname);
                        $roomid = $arr[$i]['id'];
                        $roomDataJSON = json_encode($roomData);

                        echo "<div onclick= 'setPubRoomData($roomDataJSON)' class= 'room active' id = '.$roomname.'>
                        <img src= '../group-icons/$icon' alt='group icon' width='35'height='35' class='img-circle pro-img'>&emsp; 
                        $roomname
                        <span class= 'memcount' style='float: right'>$memCount Members</span>
                        </div>";
                        
                    }
                    unset($roomObj);
                }
                
                ?>
            </div>
        
        </div>
    </div>
</main>

<?php
    $msg = "";
    if(isset($_GET['status'])){
        $msg = setMessage();
        echo '<script>alert ("'.$msg.'")</script>';
    }
    
    function setMessage()
    {
        if(isset($_GET['status'])){
            if($_GET['status'] == 'ok'){
                return "You have successfully created a new chat room";
            }
            else if($_GET['status'] == 'wrong'){
                return "Something went wrong";
            }
        }
    }
?>

<script type="text/javascript">

$(document).ready(function(){
    var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
        sendIntroduceData(); // send data to user introduce
    };

    // this method used to send userid to server to know about user
    function sendIntroduceData(){
        var introdata = {
            cliendId: <?php echo ''.$_SESSION["userid"].'';?>
        };
        conn.send(JSON.stringify(introdata));
    }

})

function setPubRoomData(roomData)
{
    document.getElementById("reserver-name").textContent = roomData.name;
    document.getElementById("msgType").value = "pubg";
    document.getElementById("roomId").value = roomData.id;
    document.getElementById('pri-chat-message-list').innerHTML = "";

    $.ajax({
        method: "POST",
        url: "../public-rooms/ajax-handle.php",
        data: {
            check_membership: "set",
            roomData: roomData,
            roomid: roomData.id,
            userid: <?php echo ''.$_SESSION["userid"].'';?>
        },
        success: function(result){
            var res = JSON.parse(result);
            pubRoom_join_sendMsg_select(res);
        }
        
    });
}
    
function pubRoom_join_sendMsg_select(result)
{
    var sendButton = document.getElementById('send-msg');
    var joinButton = document.getElementById('join-room-btn');
    var roomMemberId = document.getElementById("roomMemberId");

    if(result == "0"){
        sendButton.style.visibility = 'hidden';
        joinButton.style.visibility = 'visible';
        roomMemberId.value = null;
    }
    else if(result == "sqlerror"){
        alert("Something went wrong");
        sendButton.style.visibility = 'visible';
        joinButton.style.visibility = 'hidden';
        roomMemberId.value = null;
    }
    else{
        sendButton.style.visibility = 'visible';
        joinButton.style.visibility = 'hidden';
        roomMemberId.value = result;
        //alert("member id = " + result);
    }
}

function pubRoom_join()
{
    var room = document.getElementById("reserver-name").textContent;
    var sendButton = document.getElementById('send-msg');
    var joinButton = document.getElementById('join-room-btn');


    $.ajax({
        method: "POST",
        url: "../public-rooms/ajax-handle.php",
        data: {
            new_member: "set",
            roomname: room,
            userid: <?php echo ''.$_SESSION["userid"].'';?>
        },
        success: function(result) {
            var res = JSON.parse(result);

            //if join successful send button visible, welcome message show
            if(res != "sqlerror"){
                var welcome ="Welcome to '" + room + "' chat room!";
                displayMsg(welcome, 1);
            }
            //else show error msg and set all to default
            else{
                var msg = "Something went wrong! Try again later.";
                displayMsg(msg, 0);
            }
            sendButton.style.visibility = 'visible';
            joinButton.style.visibility = 'hidden';
        }
    });



}

//display the welcome to chat room message
function displayMsg(msg, type)
{
    var alertmsg = document.getElementById('alert-msg');
    alertmsg.innerHTML = msg;

    if(type == 0){
        alertmsg.style.backgroundColor = "red";
    }
    alertmsg.style.visibility = 'visible';
    setTimeout(hideMsg, 3000, msg);
}

//hide the welcome to chat room message
function hideMsg(msg)
{
    var alertmsg = document.getElementById('alert-msg');
    alertmsg.innerHTML = msg;
    alertmsg.style.visibility = 'hidden';
}


</script>


