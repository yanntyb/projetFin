import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let buttons = document.getElementsByClassName("modifyLink");
let input = document.querySelectorAll("input[type=text]")[0];
let submit = document.querySelectorAll("input[type=submit]")[0]


let id = 0;
let sent = false;


let reqGet = new Request("project/get.php",showConv)
let conv = new MessageAll();

//Event on modifyLink button
for(let button of buttons){
    button.addEventListener("click", () => {
        id = button.dataset.id;
        reqGet.resetLink();
        reqGet.link += "?action=realLink&id=" + id;
        reqGet.get();
    })
}

/**
 * Show first message to display actual server link
 * @param data
 */
function showConv(data){
    sent = false;
    conv.parent.className = "modifLink";
    conv.resetContent();
    conv.showSingle({"pseudo": "server", "message": `Le lien actuel du projet est <br><div style="color: red">${data.link}</div>`, "date" : getDate()});
    let reqInfo = new Request("", showLinkVerif)
    reqInfo.get("https://api.github.com/repos/" + data.link + "/events?page=0");
}

/**
 * Show second message to display if the link is actually good or not
 * @param data
 */
function showLinkVerif(data){

    if("message" in data){
        conv.showSingle({"pseudo": "server", "message": "Le lien n'est actuellement pas valide et ne pointe pas vers un repo Github. Quel est le nouveau lien que vous voulez associer au projet ?", "date" : getDate()});
    }
    else{
        conv.showSingle({"pseudo": "server", "message": "Le lien est actuellement valide et pointe bien vers un repo Github. Quel est le nouveau lien que vous voulez associer au projet ?", "date" : getDate()});
    }
    let submitClone = submit.cloneNode(true);
    submit.parentNode.replaceChild(submitClone,submit);
    if(chat.className === "modifLink") {
        submitClone.addEventListener("click", (e) => {
            e.preventDefault();
            callback();
        });
    }
}

/**
 * Ask user to set the new link of the repos
 */
function callback(){
    if(!sent){
        if(input.value.length > 0 ){
            sent = true;
            conv.showSingle({"pseudo": "user", "message": input.value, "date" : getDate(), "sent" : "true"})
            let reqModif = new Request("project/post.php");
            reqModif.resetLink();
            reqModif.link += "?action=updateLink";
            reqModif.setData({"link": input.value, "id": id});
            reqModif.send();
            conv.showSingle({"pseudo" : "server", "message" : "Lien modifié avec succes", "date" : getDate()});
        }
    }

}