import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let form = document.getElementById("sendMessage");
let chat = document.getElementById("showMessage");
let connect = document.getElementById("connect");

form.style.display = "none"

//Catch class change on chat to display input only if its necessary
function callback(mutationsList) {
    mutationsList.forEach(mutation => {
        if (mutation.attributeName === 'class') {
            if(chat.className === "" || chat.className === "graphConv"){
                form.style.display = "none";
            }
            else{
                form.style.display = "flex";
            }
        }
    })
}

const mutationObserver = new MutationObserver(callback)

mutationObserver.observe(chat, { attributes: true })

//Only show welcome message when user is connected
if(connect === null){
    let help = new MessageAll();
    help.resetContent();
    help.setFirstContent("<div id='sendTo'>Bienvenue</div>");
    help.showSingle({"pseudo" : "server", "message": "Si vous avez besoin d'aide sur l'utilisation du site, veuillez clicker sur le bouton <i class='help fas fa-info-circle'></i> en haut a droite","date": getDate()})
}


