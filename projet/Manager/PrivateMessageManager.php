<?php

namespace App\Manager;

use App\Traits\GlobalManager;
use App\Entity\Message;

session_start();

class PrivateMessageManager
{
    use GlobalManager;

    /**
     * Return an array that contain message between connected user and recipient one
     * @param int $user2
     * @return array
     */
    public function getMessage(int $user2): array{
        $conn = $this->db->prepare("SELECT privatemessage.id as messageId, message, date, u.id as id, u.name as name1, u2.name as name2 FROM privatemessage INNER JOIN user as u ON privatemessage.user1_id = u.id INNER JOIN user as u2 ON privatemessage.user2_id = u2.id WHERE (privatemessage.user1_id = :id1 AND privatemessage.user2_id = :id2) OR (privatemessage.user2_id = :id1 AND privatemessage.user1_id = :id2) ORDER BY privatemessage.id ASC");
        $conn->bindValue(":id1", $_SESSION["user1_id"]);
        $conn->bindValue(":id2", $user2);
        $conn->execute();
        $messages = [];
        foreach($conn->fetchAll() as $select){
            $message = new Message();
            //When the sender of the message is the connected one then i set the sent parameter to true
            //It will be used to set the message class to style it and place it at right

            if(intval($select["id"]) === intval($_SESSION["user1_id"])){
                $message
                    ->setSent(true);
            }
            //Set message seen to 1 witch mean that messages sent by the other user have been seen
            else{
                $connUpdate = $this->db->prepare("UPDATE privatemessage SET seen = 1 WHERE id = :id");
                $connUpdate->bindValue(":id",$select["messageId"]);
                $connUpdate->execute();
            }
            $message->setUsername($select["name1"]);
            $message
                ->setText($select["message"])
                ->setDate($select["date"]);
            $messages[] = $message;
        }
        return $messages;
    }

    /**
     * Insert a message to database between two users
     * @param int $user2
     * @param string $message
     */
    public function sendMessage(int $user2, string $message){
        $conn = $this->db->prepare("INSERT INTO privatemessage (user1_id, user2_id, message, date) VALUES (:id1, :id2, :message, :date)");
        $conn->bindValue(":id1", $_SESSION["user1_id"]);
        $conn->bindValue(":id2", $user2);
        $conn->bindValue(":message", $message);
        $conn->bindValue(":date", date('l jS \of F Y h:i:s A'));
        $conn->execute();
    }
}