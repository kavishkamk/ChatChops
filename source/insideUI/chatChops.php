<?php
    require "header.php";
    require "../phpClasses/PrivateChatHandle.class.php"; 
?>

<!-- This is a chat interface -->
<main>
    <div class = "chatContainer" id="chat">
        <div class="chat" id="private-chat" style="grid-column:1 / 2; grid-row: 1 / 3">
        
         <div class="search-bar"><p>Friends</p>
                    </div>
                    <?php
                        $priObj = new PrivateChatHandle(); // this for get privat chat detais
                        $datas = $priObj->getFriendList($_SESSION['userid']); // get friend lisg
                        unset($priObj);
                    ?>
                    <div class="friend-accounts">
                        <?php
                            // set each users
                            foreach($datas as $row){
                                // set chat user details (separated by space) to use later
                                $vall = $row['user_id']." ".$row["first_name"]." ".$row["last_name"]." ".$row["profilePicLink"];
                                // set chat bar for given usr
                                $onlineicon = '<i class="fas fa-circle text-danger"></i>';
                                if($row['onlineStatus'] == 1){
                                    $onlineicon = '<i class="fas fa-circle text-success"></i>';
                                }
                                echo '<div onclick="setChatRoomDetails(\''.$vall.'\')" class="friend-conversation1 active" id="pchat'.$row["user_id"].'">
                                        <img src="../profile-pic/'.$row["profilePicLink"].'"/>
                                        <div class="title-text">
                                            '.$row["first_name"].' '.$row["last_name"].'
                                        </div>
                                        <div class="setTime">
                                        </div>
                                        <div class="new-Message" id="lst-msg-'.$row["user_id"].'">
                                        </div>
                                        <div class="status-dot" id="onoff-'.$row["user_id"].'">'.$onlineicon.'</div>
                                    </div>';
                            }
                        ?>
                    </div>
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
          
            <div class="chat-body" id="chat-form">
            <div class="chat-message-list" id="pri-chat-message-list" style="max-height: 500px; overflow-y: scroll;">
                <!-- <div class="message-row your-message">
                                <div class="message-content">
                                    <div class="message-text">kkjfkg  fghh  ghfogi</div>
                                    <div class="message-time"></div>
                                </div>
                            </div> -->

                            <!-- <div class="message-row other-message">
                                <div class="message-content"> 
                                    <img src="../profile-pic/....."/>
                                    <div class="message-text">htht gtht ghth ggg</div>
                                    <div class="message-time"></div>
                                </div>
                            </div> -->
            </div>

            
                <form class="chat-form"  onkeydown="return event.key != 'Enter';">
                    <input type="hidden" id="senderId" name="senderId" value="<?php echo ''.$_SESSION["userid"].'';?>"> <!--sender id-->
                    <input type="hidden" id="msgType" name="msgType" value=""> <!-- set message type -->
                    <input type="hidden" id="username" name="username" value="<?php echo ''.$_SESSION["uname"].'';?>"> <!-- set username -->
                    <input type="hidden" id="propic" name="propic" value="<?php echo ''.$_SESSION['profileLink'].'';?>"> <!-- set profile pic -->

                    <!-- when select a public chat room -->
                    <input type="hidden" id="roomId" name="roomId" value=""> <!-- set room id -->
                    <input type="hidden" id="roomMemberId" name="roomMemberId" value=""> <!-- set room member id -->
                    <input type="hidden" id="roomname" name="roomname" value=""> <!-- set room name -->

                    <!-- when select a private group -->
                    
                    <!-- when select a friend -->
                    <input type="hidden" id="reseverId" name="reseverId" value=""> <!--reserver id-->
                    <input type="hidden" id="profilepiclink" name="profilepiclink" value=""> <!--profie pic link-->
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

            <div class= "pub-room-list" style="min-width: 400px; max-height: 225px; overflow-y: scroll;">
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
</body>
</html>

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
    
    // set reserved messages
        conn.onmessage = function(e) {
            console.log(e.data);
            var data = JSON.parse(e.data);
            if((data.msgType).localeCompare("pri") == 0){
                setReservedPrivatChatData(data);
                displayLastMsgOfuser(data);
            }
            else if((data.msgType).localeCompare("onoff") == 0){
                setOnlineOrOffline(data);
            }

            else if((data.msgType).localeCompare("pubg") == 0){
                set_received_pubg_msgs(data);
            }
            
            
        };

        // this used to send message when click send button of the chat area
        $("#send-msg").click(function(){
            var msg     = $("#msg").val();  // message
            var msgType = $("#msgType").val(); // message type

            // selected name of a friend, pubRoom, or group
            var titleName = document.getElementById("reserver-name").textContent; 
            
            if(msg == ""){ //ignore the empty msgs
                return;
            }
            if(titleName == ""){ //ignore the msg when the title is not set
                return;
            }

            //for public chat rooms
            var roomMemberId = $("#roomMemberId").val(); // get room member id
            var roomId = $("#roomId").val(); // get room id
            var username = $("#username").val(); // get the username
            var propic = $("#propic").val(); // get the profile picture
            var roomname = $("#roomname").val(); // get the room name

            //for private groups
            //
            //

            //for private chat
            var senderId   = $("#senderId").val(); // get sender id
            var reserverId = $("#reseverId").val(); // get reserver 

            //for public chat rooms
            if(roomId != null && roomMemberId != null){
                var data = {
                    msgType: msgType,
                    senderId: senderId,
                    username: username,
                    propic: propic,
                    roomId: roomId,
                    roomname: roomname,
                    roomMemberId: roomMemberId,
                    msg: msg
                };
                reserverId = "";
            }
            //for private chat
            if(reserverId != ""){
                var data = {
                    msgType: msgType,
                    senderId: senderId,
                    reserverId: reserverId,
                    msg: msg
                };
                roomId = "";
                roomMemberId = "";
            }

            console.log(data);
            conn.send(JSON.stringify(data)); // send data
            document.getElementById('msg').value = ''; // set chat field to empty
            reserverId = "";
            roomId = "";
            roomMemberId = "";

            // set sended chat message
            var row = '<div class="message-row your-message"><div class="message-content"><div class="message-text">'+ msg +'</div><div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row); // add to chat interface

        })
  
    // this method used to send userid to server to know about user
    function sendIntroduceData(){
        var introdata = {
            cliendId: <?php echo ''.$_SESSION["userid"].'';?>
        };
        conn.send(JSON.stringify(introdata));
    }

})

    // to set private chat reserved data
    function setReservedPrivatChatData(data){
        var propic = document.getElementById("profilepiclink").value;
        var title = document.getElementById("reserver-name").value;

        // set reserved chat message it tha chat was opend
        if(document.getElementById("reseverId").value == data.senderId){
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+propic+'"/> <div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);

            // this for set message as readed when this user load this message to chat window
            $.ajax({
                method: "POST",
                url: "../include/setPriMsgASRead.php",
                data: { msgid:data.msgDbId }
            });
        }
    }

    //to set received public chat room's msgs
    function set_received_pubg_msgs(data)
    {
        var propic = document.getElementById("propic").value;
        var roomId = document.getElementById("roomId").value;
        var title = document.getElementById("reserver-name").textContent;

        if(document.getElementById("senderId").value != data.senderId && title == data.roomname)
        {
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+data.propic+'"/> <div class = "username">'+ data.username +'</div><div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);
        }
    }

    // this method used to set chat room paramiters
    function setChatRoomDetails(val){
        var details = val.split(" ");

        var senderId   = $("#senderId").val(); // get sender id
        document.getElementById('pri-chat-message-list').innerHTML = ""; // clear chat area before start other chat
        document.getElementById("reseverId").value = details[0];
        document.getElementById("profilepiclink").value = details[3];
        document.getElementById("msgType").value = "pri";
        document.getElementById("reserver-name").textContent= details[1].concat(" ",details[2]);

        // remove last reserved message from user list
        var divid = 'lst-msg-'.concat(details[0]);
        document.getElementById(divid).innerHTML = "";

        // call to get privious messages from given users
        $.ajax({
            method: "POST",
            url: "../include/PrivatePreviousChat.php",
            data: { premsreq: "ok",
            reserver: details[0],
            sender: senderId 
            },
            success:function(result){
                var obj = JSON.parse(result);
                setPreviousMessages(obj);
            }
        });
    }

    // set private user onlie or offlien
    function  setOnlineOrOffline(data){
        var Status = '';
        if(data.statval == 1){
            Status = '<i class="fas fa-circle text-success"></i>';
        }
        else{
            Status = '<i class="fas fa-circle text-danger"></i>';
        }
        var id = '#onoff-'.concat(data.friendid);
        var divid = 'onoff-'.concat(data.friendid);
        document.getElementById(divid).innerHTML = "";
        $(id).append(Status);
        
    }

    // display last reserved message if that chat was not opend
    function displayLastMsgOfuser(data){
        if(document.getElementById("reseverId").value != data.senderId){
            var divid = 'lst-msg-'.concat(data.senderId);
            document.getElementById(divid).innerHTML = data.msg;
        }
    }

    // set previous messages in chat windows
    function setPreviousMessages(data){
        var propic = document.getElementById("profilepiclink").value;
        for (var i=0; i<data.length; i++) {
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+propic+'"/> <div class="message-text">'+ data[i] +'</div> <div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);
        }
    }
  
function setPubRoomData(roomData)
{
    document.getElementById("reserver-name").textContent = roomData.name;
    document.getElementById("msgType").value = "pubg";
    document.getElementById("roomId").value = roomData.id;
    document.getElementById('pri-chat-message-list').innerHTML = "";
    document.getElementById('roomname').value = roomData.name;
    /*
    //set private chat details null if a room selected
    document.getElementById("reserverId").value = "";
    document.getElementById("profilepiclink").value = "";*/

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
    var roomId = document.getElementById('roomId');

    $.ajax({
        method: "POST",
        url: "../public-rooms/ajax-handle.php",
        data: {
            new_member: "set",
            roomname: room,
            userid: <?php echo ''.$_SESSION["userid"].'';?>
        },
        success: function(result) {
            try{
                var res = JSON.parse(result);
            }catch(error){
                alert(result);
            }

            //if join successful send button visible, welcome message show
            if(res != "sqlerror"){
                var welcome ="Welcome to '" + room + "' chat room!";
                displayMsg(welcome, 1);
                document.getElementById("roomMemberId").value = res;
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
