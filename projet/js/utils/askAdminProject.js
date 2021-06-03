import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let icons = document.getElementsByClassName("askForAdmin");
let conv = new MessageAll();
let submit = document.querySelector("input[type=submit]")
let input = document.getElementsByTagName("form")[0].querySelector("input[type=text]")
let chat = document.getElementById("showMessage");

let message = false;


let checkAskReq = new Request("project/get.php");

for(let icon of icons){
    icon.addEventListener("click", () => {
        conv.resetContent();
        conv.parent.className = "askAdminServ";
        conv.showSingle({"pseudo" : "server", "message": "Vous voulez effectuer une demande pour être admin du server, quel message voulez vous adresser ?", "date": getDate()})

        let submitClone = submit.cloneNode(true);
        submit.parentNode.replaceChild(submitClone,submit);
        if(chat.className === "askAdminServ") {
            submitClone.addEventListener("click", (e) => {
                e.preventDefault();
                callback();
            });
        }
    })
}

function callback(){
    if(input.value.length > 0){
        if(!message){
            conv.showSingle({"pseudo": "user", "message": input.value, "date": getDate(), "sent" : "true"});
            let req = new Request("project/get.php",callbackReq);
            req.resetLink();
            req.link += "?action=checkToken&token=" + input.value;
            req.get();
        }
    }
}