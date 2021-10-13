<?php 
?>
<html>
    <head>
        <title>Google Sign-in</title>
        <link rel="stylesheet" href="css/google-button2.css" />
    </head>
    <body>
    <center>
    <div class = "full-button">
        <?php
        require 'gsign-in-config.php';
        echo "<a href= '" .$client-> createAuthUrl(). "'>";
        ?>
            <div class="google-btn" >
            <div class="google-icon-wrapper">
                <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"/>
            </div>
            <p class="btn-text">
                <b> Signin with google</b>
            </p>
            </div>

        </a>
    </div>
    </center>
    </body>
</html>