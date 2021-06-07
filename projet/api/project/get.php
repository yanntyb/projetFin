<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/classRequire_once.php";

use App\Manager\ProjetManager;
use App\Manager\UserManager;

header('Content-Type: application/json');

$requestType = $_SERVER['REQUEST_METHOD'];

$manager = new ProjetManager();


switch($requestType) {
    case 'GET':
        if(isset($_GET["id"])){
            if(isset($_GET['action'])){
                if($_GET['action'] === "link"){
                    echo getlink($manager, $_GET["id"]);
                    break;
                }
                if($_GET["action"] === "check"){
                    echo checkAsk($manager, $_GET["id"]);
                }
                if($_GET["action"] === "name"){
                    echo projectLink($manager, $_GET["id"]);
                }
                if($_GET["action"] === "realLink"){
                    echo getRealLink($manager, $_GET["id"]);
                }
            }
            else{
                $managerUser = new UserManager();
                echo getProjetData($managerUser, $manager, $_GET["id"]);
                break;
            }

        }
        else{
            if(isset($_GET["action"])){
                if($_GET['action'] === "checkToken"){
                    if(isset($_GET["token"])){
                        echo checkToken($manager, $_GET["token"]);
                        break;
                    }
                }
            }
            echo hasAskForProject($manager);
            break;
        }
        break;
    default:
        break;
}


/**
 * Check if user have already asked for a server creation. If so return project's name
 * @param ProjetManager $manager
 * @return false|string
 */
function hasAskForProject(ProjetManager $manager){
    $datas = $manager->hasAskForProjec();
    $return = [];
    foreach($datas as $data){
        $return[] = [
            "name" => $data
        ];
    }
    return json_encode($return);
}

/**
 * Return project's data
 * @param UserManager $usermanager
 * @param ProjetManager $manager
 * @param int $id
 * @return false|string
 */
function getProjetData(UserManager $usermanager, ProjetManager $manager, int $id){
    $project = $manager->getProjetById($id);
    $admission = $manager->getAdmissionById($id);

    $user = $usermanager->getUsernameById($admission["userid"]);

    $return = [
        "name" => $project->getName(),
        "link" => $project->getLink(),
        "username" => $user["name"],
        "message" => $admission["message"],
        "id" => $project->getId()
    ];

    return json_encode($return);
}

/**
 * Return a project's invitation token
 * @param ProjetManager $manager
 * @param int $id
 * @return false|string
 */
function getLink(ProjetManager $manager, int $id){
    $link = $manager->getLink($id);
    return json_encode($link);
}

/**
 * Check if token exist then add user to the corresponding project
 * @param ProjetManager $manager
 * @param string $link
 * @return false|string
 */
function checkToken(ProjetManager $manager, string $link){
    $result = $manager->checkLink($link);
    if($result !== false){
        $server = $manager->getProjet($result);
        if($manager->addUserToProject($server->getId())){
            return json_encode(["check" => true, "server" => $server->getName()]);
        }
        else{
            return json_encode(["check" => false]);
        }
    }
    else{
        return json_encode(["check" => false]);
    }
}

/**
 * Return project's link
 * @param ProjetManager $manager
 * @param int $id
 * @return false|string
 */
function projectLink(ProjetManager $manager, int $id){
    $result = $manager->getProjectLink($id);
    return json_encode($result);
}

/**
 * Duplicate of projectLink
 * @param ProjetManager $manager
 * @param int $id
 * @return false|string
 */
function getRealLink(ProjetManager $manager, int $id){
    $result = $manager->getRealLink($id);
    return json_encode(["link" => $result ]);
}

