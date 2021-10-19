<?php
require_once "DbConnection.class.php";

    class PrivateChatHandle extends DbConnection{

        public function getFriendList($uid){
            $sqlQ = "SELECT users.user_id, users.profilePicLink, users.first_name, users.last_name, users.onlineStatus
            FROM users WHERE users.user_id IN
            (SELECT friends.to_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? AND friend_request.block_status=?)) WHERE friends.from_user_id = ? UNION
            SELECT friends.from_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? AND friend_request.block_status=?))
            WHERE friends.to_user_id = ?) AND NOT users.user_id = ? AND users.active_status = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                $datas = array();
                $val1 = 1; $val0 = 0;
                mysqli_stmt_bind_param($stmt, "iiiiiiii", $val1, $val0, $uid, $val1, $val0, $uid, $uid, $val1);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result)){
                    $datas[] = $row;
                }
                $this->connclose($stmt, $conn);
                return $datas;
                exit();
            }

        }

        public function getFriendListIdList($uid){
            $sqlQ = "SELECT users.user_id FROM users WHERE users.user_id IN
            (SELECT friends.to_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? AND friend_request.block_status=?)) WHERE friends.from_user_id = ? UNION
            SELECT friends.from_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? AND friend_request.block_status=?))
            WHERE friends.to_user_id = ?) AND NOT users.user_id = ? AND users.active_status = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                $datas = array();
                $val1 = 1; $val0 = 0;
                mysqli_stmt_bind_param($stmt, "iiiiiiii", $val1, $val0, $uid, $val1, $val0, $uid, $uid, $val1);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result)){
                    $datas[] = $row;
                }
                $this->connclose($stmt, $conn);
                return $datas;
                exit();
            }
        }

        // this function used to store private message as unreaded message(msg_status = 0)
        public function privatChatStoreDB($from, $to, $msg){
            $frendId = $this->getFriendId($from, $to);
            $sqlQ = "INSERT INTO private_message(message, send_time, msg_status, receive_time, reserveId) VALUES(?,?,?,?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $val0 = 0;
                $onlieTime = date("Y-n-d H:i:s"); // acout log date and time
                mysqli_stmt_bind_param($stmt, "ssisi", $msg, $onlieTime, $val0, $onlieTime,$to);
                mysqli_stmt_execute($stmt);
                $insertId = mysqli_stmt_insert_id($stmt);
                $this->connclose($stmt, $conn);
                $this->setPMsgFriendMap($frendId, $insertId);
                return $insertId;
                exit();
            }
        }

        // set given message as readed in privat chat useing p_id of private_message table
        public function setPrivatMsgAsRead($msgid){
            $sqlQ = "UPDATE private_message SET msg_status = ? WHERE p_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $val1 = 1;
                mysqli_stmt_bind_param($stmt, "ii", $val1, $msgid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return 1;
                exit();
            }
        }

        // set p_msg_friend_map table
        private function setPMsgFriendMap($fid, $recid){
            $sqlQ = "INSERT INTO p_msg_friend_map(p_id, friend_id) VALUES(?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ii", $recid, $fid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }
        }

        // get friend_id from friends table in DB
        private function getFriendId($from, $to){
            $sqlQ = "SELECT friend_id FROM friends WHERE (from_user_id=? AND to_user_id=?) OR (from_user_id=? AND to_user_id=?)";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "iiii", $from, $to, $to, $from);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($result)){
                    $this->connclose($stmt, $conn);
                    return $row['friend_id'];
                    exit();
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "nouser";
                    exit();
                }
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }