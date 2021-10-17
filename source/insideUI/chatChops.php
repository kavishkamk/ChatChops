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
                                        <div class="status-dot"><i class="fas fa-circle"></i></div>
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
                        </div>
                        <div>
                            <!-- this for set details to send messages -->
                            <form class="chat-form" onkeydown="return event.key != 'Enter';">
                                <input type="hidden" id="senderId" name="senderId" value="<?php echo '.$_SESSION["userid"].';?>">
                                <input type="hidden" id="reseverId" name="reseverId" value="">
                                <input type="hidden" id="profilepiclink" name="profilepiclink" value="">
                                <input type="hidden" id="msgType" name="msgType" value="">
                                <input type="text" id="msg" name="msg" placeholder="type a message"/>
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
        };

        // set reserved messages
        conn.onmessage = function(e) {
            console.log(e.data);
            var data = JSON.parse(e.data);
            var propic = document.getElementById("profilepiclink").value;
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/'+propic+'"/> <div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);
        };

        $("#send-msg").click(function(){
            var senderId   = $("#senderId").val();
            var reserverId = $("#reseverId").val();
            var msg        = $("#msg").val();
            var msgType    = $("#msgType").val();
            var data = {
                msgType: msgType,
                senderId: senderId,
                reserverId: reserverId,
                msg: msg
            };
            conn.send(JSON.stringify(data));
            document.getElementById('msg').value = '';
            var row = '<div class="message-row your-message"><div class="message-content"><div class="message-text">'+ msg +'</div><div class="message-time"></div></div></div>';
            $('#pri-chat-message-list').append(row);
        })

    })

    // this method used to set chat room paramiters
    function setChatRoomDetails(val){
        var details = val.split(" ");
        document.getElementById("reseverId").value = details[0];
        document.getElementById("profilepiclink").value = details[3];
        document.getElementById("msgType").value = "pri";
        document.getElementById("reserver-name").textContent= details[1].concat(" ",details[2]);
    }
</script>