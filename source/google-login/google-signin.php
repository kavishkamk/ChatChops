<?php 
?>
<html>
    <head>
        <title>Google Sign-up</title>
        <link rel="stylesheet" href="css/google-button.css" />
    </head>
    <body>
    <center>
    <div class = "full-button">
        <?php
        require 'glogin-config.php';
        echo "<a href= '" .$client-> createAuthUrl(). "'>";
        ?>
            <div class="google-btn" >
            <div class="google-icon-wrapper">
                <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"/>
            </div>
            <p class="btn-text">
                <b> Signup with google</b>
            </p>
            </div>

        </a>
    </div>
    </center>
    </body>
</html>