<?php
    require "../insideUI/header.php";
    require "../phpClasses/FriendList.class.php";
?>
        <!-- This is a chat interface -->
        <main>
            <div class="friend-list">
                <div id="frinds" class="friend-container" style="grid-column:2 / 3; grid-row: 1 / 4">
                    <div class="search-bar"><p>Users</p>
                    </div>
                    <?php
                        $frindObj = new FriendList();
                        $frindDetails = $frindObj->getFriendList($_SESSION['userid']);
                        $frindRequestDetails = $frindObj->getfriendRequestList($_SESSION['userid']);
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
                                        <div class="request-button">
                                            <form method="post">
                                                <input type="hidden" name="frindId" value="'.$row["user_id"].'">
                                                <button type=submit name="requset-submit" class="request-btn">ADD</button>
                                            </form>
                                        </div>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
                <div id="friend-request" class="friend-container" style="grid-column:4 / 5; grid-row: 1 / 4">
                <div class="search-bar"><p>Friend Request</p>
                    </div>
                    <?php
                        while($row = mysqli_fetch_assoc($frindRequestDetails)){
                            echo '<div class="friend-conversation active">
                                <img src="../profile-pic/'.$row["profilePicLink"].'"/>
                                    <div class="title-text">
                                        '.$row["first_name"].' '.$row["last_name"].'
                                    </div>
                                    <div class="request-button">
                                        <form method="post">
                                            <input type="hidden" name="requestFrindId" value="'.$row["user_id"].'">
                                            <button type=submit name="confirm-submit" class="request-btn">CONFIRM</button>
                                        </form>
                                    </div>
                                </div>';
                            }
                        ?>
                </div>
            </div>
        </main>

    </body>
</html>

<?php

    if(isset($_POST['requset-submit'])){
        $frindObj = new FriendList();
        $frindDetails = $frindObj->addFriends($_SESSION['userid'], $_POST['frindId']);
        unset($frindObj);
        echo '<script>if (! localStorage.justOnce) {localStorage.setItem("justOnce", "true");window.location.reload(true);}</script>';
    }

    if(isset($_POST['confirm-submit'])){
        $frindObj = new FriendList();
        $frindDetails = $frindObj->requestconfirm($_SESSION['userid'], $_POST['requestFrindId']);
        unset($frindObj);
        echo '<script>if (! localStorage.justOnce) {localStorage.setItem("justOnce", "true");window.location.reload(true);}</script>';
    }

    if (isset($_POST['requset-submit'])) {
        echo '<meta http-equiv=Refresh content="0;url=friendList.php?reload=1">';
    }

    if (isset($_POST['confirm-submit'])) {
        echo '<meta http-equiv=Refresh content="0;url=friendList.php?reload=1">';
    }
?>

