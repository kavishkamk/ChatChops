<?php
    require "../insideUI/header.php";
    require "../phpClasses/FriendList.class.php";
?>
        <!-- This is a chat interface -->
        <main>
            <div class="friend-list">
                <div id="frinds" class="friend-container" style="grid-column:2 / 3; grid-row: 1 / 4">
                    <div class="search-bar">
                    </div>
                    <?php
                        $frindObj = new FriendList();
                        $frindDetails = $frindObj->getFriendList($_SESSION['userid']);
                        unset($frindObj);
                    ?>
                    <div class="friend-accounts">

                        <?php
                            while($row = mysqli_fetch_assoc($frindDetails)){
                                echo '<div class="friend-conversation active">
                                        <img src="../profile-pic/'.$row["profilePicLink"].'"/>
                                        <div class="title-text">
                                            '.$row["first_name"].' '.$row["last_name"].'
                                        </div>
                                        <input type="hidden" name="userid" value="'.$row["user_id"].'">
                                        <div class="request-button">
                                        <button type=submit name="requset-submit" class="request-btn">ADD</button>
                                        </div>
                                    </div>';
                            }
                        ?>
                        <!-- <div class="friend-conversation active">
                            <img src="../profile-pic/unknownPerson.jpg"/>
                            <div class="title-text">
                                Kavishka Madhushan
                            </div>
                            <div class="request-button">
                                <button type=submit name="requset-submit" class="request-btn">ADD</button>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div id="friend-request" class="friend-container" style="grid-column:4 / 5; grid-row: 1 / 2">
                </div>
                <div id="send-request" class="friend-container" style="grid-column:4 / 5; grid-row: 3 / 4">
                </div>
            </div>
        </main>

    </body>
</html>
