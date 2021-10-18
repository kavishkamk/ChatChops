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
                                        <div class="new-Message">
                                            hdudfu ugfudf udgfy fy gfy f gyfgyd dgfyg gfy8f 7fcfdft fgydfh gfyftyft tfytfyt
                                        </div>
                                        <div class="status-dot" id="onoff-'.$row["user_id"].'">'.$onlineicon.'</div>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
                <!-- this is chat gui -->
                <div class="chat" id="chat-gui" style="grid-column:2 / 3; grid-row: 1 / 3">
                    <div class="chat-title">
                        <span id="reserver-name"></span> <!-- to set reserver name -->
                        <i class="fas fa-ellipsis-v"></i> <!-- list icon -->
                    </div>
                    <div class="chat-body">
                        <!-- this is chat message list -->
                        <div class="chat-message-list" id="pri-chat-message-list">
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
                        <div>
                            <!-- this for set details to send messages -->
                            <form class="chat-form" onkeydown="return event.key != 'Enter';"> <!-- to stop refresh when user ENTER button on text fileld-->
                                <input type="hidden" id="senderId" name="senderId" value="<?php echo ''.$_SESSION["userid"].'';?>"> <!--sender id-->
                                <input type="hidden" id="reseverId" name="reseverId" value=""> <!--reserver id-->
                                <input type="hidden" id="profilepiclink" name="profilepiclink" value=""> <!--profie pic link-->
                                <input type="hidden" id="msgType" name="msgType" value=""> <!-- set message type -->
                                <input type="text" id="msg" name="msg" placeholder="type a message"/> <!-- get input message -->
                                <!-- button for stop refresh page when send message -->
                                <button type="button" id="send-msg" name="send-msg" class="send-msg"><i class='fas fa-paper-plane'></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="chat" id="private-group" style="grid-column:3 / 4; grid-row: 1 / 2">
                </div>
                <div class="chat" id="public-group" style="grid-column:3 / 4; grid-row: 2 / 3">
                </div>
            </div>
        </main>

    </body>
</html>

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
            }
            else if((data.msgType).localeCompare("onoff") == 0){
                setOnlineOrOffline(data);
            }
            
            
        };

        $("#send-msg").click(function(){
            var senderId   = $("#senderId").val(); // get sender id
            var reserverId = $("#reseverId").val(); // get reserver id
            var msg        = $("#msg").val();  // message
            var msgType    = $("#msgType").val(); // message type
            // structure
            var data = {
                msgType: msgType,
                senderId: senderId,
                reserverId: reserverId,
                msg: msg
            };
            conn.send(JSON.stringify(data)); // send data
            document.getElementById('msg').value = ''; // set chat field to empty
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
            // set reserved chat message
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+propic+'"/> <div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);
        }

    // this method used to set chat room paramiters
    function setChatRoomDetails(val){
        var details = val.split(" ");
        document.getElementById("reseverId").value = details[0];
        document.getElementById("profilepiclink").value = details[3];
        document.getElementById("msgType").value = "pri";
        document.getElementById("reserver-name").textContent= details[1].concat(" ",details[2]);
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
        // document.getElementById() = ;
    }
</script>