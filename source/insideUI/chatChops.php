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
                
                <!-- dropdown menu for public chat rooms -->
                <div class= "final__dropdown" id = "dropdown" style= "visibility:hidden;">
                    <i class="fas fa-ellipsis-v" class ="final__dropdown__hover"></i> <!-- list icon -->
                    <div class= "final__dropdown__menu" id= "dropdown_list">
                        <div id="open-group-info" class= "open-popup-link">Group Info</div>
                        <div id="optional-dropdown">
                            <!--
                            <hr class= "hrr"><div id="open-admin-member-list" class= "open-popup-link">Members</div>
                            <hr class= "hrr"><div id="open-room-delete" class= "open-popup-link">Group Delete</div>
                            -->
                        </div>
                    </div>
                </div>

                <!-- group info popup --> 
                <div id="group-info" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p class = "modal-topic">Public Room Info</p><hr>
                        <div class= "grid-cont">
                            <div class ="room-info-display" class= "column" style="grid-column:1 / 2; grid-row: 1 / 2">
                                <img src= '' id= "roomicon" alt='group icon' width='200'height='200' class='img-circle room-icon'>
                                <br><span id="gi-roomname"></span>
                                <br><span id="gi-date"></span>
                                <br><br><span>Description - </span>
                                <br><span id="gi-bio"></span>
                            </div>

                            <div class= "admin-info-display" class= "column" style="grid-column:2 / 2; grid-row: 1 / 2">
                                <img src= '' id="adminpic" alt='admin pic' width='200'height='200' class='img-circle room-icon'>
								<br><span id="ai-fullname"></span>
                                <br><span id="ai-username"></span>
                                <br><span id="ai-date"></span>
								
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- members list popup --> 
                <div id="member-list" class="modal">
                    <div class="modal-content">
                        <p class = "modal-topic" id= "mem-count-show">Members</p><hr class= "hrr">

                        <div class= "mem-list" id="mem-list" style="max-height: 300px; overflow-y: scroll;">
                            <!-- sample member info -->
                            <!--
                            <div class= "mem-item">
                                <div class="col1">
                                    <img src= '../profile-pic/rashmi.png' width='50'height='50' class='img-circle mem-icon' style="grid-column:1 / 2; grid-row: 1 / 2">
                                </div>
                                <div class="col2">
                                    <div class= "mem-fullname">rashmi wijesekara</div>
                                    <div class= "mem-username">#rashmi</div>
                                </div>
                            </div>
                            <hr class="hrr"> -->
                        </div>
                    </div>
                </div>

                <!-- exit group popup --> 
                <div id="exit-room" class="modal">
                    <div class="modal-content">
                        <p class = "modal-topic">Exit Group</p><hr>
                        <div class= "leave-msg">
                            Are you sure you want to leave this chat room?
                        </div>

                        <div id= "leave-room-btn" onclick = "room_dropdown_menu(3)">Leave</div>
                        
                    </div>
                </div>
                
                <!-- delete chat room popup --> 
                <div id="delete-room" class="modal">
                    <div class="modal-content">
                        <p class = "modal-topic">Delete Chat Room</p><hr>
                        <div class= "delete-room-msg">
                            No one will able to chat on this room anymore.<br>
                            Are you sure you want to delete this chat room?
                        </div>

                        <div id= "delete-room-btn" onclick = "room_dropdown_menu(4)">Delete Room</div>
                        
                    </div>
                </div>

                <!-- member list for the admin popup -->
                <div id="admin-member-list" class="modal">
                    <div class="modal-content">
                        <p class = "modal-topic" id= "admin-mem-count-show">Members</p><hr class= "hrr">

                        <div class= "mem-list" id="admin-mem-list" style="max-height: 300px; overflow-y: scroll;">
                            <!-- sample member info -->
                            <!--
                            <div class= "mem-item">
                                <div class="col11">
                                    <img src= '../profile-pic/rashmi.png' width='50'height='50' class='img-circle mem-icon' style="grid-column:1 / 2; grid-row: 1 / 2">
                                </div>
                                <div class="col22">
                                    <div class= "mem-fullname">rashmi wijesekara</div>
                                    <div class= "mem-username">#rashmi</div>
                                </div>
                                <div class= "col33">
                                    remove button
                                </div>
                            </div>
                            <hr class="hrr"> -->
                        </div>
                    </div>
                </div>

                <script>
                    var modal1 = document.getElementById("group-info");
                    var open1 = document.getElementById("open-group-info");
                    var span = document.getElementsByClassName("close")[0];

                    var modal2 = document.getElementById("member-list");
                    var modal3 = document.getElementById("exit-room");
                    var modal4 = document.getElementById("admin-member-list");
                    var modal5 = document.getElementById("delete-room");

                    open1.onclick = function() {
                        var res = room_dropdown_menu(1);
                        
                        if(res != 0){
                            modal1.style.display = "block";
                        }else{
                            alert("Something went wrong! Try again later.");
                        }
                        
                    }

                    span.onclick = function() {
                        modal1.style.display = "none";
                    }
                    window.onclick = function(event) {
                        if (event.target == modal1 || event.target == modal2 || event.target == modal3 || event.target == modal4 || event.target == modal5) {
                            modal1.style.display = "none";
                            modal2.style.display = "none";
                            modal3.style.display = "none";
                            modal4.style.display = "none";
                            modal5.style.display = "none";
                        }
                    }

                </script>
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
                    <input type="hidden" id="roomicon" name="roomicon" value=""> <!-- set room icon -->

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

                        $id = $roomname."count";
                        echo "<div onclick= 'setPubRoomData($roomDataJSON)' class= 'room active' id = '$roomname'>
                        <img src= '../group-icons/$icon' alt='group icon' width='35'height='35' class='img-circle pro-img'>&emsp; 
                        $roomname
                        <span class= 'memcount' id = '$id' style='float: right'>$memCount Members</span>
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
var conn;

$(document).ready(function(){
    conn = new WebSocket('ws://localhost:8080');
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
            else if((data.msgType).localeCompare("pubg-user-remove") == 0){
                pubg_user_remove_notification(data);
            }
            else if((data.msgType).localeCompare("memCount-update-req") == 0){
                member_count_update_on_user_side(data.room);
            }
            
        };

        // this used to send message when click send button of the chat area
        $("#send-msg").click(function(){
            var msg     = $("#msg").val();  // message
            var msgType = $("#msgType").val(); // message type
            var senderId   = $("#senderId").val(); // get sender id

            // selected name of a friend, pubRoom, or group
            var titleName = document.getElementById("reserver-name").textContent; 
            
            if(msg == ""){ //ignore the empty msgs
                return;
            }
            if(titleName == ""){ //ignore the msg when the title is not set
                return;
            }

            if(msgType == "pri"){
                var reserverId = $("#reseverId").val(); // get reserver 

                if(reserverId != ""){
                    var data = {
                        msgType: msgType,
                        senderId: senderId,
                        reserverId: reserverId,
                        msg: msg
                    };
                }
            }
            if(msgType == "pubg"){
                var roomMemberId = $("#roomMemberId").val(); // get room member id
                var roomId = $("#roomId").val(); // get room id
                var username = $("#username").val(); // get the username
                var propic = $("#propic").val(); // get the profile picture
                var roomname = $("#roomname").val(); // get the room name

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
                }
            }

            //for private groups
            //
            //

            console.log(data);
            conn.send(JSON.stringify(data)); // send data
            document.getElementById('msg').value = ''; // set chat field to empty

            // set sended chat message
            var row = '<div class="message-row your-message"><div class="message-content"><div class="message-text">'+ msg +'</div><div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row); // add to chat interface

            autoScrollDown();
        })
  
    // this method used to send userid to server to know about user
    function sendIntroduceData(){
        var introdata = {
            cliendId: <?php echo ''.$_SESSION["userid"].'';?>
        };
        conn.send(JSON.stringify(introdata));
    }

})

//user remove notification received from the server
function pubg_user_remove_notification(data)
{
    console.log(data);
    var room;

    var myname = document.getElementById("username").value;
    var myid = document.getElementById("roomMemberId").value;
    var reserv = document.getElementById("reserver-name").textContent;

    $.ajax({
        method: "POST",
        url: "../public-rooms/ajax-handle.php",
        data: {
            get_room_name: "set",
            roomid: data.room_id
        },
        success: function(result){
            room = JSON.parse(result);
            member_count_update_on_user_side(room);

            if(data.member_id == myid) 
            { // this is the admin who removed that user
                var msg = "You have removed a member";
                displayMsg(msg, 0);
            }   
            if(data.member_name == myname && room == reserv) 
            { // this is the removed user
                var msg = "You were removed by the admin";
                displayMsg(msg, 0);
                pubRoom_join_sendMsg_select("member-remove"); //hide the msg sending and joining buttons
            }
        }
    }); 
}

//set chat room info into popup window
function room_dropdown_menu(option)
{
    // ajax call and get relavent data to display
    // set got data
    //modal make display
    var roomid = document.getElementById("roomId").value;
    var memberid = document.getElementById("roomMemberId").value;
    var title = document.getElementById("reserver-name").textContent;

    //admin info
    var fullname = document.getElementById("ai-fullname").textContent;
    var username = document.getElementById("ai-username").textContent; 
    var admindate = document.getElementById("ai-date").textContent;
    var adminpic = document.getElementById("adminpic").src;

    if(option == 1)
    {   //set chat room info
        $.ajax({
            method: "POST",
            url: "../public-rooms/ajax-handle.php",
            data: {
                group_info: "set",
                roomid: roomid
            },
            success: function(result){
                //group info option
                document.getElementById("gi-roomname").textContent = "Room Name - "+ title;

                var obj = JSON.parse(result);
                if(obj == 0 || obj == "sqlerror"){
                    return 0;
                }
                document.getElementById("gi-date").textContent = "Created on - "+ obj.created_date_and_time.substring(0,10);
                document.getElementById("gi-bio").textContent = obj.bio;
                document.getElementById("roomicon").src= '../group-icons/'+ obj.icon_link;

                $.ajax({
                    method: "POST",
                    url: "../public-rooms/ajax-handle.php",
                    data: {
                        admin_info: "set",
                        roomid: roomid
                    },
                    success: function(result){
                        //admin info
                        var obj = JSON.parse(result);
                        if(obj == 0 || obj == "sqlerror"){
                            return 0;
                        }
                        document.getElementById("ai-fullname").textContent = "Admin Name - "+ obj.first_name + " "+ obj.last_name;
                        document.getElementById("ai-username").textContent = "Username - "+ obj.username; 
                        document.getElementById("ai-date").textContent = "Joined on - "+ obj.created_time.substring(0,10);
                        document.getElementById("adminpic").src = "../profile-pic/"+ obj.profilePicLink;
                        return 1;
                    }
                });
            }
        });
    }
    else if(option == 2)
    {   //set member list
        $.ajax({
            method: "POST",
            url: "../public-rooms/ajax-handle.php",
            data: {
                member_list: "set",
                roomid: roomid
            },
            success: function(result){
                //member list display

                var obj = JSON.parse(result);
                if(obj == 0 || obj == "sqlerror"){
                    return 0;
                }
                document.getElementById("mem-list").innerHTML = "";
                document.getElementById("mem-count-show").textContent = "Members";

                var i=0;
                while(obj[i]){
                    var member = `<div class= "mem-item">
                                    <div class="col1">
                                        <img src= '../profile-pic/`+ obj[i].propic+ `' width='55'height='55' class='img-circle mem-icon' style="grid-column:1 / 2; grid-row: 1 / 2">
                                    </div>
                                    <div class="col2">
                                        <div class= "mem-fullname">`+ obj[i].fname+ " "+ obj[i].lname+ `</div>
                                        <div class= "mem-username">#`+ obj[i].username+ `</div>
                                    </div>
                                </div>
                                <hr class="hrr">`;
                    $("#mem-list").append(member);
                    i++;
                }
                var count = i;
                $("#mem-count-show").append("   ("+ count + ")");
                return 1;
            }
        });

    }
    else if(option == 3)
    {   //leave the chat room
        $.ajax({
            method: "POST",
            url: "../public-rooms/ajax-handle.php",
            data: {
                leave_room: "set",
                roomid: roomid,
                memberid: memberid
            },
            success: function(result){
                var obj = JSON.parse(result);
                if(obj == 0 || obj == "sqlerror"){
                    return 0;
                }else if(obj == 1){
                    var msg = "You have left the '"+ title + "' chat room";
                    document.getElementById("exit-room").style.display = "none";
                    displayMsg(msg, 0);

                    //send button, dropdown hide
                    //join button show
                    document.getElementById("send-msg").style.visibility = "hidden";
                    document.getElementById("dropdown").style.visibility = "hidden";
                    document.getElementById("join-room-btn").style.visibility = "visible";

                    var room = document.getElementById("reserver-name").textContent;
                    //update the member count on the user side
                    member_count_update_on_user_side(room);

                    var datas = {
                                msgType: "memCount-update-req",
                                room: room
                            };
                    conn.send(JSON.stringify(datas));
                }


            }
        });    
    }
    else if(option == 4)
    {   //member list for admins (member remove)
        $.ajax({
            method: "POST",
            url: "../public-rooms/ajax-handle.php",
            data: {
                member_list: "set",
                roomid: roomid
            },
            success: function(result){
                //member list display

                var obj = JSON.parse(result);
                if(obj == 0 || obj == "sqlerror"){
                    return 0;
                }
                document.getElementById("admin-mem-list").innerHTML = "";
                document.getElementById("admin-mem-count-show").textContent = "Members";
                var user1 = document.getElementById("username").value;
                
                var i=0;
                var count=0;
                while(obj[i]){
                    var user2 = obj[i].username;
                    var idss = "remove"+ user2;

                    if(user1.localeCompare(user2) != 0){
                        var member = `<div class= "mem-item">
                                        <div class="col11">
                                            <img src= '../profile-pic/`+ obj[i].propic+ `' width='55'height='55' class='img-circle mem-icon' style="grid-column:1 / 3; grid-row: 1 / 3">
                                        </div>
                                        <div class="col22">
                                            <div class= "mem-fullname">`+ obj[i].fname+ " "+ obj[i].lname+ `</div>
                                            <div class= "mem-username">#`+ obj[i].username+ `</div>
                                        </div>
                                        <div class= "col33">
                                            <div class= "room-member-remove-btn" id="`+ idss +`" onclick= "user_remove('`+user2+`')" style="visibility: visible;">Remove</div>
                                        </div>
                                    </div>
                                    <hr class="hrr">`;
                        $("#admin-mem-list").append(member);
                        count++;
                    }
                    i++;
                }
                $("#admin-mem-count-show").append("   ("+ count + ")");
                return 1;
            }
        });

    }
    else if(option == 5)
    {   //admin delete the chat room


    }
    else{
        return 0;
    }
    
}

//public room admin removes members from the chat room
function user_remove(username){
    //alert("User removed: " + username);
    /************************************** */ 
    
    //var senderId   = $("#senderId").val(); // get sender id
    var roomid = $("#roomId").val(); // get room id
    var roomMemberId = $("#roomMemberId").val(); // get member id

    document.getElementById("admin-member-list").style.display = "none";

    var data = {
                    msgType: "pubg-user-remove",
                    room_id: roomid,
                    member_id: roomMemberId,
                    member_name: username
                };

    conn.send(JSON.stringify(data)); // send data
}

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

    //this room is selected to the chat UI
    if(document.getElementById("senderId").value != data.senderId && title == data.roomname)
    {
        var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+data.propic+'"/> <div class = "username">'+ data.username +'</div><div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
        $('#pri-chat-message-list').append(row);

        autoScrollDown();
    }
}

//to set previous messages
function set_prev_pubg_msgs(data)
{
    var title = document.getElementById("reserver-name").textContent;
    var sender = document.getElementById("senderId").value;
    //this room is selected to the chat UI
    if(sender != data.senderId && title == data.roomname)
    {
        var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+data.propic+'"/> <div class = "username">'+ data.username +'</div><div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
        $('#pri-chat-message-list').append(row);

    }else if(sender == data.senderId && title == data.roomname)
    {
        // set sended chat message
        var row = '<div class="message-row your-message"><div class="message-content"><div class="message-text">'+ data.msg +'</div><div class="message-time"></div></div></div>';
        $('#pri-chat-message-list').append(row);
    }
    autoScrollDown();
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

    //set pubRoom data null
    document.getElementById("roomId").value = null;
    document.getElementById("roomMemberId").value = null;

    //change visibilities
    document.getElementById("dropdown").style.visibility = "hidden";
    document.getElementById("send-msg").style.visibility = "visible";
    document.getElementById("join-room-btn").style.visibility = "hidden";
    
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

//auto scroll down when send button is pressed
function autoScrollDown(){
    $("#pri-chat-message-list").scrollTop($("#pri-chat-message-list").prop('scrollHeight'));
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

//set public chat room data
function setPubRoomData(roomData)
{
    document.getElementById("reserver-name").textContent = roomData.name;
    document.getElementById("msgType").value = "pubg";
    document.getElementById("roomId").value = roomData.id;
    document.getElementById('pri-chat-message-list').innerHTML = "";
    document.getElementById('roomname').value = roomData.name;
    
    //set private chat details null if a room selected
    document.getElementById("reseverId").value = "";
    document.getElementById("profilepiclink").value = "";

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
            var d = pubRoom_join_sendMsg_select(res);
            optional_dropdown_update(d, res);
            
            //load the previous messages in the chat room
            $.ajax({
                method: "POST",
                url: "../public-rooms/ajax-handle.php",
                data: {
                    prev_msgs: "set",
                    roomid: roomData.id,
                    userid: <?php echo ''.$_SESSION["userid"].'';?>
                },
                success: function(result){
                    var res = JSON.parse(result);
                    
                    var i=0;
                    while(res[i]){
                        //console.log(res[i]);
                        set_prev_pubg_msgs(res[i]);
                        i++;
                    }
                }
            });
        }
    });
}

//optional dropdown menu update
function optional_dropdown_update(option, memberid)
{
    //dropdown made visible
    if(option == 1){
        //clear the optional dropdown menu
        document.getElementById("optional-dropdown").innerHTML = "";

        $.ajax({
            method: "POST",
            url: "../public-rooms/ajax-handle.php",
            data: {
                find_admin: "set",
                member_id : memberid
            },
            success: function(result){
                var c = JSON.parse(result);
                if(c == 1){ //this user is the admin
                    var row = `<hr class= "hrr"><div id="open-admin-member-list" class= "open-popup-link">Members</div>
                                <hr class= "hrr"><div id="open-room-delete" class= "open-popup-link">Group Delete</div>`;
                    $('#optional-dropdown').append(row);

                    var open4 = document.getElementById("open-admin-member-list");
                    var modal4 = document.getElementById("admin-member-list");

                    var open5 = document.getElementById("open-room-delete");
                    var modal5 = document.getElementById("delete-room");

                    open4.onclick = function() {
                        var res = room_dropdown_menu(4);
                        if(res != 0){
                            modal4.style.display = "block";
                        }else{
                            alert("Something went wrong! Try again later.");
                        }
                    }
                    open5.onclick = function() {
                        modal5.style.display = "block";
                    }

                }
                else{ //this user is not an admin
                    var row = `<hr class= "hrr"><div id="open-member-list" class= "open-popup-link">Members</div>
                                <hr class= "hrr"><div id="open-exit-room" class= "open-popup-link">Exit Group</div>`;

                    $('#optional-dropdown').append(row);

                    var open2 = document.getElementById("open-member-list");
                    var open3 = document.getElementById("open-exit-room");

                    var modal2 = document.getElementById("member-list");
                    var modal3 = document.getElementById("exit-room");

                    open2.onclick = function() {
                        var res = room_dropdown_menu(2);
                        if(res != 0){
                            modal2.style.display = "block";
                        }else{
                            alert("Something went wrong! Try again later.");
                        }
                    }

                    open3.onclick = function() {
                        modal3.style.display = "block";
                    }
                }
                
            }
        });
    }
}

//change the button visibility based on membership status
function pubRoom_join_sendMsg_select(result)
{
    var sendButton = document.getElementById('send-msg');
    var joinButton = document.getElementById('join-room-btn');
    var roomMemberId = document.getElementById("roomMemberId");
    var dropdown = document.getElementById('dropdown');

    if(result == "0"){ // not a member
        sendButton.style.visibility = 'hidden';
        joinButton.style.visibility = 'visible';
        dropdown.style.visibility = 'hidden';
        roomMemberId.value = null;
    }
    else if(result == "sqlerror"){ // error
        alert("Something went wrong");
        sendButton.style.visibility = 'visible';
        joinButton.style.visibility = 'hidden';
        dropdown.style.visibility = 'hidden';
        roomMemberId.value = null;
    }
    else if(result == "member-remove"){
        joinButton.style.visibility = 'hidden';
        dropdown.style.visibility = 'hidden';
        sendButton.style.visibility = 'hidden';
        roomMemberId.value = null;
    }
    else if(result == "-1"){ // a removed user
        joinButton.style.visibility = 'hidden';
        dropdown.style.visibility = 'hidden';
        sendButton.style.visibility = 'hidden';
        roomMemberId.value = null;

        var msg = "You were removed by the admin";
        displayMsg(msg, 0);
    }
    else{ // a member
        sendButton.style.visibility = 'visible';
        dropdown.style.visibility = 'visible';
        joinButton.style.visibility = 'hidden';
        roomMemberId.value = result;
        return 1;
    }
    return 0;
}

//new member join to a public chat room
function pubRoom_join()
{
    var room = document.getElementById("reserver-name").textContent;
    var sendButton = document.getElementById('send-msg');
    var joinButton = document.getElementById('join-room-btn');
    var dropdown = document.getElementById('dropdown');
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
                document.getElementById('dropdown').style.visibility = 'visible';

                optional_dropdown_update(1, res);

                //update the member count on the user side
                member_count_update_on_user_side(room);
                
                var datas = {
                                msgType: "memCount-update-req",
                                room: room
                            };
                conn.send(JSON.stringify(datas));
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

//member count of public room update on user side
function member_count_update_on_user_side(roomname)
{
    $.ajax({
        method: "POST",
        url: "../public-rooms/ajax-handle.php",
        data: {
            mem_count_update: "set",
            roomname: roomname
        },
        success: function(result) {
            var res = JSON.parse(result);
            var id = roomname + "count";
            var newCount = res + " Members";
            document.getElementById(id).textContent = newCount;
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
    }else{
        alertmsg.style.backgroundColor = "#21cc63";
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

function admin_menu()
{

}

</script>

<!--redirect from create new chat room-->
<?php

if(isset($_POST['status'])){
    if($_POST['status'] == 'ok'){
        $name = $_POST['roomname'];
        echo "<script>
            displayMsg('You have successfully created $name', 1);
        </script>";
    }
    else if($_POST['status'] == 'wrong'){
        echo "<script>
            displayMsg('Something went wrong', 0);
        </script>";
    }
    unset($_POST['status']);
}
?>
