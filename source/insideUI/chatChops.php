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
                    <div class="chat-title">
                        <span>Kavishka Madhushan</span>
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <div class="chat-body">
                        <div class="chat-message-list">
                            <div class="message-row your-message">
                                <div class="message-content">
                                    <div class="message-text">Ok then</div>
                                    <div class="message-time"></div>
                                </div>
                            </div>
                            <div class="message-row other-message">
                                <div class="message-content">
                                    <img src="../profile-pic/unknownPerson.jpg"/>
                                    <div class="message-text">Ok ok</div>
                                    <div class="message-time"></div>
                                </div>
                            </div>
                            <div class="message-row your-message">
                                <div class="message-content">
                                    <div class="message-text">Ok bro</div>
                                    <div class="message-time"></div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-form">
                            <input type="text" placeholder="type a message"/>
                            <button type="submit" class="send-msg"><i class='fas fa-paper-plane'></i></button>
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
