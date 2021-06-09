<?php
    include "./include/userRequire_once.php";

    use App\Manager\UserManager;

    $userManager = new UserManager();

    session_start();
    if(isset($_SESSION["connected"]) && $_SESSION["connected"] === true){
        $connected = true;
    }
    else{
        $connected = false;
    }
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Disgit</title>
    <?php include "./include/cssGlobal.php" ?>
</head>
<body>

<?php include "./parts/menu.php" ?>
<div id="cont">
    <?php require_once("./parts/projets.php") ?>
    <section id="channels"></section>
    <section id="data">
        <div id="chat">
            <div id="showMessage"><?php
                if(!$connected){
                    require_once $_SERVER["DOCUMENT_ROOT"] . "/parts/connectform.php";
                }
                ?>

            </div>
            <div id="sendMessage">
                <div id="sendMessageBox">
                    <div id="sendMessageForm">
                        <form>
                            <?php if($connected){?>
                                <input type="text" placeholder="Envoyer un message sur le channel #......">
                                <input type="submit"><?php
                            }else{?>
                                <input disabled="disabled" type="text" placeholder="Vous devez etre connecté pour envoyer un message"><?php
                            }?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="users">
        <ul><?php if($connected){?>
            <h1 class="title" id="usersDisplay">Followed users <i class="fas fa-exchange-alt"></i></h1>
            <?php require_once("./parts/connected.php");
            }?>
        </ul>
    </section>
</div>
</body>
<script src="https://kit.fontawesome.com/78e483bd6f.js" crossorigin="anonymous"></script>
<script src="js/utils/fonctionUtils.js" type="module"></script>
<script src="js/utils/infoClick.js"></script>
<script src="js/classes/Profile.js" type="module"></script>
<script src="js/classes/sendMessage.js" type="module"></script>
<script src="js/utils/sendMessageFunc.js" type="module"></script>
<script src="js/classes/MessageSingle.js" type="module"></script>
<script src="js/classes/MessageAll.js" type="module"></script>
<script src="js/utils/privateMessage.js" type="module"></script>
<script src="js/utils/joinServer.js" type="module"></script>
<script src="js/classes/ChannelSingle.js" type="module"></script>
<script src="js/classes/ChannelAll.js" type="module"></script>
<script src="js/utils/showChannel.js" type="module"></script>
<script src="js/utils/changeUsersDisplay.js" type="module"></script>
<script src="js/utils/deletProject.js" type="module"></script>
<script src="js/utils/sendInvitation.js" type="module"></script>
<script src="js/utils/askAdminProject.js" type="module"></script>
<script src="js/utils/resetInput.js"></script>
<script src="js/utils/showInput.js" type="module"></script>
<script src="js/utils/showProfile.js" type="module"></script>
<script src="js/utils/help.js" type="module"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</html>
