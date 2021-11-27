<?php

session_start();

require "../phpClasses/privateGroup.class.php";

if(isset($_POST['pri-grp-submit'])){

    $groupname = test_input($_POST['groupname']);
    $groupbio = test_input($_POST['groupbio']);
    $icon = test_input($_POST['foo']);

    $obj = new privateGroup($groupname, $groupbio);

    $validate = $obj->validate();

    if($validate == 0){
        //valid inputs
        require_once "privateGroupDbHandle.class.php";

        $pubObj = new privateGroupDbHandle();
        $checkName = $pubObj-> checkUniqueName($groupname);

        //name is valid
        if($checkName == 0){
            $create = $pubObj-> createPrivateGroup($groupname, $groupbio, $icon, $_SESSION['userid']);

            if($create == "ok"){
                /***************************** */
                // redirect into a new pg 
                        //to select members from his friend list

                // propic, fname lname, username, add button
                        //if add button is clicked once, add button hide -> cancel button visible
                
                //create group button to submit selected member list

                /****************************** */

                
                echo "<form method='post' name = 'formAlert' action= 'http://localhost/chatchops/source/private-groups/create-private-group.php'>
                        <input type='hidden' name='status' value = 'ok' />   
                        <input type='hidden' name='groupname' value = $groupname /> 
                    </form>";
                
                echo '<script>
                        document.formAlert.submit();
                    </script>';
                
            }
            else{
                echo "<form method='post' name = 'formAlert' action= 'http://localhost/chatchops/source/insideUI/chatChops.php'>
                    <input type='hidden' name='status' value = 'wrong' />   
                </form>";
            
                echo '<script>
                        document.formAlert.submit();
                    </script>';
            }
        }else{
            header("Location:create-private-group.php?error=notavailable&groupname=$groupname&groupbio=$groupbio&picn=$icon");
        }
        
        //send to db and check errors
        //if all good, redirect to another page to select the members from his friends list
    }

    else if($validate == 1){
        header("Location:create-private-group.php?error=emptyfields&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 2){
        header("Location:create-private-group.php?error=wrongname&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 4){
        header("Location:create-private-group.php?error=namemax&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 5){
        header("Location:create-private-group.php?error=biomax&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else{
        
        echo "<form method='post' name = 'formAlert' action= 'http://localhost/chatchops/source/insideUI/chatChops.php'>
                <input type='hidden' name='status' value = 'wrong' />   
            </form>";
    
        echo '<script>
                document.formAlert.submit();
            </script>';
    
    }

    unset($obj);
    exit();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}