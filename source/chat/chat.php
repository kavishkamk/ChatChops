<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . "/phpClasses/PrivateChatHandle.class.php";
require dirname(__DIR__) . "/phpClasses/OnlineOffline.class.php";
require dirname(__DIR__) . "/public-rooms/publicRoomChat.class.php";
require dirname(__DIR__) . "/public-rooms/dropDownMenu.class.php";
require dirname(__DIR__) . "/private-groups/dropdownHandle.class.php";
require dirname(__DIR__) . "/private-groups/privateGroupChat.class.php";

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $clientsWithId; // store connection with user id [userid => connection]
    protected $clientIdWithresourceId; // store resourceId with user Id [resourcesid => clientid]

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $d = $this-> timeshow();
        echo $d. "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $d = $this-> timeshow();
        echo sprintf($d.'Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        // check established connection
        if(isset($data['cliendId'])){
            $val1 = 1;
            $this->clientsWithId[$data['cliendId']] = $from; // add connection with client id
            $this->clientIdWithresourceId[$from->resourceId] = $data['cliendId']; // map connection id with user id
            $this->updateDBuserOnline($data['cliendId']);
            $this->broadcastOnlineStatus($val1, $data['cliendId']); // to broad cast user online status
        }

        if(isset($data['msgType'])){
            $msgTypes = $data['msgType']; // get message type

            // sending messages
            if($msgTypes == "pubg"){
                $this-> send_pubg_msgs($data);
            }
            else if($msgTypes == "prig"){
                $this-> send_prig_msgs($data);
            }
            else if($msgTypes == "pri"){
               $this->privateMsgReserverConn($data);
            }

            // for public chat rooms
            else if($msgTypes == "pubg-user-remove"){
                $this-> pubg_user_remove($data);
            }
            else if($msgTypes == "memCount-update-req"){
                $this-> memCount_update_req($data);
            }
            else if($msgTypes == "delete-room"){
                $this-> delete_room($data);
            }
            else if($msgTypes == "update-room-list"){
                foreach ($this->clientsWithId as $client) {
                    $client->send(json_encode($data));
                }
            }

            //for private chat groups
            else if($msgTypes == "prig-memCount-update-req"){
                $this-> prig_mem_count_update_req($data);
            }
            else if($msgTypes == "new-grp-add-to-list-req"){
                $this-> new_grp_add_to_list_req($data);
            }
            else if($msgTypes == "delete-group"){
                $this-> delete_group($data);
            }
            else if($msgTypes == "prig-mem-remove"){
                $this-> prig_member_remove($data);
            }
        }
    }

    //a member was removed from the private group by the admin
    public function prig_member_remove($datas)
    {
        $data['msgType'] = $datas['msgType'];
        $remov = $datas['remove_list'];
        $data['group_id'] = $datas['groupid'];

        foreach ($remov as $user) {
            if(array_key_exists($user, $this->clientsWithId)){
                $data['userid'] = $user;

                $resConn = $this->clientsWithId[$user];
                $resConn->send(json_encode($data));
            }
        }
        
        $arr = $datas['memlist'];
        $data1['msgType'] = "prig-memCount-update-req";
        $data1['group_id'] = $datas['groupid'];

        foreach ($arr as $user) {
            if(array_key_exists($user, $this->clientsWithId)){
                $resConn = $this->clientsWithId[$user];
                $resConn->send(json_encode($data1));
            }
        }
        

    }

    // send a message to the members to update the member count in the list
    // when the count is changed 
    // (member leave, remove from the group)
    public function prig_mem_count_update_req($datas)
    {
        $data['msgType'] = $datas['msgType'];
        $data['group_id'] = $datas['group_id'];

        $arr = $datas['member_list'];

        foreach ($arr as $user) {
            if(array_key_exists($user, $this->clientsWithId)){
                $resConn = $this->clientsWithId[$user];
                $resConn->send(json_encode($data));
            }
        }
    }

    // send a message to update the group list
    public function new_grp_add_to_list_req($datas)
    {
        $data['msgType'] = $datas['msgType'];
        $arr = $datas['memlist'];

        foreach ($arr as $user) {
            if(array_key_exists($user, $this->clientsWithId)){
                $resConn = $this->clientsWithId[$user];
                $resConn->send(json_encode($data));
            }
        }
    }

    public function multicast($msg) {
        foreach ($this->clientsWithId as $client) $client->send($msg);
    }
      
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        $val0 = 0;
        $onCloseUserId = $this->clientIdWithresourceId[$conn->resourceId]; // get user id of disconnected user
        unset($this->clientIdWithresourceId[$conn->resourceId]); // remove disconnected user resources ID
        unset($this->clientsWithId[$onCloseUserId]); // remove connction from privat connection list
        $this->updateDBuserOffline($onCloseUserId);
        $this->broadcastOnlineStatus($val0, $onCloseUserId); // to broad cast user offline status

        $d = $this-> timeshow();
        echo $d."Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $d = $this-> timeshow();
        echo $d."An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    // send private message to its reserver
    private function privateMsgReserverConn($details){
        $senddata['msgType'] = $details['msgType'];
        $senddata['senderId'] = $details['senderId'];
        $senddata['reserverId'] = $details['reserverId'];
        $senddata['msg'] = $details['msg'];

        // store chat message in DB as notread
        $priChatObj = new \PrivateChatHandle();
        $lastInId = $priChatObj->privatChatStoreDB($details['senderId'], $details['reserverId'], $details['msg']);
        unset($priChatObj);

        $senddata['msgDbId'] = $lastInId; // get db stored id
        echo $senddata['msgDbId'];
        // send message if user online
        if(array_key_exists($senddata['reserverId'], $this->clientsWithId)){
            $resConn = $this->clientsWithId[$senddata['reserverId']];
            $resConn->send(json_encode($senddata));
        }
        else{
            $d = $this-> timeshow();
            echo $d."offline user <br>";
        }
    }

    //send the message of user removal from a public chat room 
    private function pubg_user_remove($details)
    {
        $data['msgType'] = $details['msgType'];
        $data['room_id'] = $details['room_id'];
        $data['member_id'] = $details['member_id'];
        $data['member_name'] = $details['member_name'];

        $obj = new \dropDownMenu();
        $res = $obj-> user_remove($details['room_id'], $details['member_id'], $details['member_name']);
        
        echo $res;
        
        if($res == 1){
            foreach ($this->clientsWithId as $client) {
                $client->send(json_encode($data));
            }
        }else{
            $d = $this-> timeshow();
            echo $d."public room user remove was unsuccessful";
        }
        unset($obj);
    }

    //send public chat room message to all the users
    private function send_pubg_msgs($details)
    {
        $data['msgType'] = $details['msgType'];
        $data['senderId'] = $details['senderId'];
        $data['username'] = $details['username'];
        $data['propic'] = $details['propic'];
        $data['roomId'] = $details['roomId'];
        $data['roomname'] = $details['roomname'];
        $data['roomMemberId'] = $details['roomMemberId'];
        $data['msg'] = $details['msg'];
        
        $pubObj = new \publicRoomChat();    //store messages in the DB
        $res = $pubObj->storeMsgs($details['roomMemberId'], $details['msg']);

        if($res != "sqlerror"){
            foreach ($this->clientsWithId as $client) {
                $client->send(json_encode($data));
            }
        }else{
            $d = $this-> timeshow();
            echo $d."message didn't save";
        }
        unset($pubObj);
    }

    private function send_prig_msgs($details)
    {
        $data['msgType'] = $details['msgType'];
        $data['senderId'] = $details['senderId'];
        $data['username'] = $details['username'];
        $data['propic'] = $details['propic'];

        $data['groupid'] = $details['groupid'];
        $data['groupname'] = $details['groupname'];
        $data['memberid'] = $details['memberid'];
        $data['msg'] = $details['msg'];

        $pubObj = new \privateGroupChat();    //store messages in the DB
        $res = $pubObj->storeMsgs($details['memberid'], $details['msg']);

        if($res != "sqlerror"){
            // send the msg to each member
            $arr = $details['memlist'];
            foreach ($arr as $user) {
                if(array_key_exists($user, $this->clientsWithId)){
                    $resConn = $this->clientsWithId[$user];
                    $resConn->send(json_encode($data));
                }
            }

        }else{
            $d = $this-> timeshow();
            echo $d."message didn't save";
        }
        unset($pubObj);

        
    }

    //send the request of updating the member count of the given chat room
    private function memCount_update_req($details)
    {
        $data['msgType'] = $details['msgType'];
        $data['room'] = $details['room'];

        foreach ($this->clientsWithId as $client) {
            $client->send(json_encode($data));
        }
    }

    //admin delete the public chat room
    private function delete_room($details)
    {
        $data['msgType'] = $details['msgType'];
        $data['room_id'] = $details['room_id'];
        $data['admin_member_id'] = $details['admin_member_id'];
        $data['roomname'] = $details['roomname'];

        $obj = new \dropDownMenu();
        $res = $obj-> delete_room($details['room_id']);
        
        echo $res;
        
        if($res == 1){
            foreach ($this->clientsWithId as $client) {
                $client->send(json_encode($data));
            }
        }else{
            $d = $this-> timeshow();
            echo $d."public room deleting was unsuccessful";
        }
        unset($obj);
    }

    //admin delete the private group
    private function delete_group($details)
    {
        $data['msgType'] = $details['msgType'];
        $data['group_id'] = $details['group_id'];
        $data['admin_member_id'] = $details['admin_member_id'];
        $data['groupname'] = $details['groupname'];

        $arr = $details['memlist'];

        $obj = new \dropdownHandle();
        $res = $obj-> delete_group($details['group_id']);
        
        echo $res;
        
        if($res == 1){
            foreach ($arr as $user) {
                if(array_key_exists($user, $this->clientsWithId)){
                    $resConn = $this->clientsWithId[$user];
                    $resConn->send(json_encode($data));
                }
            }
        }else{
            $d = $this-> timeshow();
            echo $d."public room deleting was unsuccessful";
        }
        unset($obj);
    }

    // send online or offliene status
    private function broadcastOnlineStatus($val, $newConnectionId){
        $priChatObj = new \PrivateChatHandle();
        
        $friendList = $priChatObj->getFriendListIdList($newConnectionId); // get friend list
        unset($priChatObj);

        $senddata['msgType'] = "onoff";
        $senddata['statval'] = $val; // online or offline status
        $senddata['friendid'] = $newConnectionId;
        // send to online users
        foreach ($friendList as $row) {
          if (array_key_exists($row['user_id'], $this->clientsWithId)) {
              $resConn = $this->clientsWithId[$row['user_id']];
               $resConn->send(json_encode($senddata));
            }
        }
    }

    // update DB as user online
    private function updateDBuserOnline($userId){
        $onoffObj = new \OnlineOffline();
        $onoffObj->setUserOnline($userId);
        unset($onoffObj);
    }

    private function updateDBuserOffline($userId){
        $onoffObj = new \OnlineOffline();
        $onoffObj->setOfflineStatusInDB($userId);
        unset($onoffObj);
    }

    private function timeshow()
    {
        $d = date("Y-n-d H:i:s");
        return "$d - ";
    }
}