<?php
include "../include/userRequire_once.php";

use App\Manager\UserManager;

session_start();
if(isset($_POST, $_POST["mail"], $_POST["pass"])){
    $userManager = new UserManager();
    $mail = sanitize($_POST["mail"]);
    $pass = $_POST["pass"];
    //Return User object or null if no user match with $mail
    if(strlen($mail) > 0){
        $user = $userManager->getUserByMail($mail);
        //If given mail exist in database then I check if password correspond to database's one
        if(!is_null($user)){
            if(password_verify($pass,$user->getPass())){
                $_SESSION["user1_id"] = $user->getId();
                $_SESSION["connected"] = true;
                $_SESSION["role"] = $user->getRole();
                header("Location: ../index.php");
            }
            else{
                header("Location: ../index.php");
            }
        }
        else{
            header("Location: ../index.php");
        }
    }
    else{
        header("Location: ../index.php");
    }
}