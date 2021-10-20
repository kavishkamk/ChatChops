
    <!-- This is a chat interface -->
    <head>
    

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <style>
    body {
        font-family: 'Roboto';
    }
    </style>

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
                </div>

                <div class="chat" id="private-group" style="grid-column:3 / 4; grid-row: 1 / 2">
                    <div class= "topic">
                        Private Groups
                    </div>
                </div>
                
                <div class="chat" id="public-group" style="grid-column:3 / 4; grid-row: 2 / 3">
                    <div class= "topic">
                        Public Chat Rooms
                    </div>

                    <div style= "position: right">
                        <a href="../public-rooms/create-pub-room.php">
                        <button id= "create-room-button">Create Room</button>
                        </a>
                    </div>

                    <div class= "pub-room-list">
                        <?php
                            //take #of rooms active
                            //while loop count = #of rooms active
                            //create div class= room for each room details (ref = chat msg in w3schools)
                            // <a href> to each div to load room into chat ui in the middle
                        ?>

                        <!--sample rooms display-->
                        <div class= "room">
                            Room 1
                        </div>

                        <div class= "room">
                            Room 2
                        </div>
                    </div>
                
                </div>
            </div>
        </main>
    </body>

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

</html>
