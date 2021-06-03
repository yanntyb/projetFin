<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/classRequire_once.php";

use App\Manager\ProjetManager;

header('Content-Type: application/json');

$requestType = $_SERVER['REQUEST_METHOD'];

$manager = new ProjetManager();

switch($requestType) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        if(isset($_GET, $_GET["action"])){
            if($_GET["action"] === "new"){
                addProject($manager, $data->name);
                break;
            }
            if($_GET["action"] === "admin"){
                setProjectAdmin($manager, $data->id);
                break;
            }
            if($_GET["action"] === "ask"){
                askForProject($manager, $data);
                break;
            }
            if($_GET["action"] === "delete"){
                deleteProject($manager, $data->id);
                break;
            }
            if($_GET["action"] === "updateLink"){
                updateLink($manager, $data->link, $data->id);
                break;
            }
        }
        break;
    default:
        break;
}

/**
 * Add a project to database
 * @param ProjetManager $manager
 * @param string $name
 */
function addProject(ProjetManager $manager, string $name = ""){
    $manager->addProject(sanitize($name),sanitize($name));
}

function setProjectAdmin(ProjetManager $manager, int $id){

}

/**
 * Ask to a superAdmin for server creation
 * @param ProjetManager $manager
 * @param array $data
 */
function askForProject(ProjetManager $manager, array $data){
    $projectName = $data[0];
    $message = $data[1];
    $manager->addProject(sanitize($projectName), sanitize($message));
}

/**
 * Delete project
 * @param ProjetManager $manager
 * @param int $id
 */
function deleteProject(ProjetManager $manager, int $id){
    $manager->deleteProject($id);
}

function addUserToProject(ProjetManager $manager){

}

function updateLink(ProjetManager $manager, string $new, int $id){
    $manager->updateLink($new, $id);
}