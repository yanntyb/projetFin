<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/userRequire_once.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Manager/ProjetManager.php";

use App\Manager\UserManager;
use App\Entity\User;
use App\Manager\ProjetManager;

$userManager = new UserManager();
$projectManager = new ProjetManager();

header('Content-Type: application/json');

$requestType = $_SERVER['REQUEST_METHOD'];


switch($requestType) {
    case 'GET':
        if(isset($_GET["user"])) {
            if(isset($_GET["action"])){
                if($_GET["action"] === "profile"){
                    echo getUserInfo(intval($_GET["user"]), $userManager);
                    break;
                }
                if($_GET["action"] === "follow"){
                    echo getFollow($userManager,  $_GET["user"]);
                    break;
                }
            }
            else{
                echo getUser(intval($_GET["user"]), $userManager);
                break;
            }
            break;
        }
        if(isset($_GET["action"])){
            if($_GET["action"] === "followed"){
                echo getFollowedUser($userManager);
            }
            if($_GET["action"] === "project"){
                if(isset($_GET["id"])){
                    echo getProjectUsers($projectManager, $_GET["id"]);
                }
            }
        }
    default:
        break;
}

/**
 * Return user's name
 * @param int $id
 * @param UserManager $manager
 * @return false|string
 */
function getUser(int $id, UserManager $manager) {
    $result = $manager->getUsernameById($id);
    return json_encode($result);
}

/**
 * Return all of the user's info (used to display user's profile)
 * @param int $id
 * @param UserManager $manager
 * @return false|string
 */
function getUserInfo(int $id, UserManager $manager){
    $result = $manager->getAllInfoById($id);
    $editable = "false";
    if($id === $_SESSION["user1_id"]){
        $editable = "true";
    }
    $user = [
        "id" => $result->getId(),
        "name" => $result->getName(),
        "pass" => $result->getPass(),
        "mail" => $result->getMail(),
        "lien" => $result->getLien(),
        "icon" => $result->getIcon(),
        "bio" => $result->getBio(),
        "editable" => $editable
    ];
    return json_encode($user);
}

/**
 * Return follow state (false or true)
 * @param UserManager $manager
 * @param int $id
 * @return false|string
 */
function getFollow(UserManager $manager, int $id){
    return json_encode([
        "follow" => $manager->getFollow($id),
        "id" => $id,
    ]);
}

/**
 * return session's user followed one
 * @param UserManager $manager
 * @return false|string
 */
function getFollowedUser(UserManager $manager){
    $users = $manager->getFollowedUser();
    $return = [];
    foreach($users as $user){
        $return[] = [
            "name" => $user->getName(),
            "id" => $user->getId()
        ];
    }
    return json_encode($return);
}

/**
 * Return project's users
 * @param ProjetManager $manager
 * @param int $id
 * @return false|string
 */
function getProjectUsers(ProjetManager $manager , int $id){
    $users = $manager->getProjectUsers($id);
    $return = [];
    foreach($users as $user){
        $return[] = [
            "name" => $user->getName(),
            "link" => $user->getLien(),
        ];
    }
    return json_encode($return);
}