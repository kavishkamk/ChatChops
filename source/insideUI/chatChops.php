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
                        $priObj = new PrivateChatHandle();
                        $datas = $priObj->getFriendList($_SESSION['userid']);
                        unset($priObj);
                    ?>
                    <div class="friend-accounts">
                        <?php
                            // set users in the div with given details
                            foreach($datas as $data){
                                echo '<div class="friend-conversation1 active">
                                        <img src="../profile-pic/'.$data["profilePicLink"].'"/>
                                        <div class="title-text">
                                            '.$data["first_name"].' '.$data["last_name"].'
                                        </div>
                                        <div>
                                        </div>
                                        <div class="new-Message">
                                            hdudfu ugfudf udgfy fy gfy f gyfgyd dgfyg gfy8f 7fcfdft fgydfh gfyftyft tfytfyt
                                        </div>
                                        <div class="status-dot"><i class="fas fa-circle"></i></div>
                                        <div class="request-button">
                                            <form method="post">
                                                <input type="hidden" name="frindId" value="'.$data["user_id"].'">
                                            </form>
                                        </div>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
                <div class="chat" id="chat-gui" style="grid-column:2 / 3; grid-row: 1 / 3">
                    <div class="chat-title">
                        <span>Kavishka Madhushan</span>
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <div class="chat-body">
                        <div class="chat-message-list" id="pri-chat-message-list">
                        </div>
                        <div>
                            <form class="chat-form" onkeydown="return event.key != 'Enter';">
                                <input type="hidden" id="senderId" name="senderId" value="1">
                                <input type="hidden" id="reseverId" name="reseverId" value="2">
                                <input type="hidden" id="msgType" name="msgType" value="pri">
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

        conn.onmessage = function(e) {
            console.log(e.data);
            var data = JSON.parse(e.data);
            var row = '<div class="message-row other-message"> <div class="message-content"> <img src="../profile-pic/unknownPerson.jpg"/> <div class="message-text">'+ data.msg +'</div> <div class="message-time"></div></div></div>';
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
</script>
