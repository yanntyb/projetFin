<?php

require_once("./include/classRequire_once.php");

use App\Manager\ProjetManager;

$manager = new ProjetManager();
$ask = false;
if(isset($_SESSION["user1_id"])){
    if($_SESSION["role"] === "super_admin"){
        $projets = $manager->getAllProject();
        $ask = true;
    }
    else{
        $projets = $manager->getProjetByUser($_SESSION["user1_id"]);
    }
}
else{
    $projets = [];
}

//set admin flag so only admins of a server will charge addChannel.js
$adminFlag = false;

?>

<section id="projet"><?
    if(!empty($projets)){?>
        <h1 class="title">Projects</h1><?php
    }
    foreach ($projets as $projet){
        if($manager->isAdmin($_SESSION["user1_id"],$projet->getId())){
            $admin = true;
            $adminFlag = true;
        }else{
            $admin = false;
        }?>
        <div class="projetCont" >
        <h1 data-id="<?= $projet->getId() ?>"><?= $projet->getName()?>
            </h1><?php if($projet->getStat() === 0){?>
            <i class="fas fa-question" data-stat ="<?php if(is_int($projet->getStat())){
                echo $projet->getStat();
            } ?>" data-id="<?= $projet->getId() ?>"></i><?php
            }
            if($admin || $ask){?>
                <i data-id="<?= $projet->getId() ?>" class="fas fa-link inviteToServer"></i><?php
            }
            if($admin){?>
                <i data-id="<?= $projet->getId() ?>" class="fas fa-plus addChannel"></i>
                <i data-id="<?= $projet->getId() ?>" class="fas fa-cogs modifyLink"></i><?php
            }
            else{if(!$admin && !$ask){?>
            <i data-id="<?= $projet->getId() ?>" class="fas fa-user-shield askForAdmin"></i><?php
            }}
            if($ask){?>
            <i class="far fa-trash-alt delete"></i><?php
            } ?>
        <i data-id="<?= $projet->getId() ?>" class='help fas fa-info-circle infoServe'></i>
    </div><?php
    }
    if(isset($_SESSION["role"]) && $_SESSION["role"] === "super_admin"){?>
        <div id="addProject"><i class="fas fa-plus"></i></div>
        <script src="./js/utils/addProject.js" type="module"></script>
        <?php
    }
    if(!empty($projets)){?>
        <script src="./js/utils/projectInfo.js" type="module"></script><?php
    }
    ?>
</section>
<script src="./js/classes/Request.js" type="module"></script>
<script src="./js/classes/Channel.js" type="module"></script>
<script src="./js/classes/Users.js" type="module"></script>
<?php if($ask){?>
    <script src="./js/utils/projectAdmission.js" type="module"></script><?php
}
if($adminFlag){?>
    <script src="./js/utils/addChannel.js" type="module"></script>
    <script src="./js/utils/sendInvitation.js" type="module"></script>
    <script src="./js/utils/modifProjectLink.js" type="module"></script><?php
}
