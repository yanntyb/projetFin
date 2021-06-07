import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let link = document.getElementById("joinServer");
let conv = new MessageAll();
let input = document.querySelector("input[type=text]");

let chat = document.getElementById("showMessage");
let token = false;

//Ask for invitation token
link.addEventListener("click", (e) => {
    let submit = document.querySelector("input[type=submit]");
    e.preventDefault();
    conv.resetContent();
    conv.setFirstContent("<div id='sendTo'>Join server</div>");
    conv.parent.className = "joinServerConv";
    conv.showSingle({"pseudo": "server", "message": "Token d'invitation ?", "date": getDate()});

    let submitClone = submit.cloneNode(true);
    submit.parentNode.replaceChild(submitClone,submit);
    if(chat.className === "joinServerConv") {
        submitClone.addEventListener("click", (e) => {
            e.preventDefault();
            callback();
        });
    }
})

/**
 * Show user's message
 */
function callback(){
    if(input.value.length > 0){
        //If user havent already send a token
        if(!token){
            conv.showSingle({"pseudo": "user", "message": input.value, "date": getDate(), "sent" : "true"});
            token = true;
            let req = new Request("project/get.php",callbackReq);
            req.resetLink();
            req.link += "?action=checkToken&token=" + input.value;
            req.get();
        }
    }
}

/**
 * Show message according to token gift by the user
 * @param data
 */
function callbackReq(data){
    if(data["check"]){
        conv.showSingle({"pseudo" : "server", "message": "Vous venez de rejoindre le server " + data["server"], "date" : getDate()});
    }
    else{
        conv.showSingle({"pseudo" : "server", "message": "La clef n'est pas valide ou a expirée", "date" : getDate()});
    }
}