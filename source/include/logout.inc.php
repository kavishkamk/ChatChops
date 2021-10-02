<?php
    // for log out
    session_start();
    session_unset();
    session_destroy();
    header("Location:../login.php?logout=logoutok");