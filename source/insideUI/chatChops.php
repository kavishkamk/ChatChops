<?php
    require "header.php";
?>
        <!-- This is a chat interface -->
        <main>
            <div class = "chatContainer" id="chat">
                <div class="chat" id="private-chat" style="grid-column:1 / 2; grid-row: 1 / 3">
                    <div class="search-bar"><p>Friends</p>
                    </div>
                    <div class="friend-accounts">
                        <?php
                            // set users in the div with given details
                            // while($row = mysqli_fetch_assoc($frindDetails)){
                            //     echo '<div class="friend-conversation active">
                            //             <img src="../profile-pic/'.$row["profilePicLink"].'"/>
                            //             <div class="title-text">
                            //                 '.$row["first_name"].' '.$row["last_name"].'
                            //             </div>
                            //             <div class="request-button">
                            //                 <form method="post">
                            //                     <input type="hidden" name="frindId" value="'.$row["user_id"].'">
                            //                     <button type=submit name="requset-submit" class="request-btn">ADD</button>
                            //                 </form>
                            //             </div>
                            //         </div>';
                            // }
                        ?>
                        <div class="friend-conversation1 active">
                                    <img src="../profile-pic/unknownPerson.jpg"/>
                                   <div class="title-text">
                                            Kavishka Madhushan
                                        </div>
                                        <div>
                                            
                                        </div>
                                        <div class="new-Message">
                                            hdudfu ugfudf udgfy fy gfy f gyfgyd dgfyg gfy8f 7fcfdft fgydfh gfyftyft tfytfyt
                                        </div>
                                        <div class="status-dot"><i class="fas fa-circle"></i></div>
                                        <div class="request-button">
                                            <form method="post">
                                            <input type="hidden" name="frindId" value="1">
                                           </form>
                                        </div>
                                    </div>
                    </div>
                </div>
                <div class="chat" id="chat-gui" style="grid-column:2 / 3; grid-row: 1 / 3">
                </div>
                <div class="chat" id="private-group" style="grid-column:3 / 4; grid-row: 1 / 2">
                </div>
                <div class="chat" id="public-group" style="grid-column:3 / 4; grid-row: 2 / 3">
                </div>
            </div>
        </main>

    </body>
</html>
