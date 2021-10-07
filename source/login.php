<!-- This is login interface -->
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logging</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/footer.css">
    </head>
    <body>
        <div class=container>
            <div id="logo">
                <img src= "images/chatchops.png">
            </div>
            <div>
                <p class="logerr">Invalid Login.Pleade try again...</p>
            </div>
            <div id="logcont">
                <p>Log-In</p>
                <form class="logform">
                    <label for="unameormail">Email*</label><br>
                    <input type="text" name="unameormail" placeholder="enter your email / username" size="30" class="flog">
                    <br>
                    <label for="pwd">Password*</label><br>
                    <input type="password" name="pwd" placeholder="enter your password" size="30" class="flog"><br>
                    <button type="submit" name="log-submit" class="logbutn">Login</button>
                    <p class="fpwd">Forgot your password?</p><br>
                </form>
            </div>
        </div>
        <p>Copyright &copy; 2021 ChatChops. Inc. All rights reserved</p>
    </body>
</html>