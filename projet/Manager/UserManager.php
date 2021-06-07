<?php



namespace App\Manager;

require_once $_SERVER["DOCUMENT_ROOT"] . "/utils/function.php";

use App\Traits\GlobalManager;
use App\Entity\User;

class UserManager
{
    use GlobalManager;

    /**
     * Return the first User where mail = $mail
     * @param string $mail
     * @return User|null
     */
    public function getUserByMail(string $mail): ?User {
        $conn = $this->db->prepare("SELECT * FROM user WHERE mail = :mail");
        $conn->bindValue(":mail", $mail);
        if($conn->execute()){
            $selected = $conn->fetchAll();
            if(count($selected) > 0){
                $selected = $selected[0];
                $conn = $this->db->prepare("SELECT name FROM userrole WHERE id = :id");
                $conn->bindValue(":id", $selected["userrole_id"]);
                if($conn->execute()){
                    $role = $conn->fetch()["name"];
                }
                else{
                    $role = null;
                }
                $user = new User();
                $user
                    ->setId($selected["id"])
                    ->setMail($selected["mail"])
                    ->setPass($selected["pass"])
                    ->setName($selected["name"])
                    ->setRole($role)
                    ->setLien($selected["lienGithub"]);
                return $user;
            }
            else{
                return null;
            }
        }
    }

    /**
     * Return every user of the database
     * @return array
     */
    public function getUsers(): array{
        $conn = $this->db->prepare("SELECT * FROM user");
        $users = [];
        if($conn->execute()){
            $selected = $conn->fetchAll();
            foreach($selected as $select){
                $user = new User();
                $user
                    ->setName($select["name"])
                    ->setId($select["id"])
                    ->setMail($select["mail"])
                    ->setLien($select["lienGithub"])
                    ->setPass($select["pass"]);
                $users[] = $user;
            }
        }
        return $users;
    }

    /**
     * Return the user's name of a certain user
     * @param int $id
     * @return mixed
     */
    public function getUsernameById(int $id){
        $conn = $this->db->prepare("SELECT name FROM user WHERE id = :id");
        $conn->bindValue(":id", $id);
        if($conn->execute()){
            return $conn->fetch();
        }
    }

    /**
     * Return all information of a certain user
     * @param int $id
     * @return User
     */
    public function getAllInfoById(int $id){
        $conn = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $conn->bindValue(":id", $id);
        if($conn->execute()){
            $user = new User();
            $info = $conn->fetch();
            $conn = $this->db->prepare("SELECT name FROM userrole WHERE id = :id");
            $conn->bindValue(":id", $info["userrole_id"]);
            if($conn->execute()){
                $role = $conn->fetch()["name"];
            }
            else{
                $role = null;
            }
            $user
                ->setId($info["id"])
                ->setMail($info["mail"])
                ->setLien($info["lienGithub"])
                ->setName($info["name"])
                ->setIcon($info["icone"])
                ->setRole($role)
                ->setBio($info["bio"]);
            return $user;
        }
    }

    /**
     * Change follow between session's user and a certain user
     * @param int $id
     * @return bool
     */
    public function followUser(int $id){
        $conn = $this->db->prepare("SELECT id FROM userfollow WHERE user1_id = :id1 AND user2_id = :id2");
        $conn->bindValue(":id1", $_SESSION["user1_id"]);
        $conn->bindValue(":id2", $id);
        if($conn->execute()){
            if(!$conn->fetch()){
                $conn = $this->db->prepare("INSERT INTO userfollow (user1_id, user2_id)  VALUES (:id1, :id2)");
                $conn->bindValue(":id1", $_SESSION["user1_id"]);
                $conn->bindValue(":id2", $id);
                $conn->execute();
                return true;
            }
            else{
                $conn = $this->db->prepare("DELETE FROM userfollow WHERE user1_id = :id1 AND user2_id = :id2");
                $conn->bindValue(":id1", $_SESSION["user1_id"]);
                $conn->bindValue(":id2", $id);
                $conn->execute();
                return false;
            }
        }
    }

    /**
     * Return follow statue between session's user and a certain user
     * @param  int $id
     * @return bool
     */
    public function getFollow(int $id) : bool{
        $conn = $this->db->prepare("SELECT id FROM userfollow WHERE user1_id = :id1 AND user2_id = :id2");
        $conn->bindValue(":id1", $_SESSION["user1_id"]);
        $conn->bindValue(":id2", $id);
        if($conn->execute()){
            if ($conn->fetch()) {
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * Modifie connected user's informations
     * @param string $name
     * @param string $bio
     * @param string $link
     */
    public function modifyUser(string $name, string $bio, string $link){
        $conn = $this->db->prepare("UPDATE user SET bio = :bio, name = :name, lienGithub = :link  WHERE id = :id");
        $conn->bindValue(":bio", $bio);
        $conn->bindValue(":name", $name);
        $conn->bindValue(":id", $_SESSION["user1_id"]);
        $conn->bindValue(":link", $link);
        $conn->execute();
    }

    /**
     * Get followed users of connected's one
     * @return array
     */
    public function getFollowedUsers() :array{
        $conn = $this->db->prepare("SELECT * FROM user INNER JOIN userfollow ON user.id = userfollow.user2_id WHERE userfollow.user1_id = :id ");
        $conn->bindValue(":id", $_SESSION["user1_id"]);
        if ($conn->execute()) {
            $selected = $conn->fetchAll();
            $users = [];
            foreach ($selected as $select) {
                $user = new User();
                $user
                    ->setId($select["user2_id"])
                    ->setName($select["name"])
                    ->setIcon($select["icone"]);
                $users[] = $user;
            }
            return $users;
        }
    }

    /**
     * Add a user to database
     * @param string $mail
     * @param string $pass
     * @param string $name
     */
    public function newUser(string $mail, string $pass, string $name){
        $conn = $this->db->prepare("INSERT INTO user (name, pass, mail) VALUES (:name, :pass, :mail)");
        $conn->bindValue(":name", $name);
        $conn->bindValue(":mail", $mail);
        $conn->bindValue(":pass", password_hash($pass,PASSWORD_DEFAULT));
        $conn->execute();
    }

    /**
     * Return user's followed
     * @return array
     */
    public function getFollowedUser() :array{
        $conn = $this->db->prepare("SELECT u.id, u.name FROM user as u INNER JOIN userfollow ON user2_id = u.id WHERE user1_id = :id");
        $conn->bindValue(":id", $_SESSION["user1_id"]);
        $users = [];
        if($conn->execute()){
            $selected = $conn->fetchAll();
            foreach ($selected as $select){
                $user = new User();
                $user
                    ->setId($select["id"])
                    ->setName($select["name"]);
                $users[] = $user;
            }
        }
        return $users;
    }

}