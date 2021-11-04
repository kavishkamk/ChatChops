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
            </div>

            <div class="chat-message-list" id="pri-chat-message-list">
            </div>

            <div class="chat-body">
                <form class="chat-form" onkeydown="return event.key != 'Enter';">
                    <input type="hidden" id="senderId" name="senderId" value="<?php echo ''.$_SESSION["userid"].'';?>"> <!--sender id-->
                    <input type="hidden" id="msgType" name="msgType" value=""> <!-- set message type -->
                    <input type="text" id="msg" name="msg" placeholder="type a message"/> <!-- get input message -->
                    <!-- button for stop refresh page when send message -->
                    <button type="button" id="send-msg" name="send-msg" class="send-msg"><i class='fas fa-paper-plane'></i></button>
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
                    
                    // <a href> to each div to load room into chat ui in the middle

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
                    for($i=0; $i<$count; $i++){
                        $roomData = $arr[$i];
                        $roomname = $arr[$i]['name'];
                        $icon = $arr[$i]['icon'];
                        $memCount = $roomObj-> getMemberCount($roomname);

                        $roomDataJSON = json_encode($roomData);

                        echo "<div onclick= 'setPubRoomData($roomDataJSON)' class= 'room active' id = '.$roomname.'>
                        <img src= '../group-icons/$icon' alt='group icon' width='35'height='35' class='img-circle pro-img'>&emsp; 
                        $roomname
                        <span class= 'memcount' style='float: right'>$memCount Members</span>
                        </div>";
                        
                    }
                }
                unset($roomObj);


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
}
</script>


