
    <!-- This is a chat interface -->
    <head>
    

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <style>
    body {
        font-family: 'Roboto';
    }
    </style>

    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/chatUI.css">

    </head>
    <body>
        <main>
            <?php
                require "header.php";
            ?>

            <div class = "chatContainer" id="chat">
                <div class="chat" id="private-chat" style="grid-column:1 / 2; grid-row: 1 / 3">
                </div>
                <div class="chat" id="chat-gui" style="grid-column:2 / 3; grid-row: 1 / 3">
                
                    <div class="chat-title">
                        chat title
                        <span id="reserver-name"> group name</span>
                    </div>
                    <div class="chat-body">chat body</div>
                
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
                                $roomname = $arr[$i]['name'];
                                $icon = $arr[$i]['icon'];
                                $memCount = $roomObj-> getMemberCount($roomname);

                                echo "<a href= '#' class= 'groupChat-load' style='text-decoration:none;'><div class= 'room'>
                                <img src= '../group-icons/$icon' alt='group icon' width='35'height='35' class='img-circle pro-img'>&emsp; 
                                $roomname
                                <span class= 'memcount' style='float: right'>$memCount Members</span>
                                </div></a>";
                                
                            }

                            

                        }
                        unset($roomObj);
                        ?>
                    </div>
                
                </div>
            </div>
        </main>
    </body>

    <?php
        $msg = "";
        if(isset($_GET['status'])){
            $msg = setMessage();
            echo '<script>alert ("'.$msg.'")</>';
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

</html>
