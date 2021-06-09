<div id="form">
    <h1 id="connectTitle">DISGIT</h1>
    <div class="formContent">
        <form action="./utils/connectFormCheck.php" method="post">
            <div>
                <input type="email" placeholder="email" name="mail">
            </div>
            <div>
                <input type="password" placeholder="pass" name="pass">
            </div>
            <div>
                <input class="buttonForm" type="submit" value="Connexion">
            </div>
            <div>
                <span id="connect" class="changeForm">INSCRIPTION</span>
            </div>
        </form>
    </div>
    <div class="formContent">
        <form action="./utils/inscription.php" method="post">
            <div>
                <input type="text" placeholder="name" name="name">
            </div>
            <div>
                <input type="email" placeholder="email" name="mail">
            </div>
            <div>
                <input type="password" placeholder="pass" name="pass">
            </div>
            <div>
                <input class="buttonForm" type="submit" value="Inscription">
            </div>
            <div>
                <span class="changeForm">CONNEXION</span>
            </div>
        </form>
    </div>
</div>
<script src="js/utils/connexionChange.js"></script>