<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . "/phpClasses/PrivateChatHandle.class.php";

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $clientsWithId; // store connection with user id
    protected $clientIdWithresourceId; // store resourceId with user Id

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        // check established connection
        if(isset($data['cliendId'])){
            $val1 = 1;
            $this->clientsWithId[$data['cliendId']] = $from; // add connection with client id
            $this->clientIdWithresourceId[$from->resourceId] = $data['cliendId']; // map connection id with user id
            $this->broadcastOnlineStatus($val1, $data['cliendId']); // to broad cast user online status
        }
        if(isset($data['msgType'])){
            $msgTypes = $data['msgType']; // get message type

            if($msgTypes == "pubg"){
                echo "this is public group";
            }
            else if($msgTypes == "prig"){
               echo "this is private group";
            }
            else if($msgTypes == "pri"){
               $this->privateMsgReserverConn($data);
            }
        }
        
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        $val0 = 0;
        $onCloseUserId = $this->clientIdWithresourceId[$conn->resourceId]; // get user id of disconnected user
        unset($this->clientIdWithresourceId[$conn->resourceId]); // remove disconnected user resources ID
        unset($this->clientsWithId[$onCloseUserId]); // remove connction from privat connection list
        $this->broadcastOnlineStatus($val0, $onCloseUserId); // to broad cast user offline status
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    // send private message to its reserver
    private function privateMsgReserverConn($details){
        $senddata['msgType'] = $details['msgType'];
        $senddata['senderId'] = $details['senderId'];
        $senddata['reserverId'] = $details['reserverId'];
        $senddata['msg'] = $details['msg'];

        if(array_key_exists($senddata['reserverId'], $this->clientsWithId)){
            $resConn = $this->clientsWithId[$senddata['reserverId']];
            $resConn->send(json_encode($senddata));
        }
        else{
            echo "offline user";
        }
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
}