<?php


namespace App\Manager;

use App\Entity\User;
use App\Traits\GlobalManager;
use App\Entity\Projet;
use App\Manager\UserManager;
use App\Entity\Channel;
use DateTime;

class ProjetManager
{
    use GlobalManager;

    /**
     * Return projet datas from database with a certain id
     * @param int $id
     * @return Projet|false
     */
    public function getProjetById(int $id){
        $conn = $this->db->prepare("SELECT * FROM projet INNER JOIN projetuser ON projetuser.projet_id = projet.id WHERE projet.id = :id");
        $conn->bindValue(":id", $id);
        if($conn->execute()){
            $selected = $conn->fetchAll();
            $users = [];
            $manager = new UserManager();
            foreach($selected as $select){
                    $users[] = $manager->getUsernameById($select["user_id"]);
            }

            $projet = new Projet();
            $projet
                ->setName($selected[0]["name"])
                ->setId($selected[0]["projet_id"])
                ->setLink($selected[0]["link"])
                ->setUsers($users);
            return $projet;
        }
        return false;
    }

    public function getProjet(int $id){
        $conn = $this->db->prepare("SELECT * FROM projet WHERE id = :id");
        $conn->bindValue(":id", $id);
        $conn->execute();
        $select = $conn->fetch();
        $projet = new Projet();
        $projet
            ->setName($select["name"])
            ->setId($select["id"]);
        return $projet;
    }

    /**
     * Get all project where user is admin
     * @param int $id
     * @return array|false
     */
    public function getProjetByUser(int $id){
        $conn = $this->db->prepare("SELECT * FROM projet INNER JOIN projetuser ON projetuser.projet_id = projet.id INNER JOIN projetadmission ON projetadmission.statue = 1 WHERE projetadmission.projet_id = projet.id AND projetuser.user_id = :id ");
        $conn->bindValue(":id", $id);
        if($conn->execute()){
            $projets = [];
            $selected = $conn->fetchAll();
            foreach($selected as $select){
                $projet = new Projet();
                //Get channel
                $conn = $this->db->prepare("SELECT * FROM channel WHERE projet_id = :id");
                $conn->bindValue(":id", $select["projet_id"]);
                $channels = [];
                if($conn->execute()){
                    foreach($conn->fetchAll() as $select2){
                        $channel = new Channel();
                        $channel
                            ->setId($select2["id"])
                            ->setName($select2["name"]);
                        $channels[] = $channel;
                    }
                }
                $projet
                    ->setName($select["name"])
                    ->setLink($select["link"])
                    ->setId($select["projet_id"])
                    ->setChannels($channels);
                $projets[] = $projet;
            }
            return $projets;
        }
        return false;
    }

    /**
     * Return all project ( super_admin fonction )
     * @return array|false
     */
    public function getAllProject(){
        if($_SESSION["role"] === "super_admin"){
            $conn = $this->db->prepare("SELECT * FROM projet");
            if($conn->execute()){
                $projets = [];
                $selected = $conn->fetchAll();
                foreach($selected as $select){
                    $projet = new Projet();
                    //Get project's channels
                    $conn = $this->db->prepare("SELECT * FROM channel WHERE id = :id");
                    $conn->bindValue(":id", $select["id"]);
                    $channels = [];
                    if($conn->execute()){
                        foreach($conn->fetchAll() as $select2){
                            $channel = new Channel();
                            $channel
                                ->setId($select2["id"])
                                ->setName($select2["name"]);
                            $channels[] = $channel;
                        }
                    }
                    $conn = $this->db->prepare("SELECT verif, lastLink FROM projectLinkVerified WHERE projet_fk = :id");
                    $conn->bindValue(":id", $select["id"]);
                    $conn->execute();
                    $link = $conn->fetch();

                    $conn = $this->db->prepare("SELECT statue, message FROM projetadmission WHERE projet_id = :id");
                    $conn->bindValue(":id", $select["id"]);
                    $conn->execute();
                    $state = $conn->fetch();
                    if($state !== false){
                        $projet
                            ->setStat($state["statue"])
                            ->setName($select["name"])
                            ->setLink($select["link"])
                            ->setId($select["id"])
                            ->setLinkVerif($link["verif"])
                            ->setLastLink($link["lastLink"])
                            ->setChannels($channels);
                        $projets[] = $projet;
                    }

                }
                return $projets;
            }
            return false;
        }
        else{
            return false;
        }
    }

    /**
     * Create a project in database
     * @param $name
     * @param $message
     */
    public function addProject($name, $message){
        $conn = $this->db->prepare("INSERT INTO projet (name, link) VALUES (:name, :link)");
        $conn->bindValue(":name", $name);
        $conn->bindValue(":link", "link");
        $conn->execute();

        $id = $this->db->lastInsertId();

        $conn = $this->db->prepare("INSERT INTO projectLinkVerified (projet_fk, verif) VALUES (:id, :verif)");
        $conn->bindValue(":id", $id);
        $conn->bindValue(":verif", "0");
        $conn->execute();

        $conn = $this->db->prepare("INSERT INTO projetadmission (user_id, projet_id, message, statue) VALUES (:userid, :projetid, :message, :statue)");
        $conn->bindValue(":userid", $_SESSION["user1_id"]);
        $conn->bindValue(":projetid", $id);

        //If user is a super_admin then project is directly admit
        if($_SESSION["role"] === "super_admin"){
            $conn->bindValue(":message", "Added by an admin");
            $conn->bindValue(":statue", 1);
        }
        else{
            $conn->bindValue(":message", $message);
            $conn->bindValue(":statue", 0);
        }

        $conn->execute();

        $conn = $this->db->prepare("INSERT INTO projetuser (projet_id, user_id) VALUES (:projetid, :userid)");
        $conn->bindValue(":projetid", $id);
        $conn->bindValue(":userid", $_SESSION["user1_id"]);
        $conn->execute();


    }

    /**
     * Return Project's user already in ask stats
     * @return array
     */
    public function hasAskForProjec(){
        $conn = $this->db->prepare("SELECT * FROM projet INNER JOIN projetuser ON projetuser.projet_id = projet.id INNER JOIN projetadmission ON projetadmission.projet_id = projet.id WHERE projetadmission.statue = 0 AND projetuser.user_id = :id ");
        $conn->bindValue(":id", $_SESSION["user1_id"]);
        $return = [];
        if($conn->execute()){
            $selected = $conn->fetchAll();
            foreach($selected as $select){
                $return[] = [
                    $select["name"]
                ];
            }
        }
        return $return;
    }

    /**
     * Return admission message of a selected project
     * @param int $id
     * @return array
     */
    public function getAdmissionById(int $id){
        $conn = $this->db->prepare("SELECT user_id, message FROM projetadmission WHERE projet_id = :id");
        $conn->bindValue(":id", $id);
        if($conn->execute()){
            $return =  $conn->fetch();
            return [
                "userid" => $return["user_id"],
                "message" => $return["message"]
            ];
        }
    }

    /**
     * Make project admit
     * @param int $id
     */
    public function acceptProject(int $id){
        $conn = $this->db->prepare("UPDATE projetadmission SET statue = 1 WHERE projetadmission.projet_id = :id");
        $conn->bindValue(":id", $id);
        $conn->execute();

        //Get id of the user that asked for the project
        $conn = $this->db->prepare("SELECT user_id FROM projetadmission WHERE projet_id = :id");
        $conn->bindValue(":id", $id);
        $conn->execute();
        $iduser = $conn->fetch()["user_id"];

        //Make user admin of his project
        $conn = $this->db->prepare("INSERT INTO projetadmin (projet_id, user_id) VALUES (:projid, :userid)");
        $conn->bindValue(":projid", $id);
        $conn->bindValue(":userid", $iduser);
        $conn->execute();
    }

    /**
     * Delete ask for server
     * @param int $id
     */
    public function refuseProject(int $id){
        $conn = $this->db->prepare("DELETE FROM projet WHERE id= :id");
        $conn->bindValue(":id", $id);
        $conn->execute();
    }

    /**
     * Return True is connected user is admin of the project
     * @param int $iduser
     * @param int $idserver
     * @return bool
     */
    public function isAdmin(int $iduser, int $idserver){
        $conn = $this->db->prepare("SELECT * FROM projetadmin WHERE projet_id = :idproj AND  user_id = :iduser");
        $conn->bindValue(":idproj", $idserver);
        $conn->bindValue(":iduser", $iduser);
        if($conn->execute()){
            if($conn->fetch()){
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * Return an invitation token for the server selected
     * @param int $id
     * @return false|string
     */
    public function getLink(int $id){
        $link = substr(md5(rand()), 0, 300);
        $date = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 7,date("Y"));
        $conn = $this->db->prepare("INSERT INTO projetlink (link, projet_id, expiration) VALUES (:link, :id, :date)");
        $conn->bindValue(":link", $link);
        $conn->bindValue(":id", $id);
        $conn->bindValue(":date", $date);
        $conn->execute();
        return $link;
    }

    /**
     * Check if an invitation token existe in database
     * @param $link
     * @return false|mixed
     */
    public function checkLink($link){
        $conn = $this->db->prepare("SELECT * FROM projetlink WHERE link = :link");
        $conn->bindValue(":link", $link);
        $conn->execute();
        $info = $conn->fetch();
        if(isset($info["id"])){
            $conn = $this->db->prepare("DELETE FROM projetlink WHERE link = :link");
            $conn->bindValue(":link", $link);
            $conn->execute();
            return $info["projet_id"];
        }
        else{
            return false;
        }
    }

    /**
     * Function used by o2switch setup CRON to delete token expired
     */
    public function checkLinkDuration(){
        $conn = $this->db->prepare("SELECT * FROM projetlink");
        $conn->execute();
        $selected = $conn->fetchAll();
        foreach($selected as $select){
            if($select["expiration"] < mktime(date("H"), date("i"), date("s"), date("m"), date("d"),date("Y"))){
                $conn = $this->db->prepare("DELETE FROM projetlink WHERE id = :id");
                $conn->bindValue(':id', $select["id"]);
                $conn->execute();
            }
        }
    }

    /**
     * Add user to a project
     * @param int $id
     * @return bool
     */
    public function addUserToProject(int $id){
        $conn = $this->db->prepare("SELECT user_id FROM projetuser WHERE projet_id = :id AND user_id = :iduser");
        $conn->bindValue(":id", $id);
        $conn->bindValue(":iduser", $_SESSION["user1_id"]);
        if($conn->execute()){
            if(!$conn->fetch()){
                $conn = $this->db->prepare("INSERT INTO projetuser (user_id, projet_id) VALUES (:userid, :projetid)");
                $conn->bindValue(":userid", $_SESSION["user1_id"]);
                $conn->bindValue(":projetid", $id);
                $conn->execute();
                return true;
            }
            else{
                return false;
            }
        }
        return false;
    }

    /**
     * Delet project
     * @param int $id
     */
    public function deleteProject(int $id){
        $conn = $this->db->prepare("DELETE FROM projet WHERE id = :id");
        $conn->bindValue(":id", $id);
        $conn->execute();
    }

    public function checkAsk(int $id){
    }

    /**
     * Return project link
     * @param int $id
     * @return mixed
     */
    public function getProjectLink(int $id){
        $conn = $this->db->prepare("SELECT link FROM projet WHERE id = :id");
        $conn->bindValue(":id", $id);

        if($conn->execute()){
            return $conn->fetch();
        }
    }

    public function updateProjectLinkVerif(int $id, string $link, int $verif){
        $conn = $this->db->prepare("UPDATE projectLinkVerified (verif, lastLink, lastVerif) VALUES (:verif, :link, :last) WHERE projet_fk = :id");
        $conn->bindValue(":verif", $verif);
        $conn->bindValue(":id", $id);
        $conn->bindValue(":link", $link);
        $conn->bindValue(":last", (new DateTime())->getTimestamp());
        $conn->execute();
    }

    public function getProjectUsers(int $id){
        $conn = $this->db->prepare("SELECT name,lienGithub FROM user INNER JOIN projetuser as p ON p.projet_id = :id WHERE p.user_id = user.id");
        $conn->bindValue(":id", $id);
        $conn->execute();
        $selected = $conn->fetchAll();
        $users = [];
        foreach($selected as $select){
            if($select["lienGithub"] !== ""){
                $user = new User();
                $user
                    ->setName($select["name"])
                    ->setLien($select["lienGithub"]);
                $users[] = $user;
            }
        }
        return $users;
    }
}