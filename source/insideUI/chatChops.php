<?php
    require "header.php";
?>
        <!-- This is a chat interface -->
        <head>
        <link rel="stylesheet" type="text/css" href="../css/chatUI.css">

        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <style>
        body {
            font-family: 'Roboto';
        }
        </style>

        </head>
    <body>
        <main>
            <div class = "chatContainer" id="chat">
                <div class="chat" id="private-chat" style="grid-column:1 / 2; grid-row: 1 / 3">
                </div>
                <div class="chat" id="chat-gui" style="grid-column:2 / 3; grid-row: 1 / 3">
                    
                </div>
                <div class="chat" id="private-group" style="grid-column:3 / 4; grid-row: 1 / 2">
                    <div class= "topic">
                        Private Groups
                        <hr>
                    </div>
                </div>
                <div class="chat" id="public-group" style="grid-column:3 / 4; grid-row: 2 / 3">
                    <table>
                        <th>
                            <div class= "topic">
                                Public Chat Rooms
                            </div>
                        </th>
                        <th>
                            <a href="create-pub-room.php">
                            <button id= "create-room-button">Create Room</button>
                            </a>
                        </th>
                    </table>
                    <hr>
                    

                    <div class= "pub rooms">
                    </div>
                </div>
            </div>
        </main>

        <script>/*
            function fullwindowpopup(){
                document.getElementById("form-popup").style.display = "block";
            }

            function toggle(e) {
                e.stopPropagation();
                popup.classList.toggle("hide");
            }

            function closePopup() {
                if (!popup.classList.contains("hide")) {
                    popup.classList.toggle("hide");
                }
            }*/

        </script>
    </body>
</html>
