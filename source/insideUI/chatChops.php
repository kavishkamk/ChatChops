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
                <div class= "cols">
                    <div class= "col-1">
                        <span id="reserver-name"></span> <!-- to set reserver name -->
                    </div>
                    
                    <!-- public room join button -->
                    <div class= "col-2">
                        <button type="button" onclick= 'pubRoom_join()' class="join-room" id="join-room-btn" style="visibility:hidden;">Join</button>
                    </div>
                </div>
                
                <!-- dropdown menu for public chat rooms -->
                <div class= "final__dropdown" id = "dropdown"  style= "visibility:hidden;">
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
                        <p class = "modal-topic" id= "modal-topic"></p><hr>
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

                <!-- exit room popup --> 
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

                        <div id= "delete-room-btn" onclick = "room_dropdown_menu(5)">Delete Room</div>
                        
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
                
                <!-- exit group popup --> 
                <div id="exit-group" class="modal">
                    <div class="modal-content">
                        <p class = "modal-topic">Exit Group</p><hr>
                        <div class= "leave-msg">
                            Are you sure you want to leave this group?
                        </div>

                        <div id= "leave-room-btn" onclick = "private_group_dropdown(3)">Leave</div>
                        
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

                    var modal6 = document.getElementById("exit-group");

                    open1.onclick = function() {

                        // a public chat room is selected
                        if(document.getElementById("roomId").value != ""){
                            document.getElementById("modal-topic").textContent = "Public Room Info";
                            var res = room_dropdown_menu(1);
                        
                            if(res != 0){
                                modal1.style.display = "block";
                            }else{
                                alert("Something went wrong! Try again later.");
                            }
                        }
                        
                        // a private group is selected
                        if(document.getElementById("group-id").value != ""){
                            document.getElementById("modal-topic").textContent = "Private Group Info";
                            var res = private_group_dropdown(1);
                        
                            if(res != 0){
                                modal1.style.display = "block";
                            }else{
                                alert("Something went wrong! Try again later.");
                            }
                        }
                    }

                    span.onclick = function() {
                        modal1.style.display = "none";
                    }
                    window.onclick = function(event) {
                        if (event.target == modal1 || event.target == modal2 || event.target == modal3 || 
                        event.target == modal4 || event.target == modal5 || event.target == modal6) {
                            modal1.style.display = "none";
                            modal2.style.display = "none";
                            modal3.style.display = "none";
                            modal4.style.display = "none";
                            modal5.style.display = "none";
                            modal6.style.display = "none";
                        }
                    }

                </script>
                
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
                    <input type="hidden" id="room-list-status" name="roomListStatus" value="">
                    
                    <!-- when select a private group -->
                    <input type="hidden" id="group-id" name="group-id" value="">
                    <input type="hidden" id="group-name" name="group-name" value=""> <!-- group name -->
                    <input type="hidden" id="created-on" name="created-on" value="">
                    <input type="hidden" id="bio" name="bio" value="">
                    <input type="hidden" id="group-icon" name="group-icon" value="">
                    <input type="hidden" id="member-id" name="member-id" value="">

                    <input type="hidden" id="admin-userid" name="admin-userid" value=""> <!-- admin's user id -->
                    <input type="hidden" id="member-userids" name="member-userids" value=""> <!-- selected user ids -->

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
            <div class="first-line">
                <div class= "topic" >
                    <p>Private Groups</p>
                </div>

                <div class = "create-grp-btn">
                    <a href="../private-groups/create-private-group.php">
                    <button id= "grp">Create Group</button>
                    </a>
                </div>
            </div>
            
            <div class= "prig-list" id= "prig-list" style="height: 40vh; overflow-y: scroll;">
                <!-- private chat groups list 
                <div class= "friend-conversation1 active">
                    <img src="../group-icons/groupchat-icon.png" alt='group icon'/>
                    <div class= "title-text">My chat Group</div>
                    <span class= 'memcount' style='float: right'>3 Members</span>
                </div>
                -->
            </div>
        </div>
        
        <div class="chat" id="public-group" style="grid-column:3 / 4; grid-row: 2 / 3">
            <div class= "first-line">
                <div class= "topic">
                    Public Chat Rooms
                </div>

                <div class = "create-grp-btn">
                    <a href="../public-rooms/create-pub-room.php">
                    <button id= "rm">Create Room</button>
                    </a>
                </div>
            </div>
            

            <div class= "pub-room-list" id= "pub-room-list" style="max-height: 260px; overflow-y: scroll;">
                <?php

                include_once "../public-rooms/displayRoomList.class.php";
                $roomObj = new displayRoomList();
                $count = $roomObj->roomCount();
                
                $roomname;
                
                if($count == 0){
                    //there is no any chat rooms available
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

                        /*
                        <img src="../group-icons/groupchat-icon.png" alt='group icon'/>
                <div class= "title-text">My chat Room</div>
                <span class= 'memcount' style='float: right'>3 Members</span>*/

                        $id = $roomname."count";

                        if($memCount == 1) $mem = $memCount. ' Member';
                        else $mem = $memCount. ' Members';

                        echo "<div onclick= 'setPubRoomData($roomDataJSON)' class= 'friend-conversation1 active' id = '$roomname'>
                        <img src= '../group-icons/$icon' alt='group icon'>
                        <div class= 'title-text'>$roomname</div>
                        <span class= 'memcount' id = '$id' style='float: right'>$mem</span>
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

<script type="text/javascript">
var conn;

$(document).ready(function(){
    conn = new WebSocket('ws://localhost:8080');

    load_group_list();

    conn.onopen = function(e) {
        console.log("Connection established!");
        sendIntroduceData(); // send data to user introduce

        //if the user is created a new chat room the roomlist should be updated for others too
        check_to_update_room_list();

        //if the user is created a new private group, 
        //the group list should be updated for others too
        check_to_update_group_list();
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
            else if((data.msgType).localeCompare("delete-room") == 0){
                pubRoom_delete_notification(data);
            }
            else if((data.msgType).localeCompare("update-room-list") == 0){
                update_room_list();
            }
            else if((data.msgType).localeCompare("new-grp-add-to-list-req") == 0){
                load_group_list();
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

    //send a msg to the server when a new chat room was created
    //then the server will broadcast the message asking them to update the roomlist
    function check_to_update_room_list()
    {
        var st = document.getElementById("room-list-status").value;

        if(st == 'ok'){
            update_room_list_broadcast();
        }
    }

    function check_to_update_group_list()
    {
        var str = document.getElementById("member-userids").value;

        if(str != ""){
            update_group_list(str);
        }
    }

})

// send the message to update the private group list to the members
function update_group_list(memlist)
{
    memlist = memlist + "";
    var temp = new Array();
    temp = memlist.split(",");

    for (a in temp){ 
        //store the userids as base 10 integers instead of strings
        temp[a] = parseInt(temp[a], 10); 
    }

    data = {    msgType: "new-grp-add-to-list-req",
                memlist: temp
            };   

    conn.send(JSON.stringify(data)); // send data
}


//load the group list
function load_group_list()
{
    var userid = document.getElementById("senderId").value;

    //empty the previous list and load the new list again
    document.getElementById("prig-list").innerHTML = "";

    $.ajax({
        method: "POST",
        url: "../private-groups/ajax-handle.php",
        data: {
            load_group_list: "set",
            userid: userid
        },
        success: function(result){
            var obj = JSON.parse(result);
            console.log(obj);

            var i=0;
            while(obj[i]){
                var group = obj[i];
                grp = JSON.stringify(group);

                var id = group.group_id + "memcount";
                var id2 = group.group_name + "prig";

                var datas = `<div onclick='set_private_group_data(`+ grp +`)' id= "`+id2+`" class= "friend-conversation1 active">
                    <img src="../private-group-icons/`+group.icon+`" alt='group icon'/>
                    <div class= "title-text">`+group.group_name+`</div>
                    <span class= 'memcount' id='`+id+`' style='float: right'></span>
                </div>`;

                $('#prig-list').append(datas);
                set_member_count(group.group_id);
                i++;
            }
        }
    });
}

// set selected private group data
function set_private_group_data(data)
{
    console.log(data);

    //set the active chat's color in the list
    //document.getElementById(data.group_name).style.backgroundColor = "white";

    //set private chat details null if a group selected
    document.getElementById("reseverId").value = "";
    document.getElementById("profilepiclink").value = "";

    //set pubRoom data null
    document.getElementById("roomId").value = null;
    document.getElementById("roomMemberId").value = null;

    //set private group data
    document.getElementById("reserver-name").textContent = data.group_name; // set the chat title
    document.getElementById("msgType").value = "prig";
    document.getElementById('pri-chat-message-list').innerHTML = ""; //chat clear
    document.getElementById('dropdown').style.visibility = 'visible'; //dropdown menu hide
    document.getElementById('optional-dropdown').innerHTML = ""; //optional dropdown menu clear

    document.getElementById("group-name").value = data.group_name;
    document.getElementById("group-id").value = data.group_id ;
    document.getElementById("created-on").value = data.created ;
    document.getElementById("bio").value = data.bio ;
    document.getElementById("group-icon").value = data.icon ;
    document.getElementById("member-id").value = data.member_id;
    $.ajax({
        method: "POST",
        url: "../private-groups/ajax-handle.php",
        data: {
            check_admin: "set",
            member_id: data.member_id
        },
        success: function(result){
            var res = JSON.parse(result);
            //admin or a member
            
            if(res == 1){   //admin

            }
            else if(res == 0){ //member
                var row = `<hr class= "hrr"><div id="open-member-list" class= "open-popup-link">Members</div>
                            <hr class= "hrr"><div id="open-exit-group" class= "open-popup-link">Exit Group</div>`;

                $('#optional-dropdown').append(row);

                var open2 = document.getElementById("open-member-list");
                var open3 = document.getElementById("open-exit-group");

                var modal2 = document.getElementById("member-list");
                var modal3 = document.getElementById("exit-group");

                open2.onclick = function() {
                    var res = private_group_dropdown(2);
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
            else{
                displayMsg("Error!", 0);
                return;
            }

            //for all members

        }
    });


    /**
    
    if admin ==>    member popup with add remove buttons
                    delete group

    if member ==>   member list viewing popup
                    exit group popup
    
    for all ==>     group info
                    last 100 msgs loading
    
    */
}

// set private group info into popup windows
function private_group_dropdown(option)
{
    var groupid = document.getElementById("group-id").value;
    var groupname = document.getElementById("group-name").value;
    var icon = document.getElementById("group-icon").value;
    var bio = document.getElementById("bio").value;
    var created = document.getElementById("created-on").value;
    var memberid = document.getElementById("member-id").value;

    if(option == 1)
    {   // set group info popup
        
        //group info setting
        document.getElementById("gi-roomname").textContent = "Group Name - "+ groupname;
        document.getElementById("gi-date").textContent = "Created on - "+ created.substring(0,10);
        document.getElementById("gi-bio").textContent = bio;
        document.getElementById("roomicon").src= '../private-group-icons/'+ icon;

        //admin info setting
        var fullname = document.getElementById("ai-fullname").textContent;
        var username = document.getElementById("ai-username").textContent; 
        var admindate = document.getElementById("ai-date").textContent;
        var adminpic = document.getElementById("adminpic").src;

        $.ajax({
            method: "POST",
            url: "../private-groups/ajax-handle.php",
            data: {
                admin_data: "set",
                group_id: groupid
            },
            success: function(result){
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
    else if(option == 2)
    {   // set the member list popup
        $.ajax({
            method: "POST",
            url: "../private-groups/ajax-handle.php",
            data: {
                member_list: "set",
                group_id: groupid
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
    {   // leave the group
        $.ajax({
            method: "POST",
            url: "../private-groups/ajax-handle.php",
            data: {
                leave_group: "set",
                group_id: groupid,
                member_id: memberid
            },
            success: function(result){
                var obj = JSON.parse(result);
                if(obj == 0 || obj == "sqlerror"){
                    return 0;
                }else if(obj == 1){
                    var msg = "You have left the '"+ groupname + "' group";
                    document.getElementById("exit-group").style.display = "none";
                    displayMsg(msg, 0);

                    //dropdown hide
                    document.getElementById("dropdown").style.visibility = "hidden";
                    document.getElementById("reserver-name").textContent = "";
                    document.getElementById("pri-chat-message-list").innerHTML = ""; // clear chat area
                    /*
                    member_count_update_on_user_side(room);

                    var datas = {
                                msgType: "prig-memCount-update-req",
                                group: room
                            };
                    conn.send(JSON.stringify(datas));
                    */
                }
            }
        });
    }
    else if(option == 4)
    {   // member list for admins
        
    }
    else if(option == 5)
    {   // admin delete the group

    }
    else{
        return 0;
    }
}

// get the member count of a given private group
function set_member_count(grpid)
{
    $.ajax({
        method: "POST",
        url: "../private-groups/ajax-handle.php",
        data: {
            member_count: "set",
            group_id: grpid
        },
        success: function(result){
            count = JSON.parse(result);
            var id = grpid + "memcount";
            var newCount;

            if(count == 1) newCount = count + " Member";
            else newCount = count + " Members";

            document.getElementById(id).textContent = newCount;
        }
    }); 
}

//a public room was deleted by the admin user
function pubRoom_delete_notification(data){
    console.log(data);

    var myid = document.getElementById("roomMemberId").value;
    var reserv = document.getElementById("roomname").value;

    // this is the admin user
    if(myid == data.admin_member_id){
        var msg = "You have deleted the '"+ data.roomname +"' chat room!";
        displayMsg(msg, 0);

        document.getElementById("reserver-name").textContent = "";
        pubRoom_join_sendMsg_select("delete-room");
        document.getElementById('pri-chat-message-list').innerHTML = ""; // clear chat area
    }
    // this is a member of the room who selected that as the active chat title
    else if(reserv == data.roomname && myid != data.admin_member_id){
        var msg = "'"+ data.roomname+"' chat room is deleted by the admin!";
        displayMsg(msg, 0);

        document.getElementById("reserver-name").textContent = "";
        pubRoom_join_sendMsg_select("delete-room");
        document.getElementById('pri-chat-message-list').innerHTML = ""; // clear chat area
    }

    //chat room list update for all the users
    update_room_list();

}

//public room list update
function update_room_list()
{
    $("#pub-room-list").empty(); // clear the room list
    
    $.ajax({    // get the total count of public rooms available
        method: "POST",
        url: "../public-rooms/ajax-handle.php",
        data: {
            get_pubg_count: "set"
        },
        success: function(result){
            var count = JSON.parse(result);
            
            if(count == "sqlerror"){
                alert("Something went wrong");
            }
            else if(count > 0){
                $.ajax({
                    method: "POST",
                    url: "../public-rooms/ajax-handle.php",
                    data: {
                        get_pubg_list: "set"
                    },
                    success: function(res){
                        var roomlist = JSON.parse(res);
                        
                        for(var i=0; i< count; i++){
                            var roomData = roomlist[i];
                            var roomname = roomData.name;
                            var icon = roomData.icon;

                            console.log(roomData);
                            
                            var roomid = roomData.id;
                            var id = roomname + "count";
                            
                            var data = {"id": roomid, 
                                        "name": roomname,
                                        "time": roomData.time,
                                        "bio": roomData.bio,
                                        "icon": icon
                                        };
                            
                            var datas = JSON.stringify(data);
                            var room = `<div onclick='setPubRoomData(`+datas+`)' class= 'friend-conversation1 active' id = '`+roomname+`'>
                                            <img src= '../group-icons/`+icon+`' alt='group icon'>
                                            <div class= 'title-text'>`+roomname+`</div>
                                            <span class= 'memcount' id = '`+id+`' style='float: right'>`+0+` Members</span>
                                        </div>`;
                            $('#pub-room-list').append(room);
                            
                            member_count_update_on_user_side(roomname);
                        }
                    }
                });
            }
        }
    });
}

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
                document.getElementById("modal-topic").textContent = "Public Room Info";
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
      
        document.getElementById("delete-room").style.display = "none";
        var roomid = $("#roomId").val(); // get room id
        var roomMemberId = $("#roomMemberId").val(); // get member id
        var roomname = document.getElementById("roomname").value;

        var data = {
                msgType: "delete-room",
                room_id : roomid,
                admin_member_id : roomMemberId,
                roomname: roomname
        };
        conn.send(JSON.stringify(data)); // send data
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
            setPreviousMessages(obj, senderId);
        }
    });
}
           
// set previous messages in chat windows
function setPreviousMessages(data, senderId){
    var propic = document.getElementById("profilepiclink").value;
    for (var i=0; i<data.length; i++) {
        if(data[i][1] != senderId){
            var row = '<div class="message-row your-message"><div class="message-content"><div class="message-text">'+ data[i][0] +'</div><div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row); // add to chat interface
        }
        else{
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+propic+'"/> <div class="message-text">'+ data[i][0] +'</div> <div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);
        }
    }
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

//set public chat room data
function setPubRoomData(roomData)
{
    console.log(roomData);
    document.getElementById("reserver-name").textContent = roomData.name;
    document.getElementById("msgType").value = "pubg";
    document.getElementById("roomId").value = roomData.id;
    document.getElementById('pri-chat-message-list').innerHTML = "";document.getElementById('pri-chat-message-list').innerHTML = "";
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
    else if(result == "delete-room"){ // chat room deleted by the admin
        joinButton.style.visibility = 'hidden';
        dropdown.style.visibility = 'hidden';
        sendButton.style.visibility = 'visible';
        roomMemberId.value = null;
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
            var newCount;

            if(count == 1) newCount = count + " Member";
            else newCount = count + " Members";

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

//send a msg to the server to update the room list for all
function update_room_list_broadcast()
{
    var datas = { msgType: 'update-room-list'};
    conn.send(JSON.stringify(datas));
}

</script>

<?php

// redirect from create new chat room
if(isset($_POST['status'])){
    if($_POST['status'] == 'ok'){
        $name = $_POST['roomname'];
        $room_list_status = $_POST['status'];
        echo "<script>
                displayMsg('You have successfully created $name', 1);
                document.getElementById('room-list-status').value = 'ok';
            </script>";
    }
    else if($_POST['status'] == 'wrong'){
        echo "<script>
            displayMsg('Something went wrong', 0);
        </script>";
    }
    unset($_POST['status']);
}

// redirect from create new private group
if(isset($_POST['member-userids'])){
    $user_list = $_POST['member-userids'];

    echo "<script>
            document.getElementById('member-userids').value = '$user_list';
            console.log(document.getElementById('member-userids').value);
            
        </script>";


    unset($_POST['member-userids']);
}

?>
