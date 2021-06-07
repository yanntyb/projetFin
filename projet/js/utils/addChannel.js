import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let input = document.getElementsByTagName("form")[0].querySelector("input[type=text]");

let addChannel = document.getElementsByClassName("addChannel");
let conv = new MessageAll();

let id = false;
let alreadySend = false;

for(let add of addChannel){
    add.addEventListener("click", () => {
        alreadySend = false;

        //Get server's name
        let serverName = add.parentNode.innerText;
        let submit = document.querySelector("input[type=submit]");

        id = add.dataset.id;

        //Reset chat
        conv.resetContent();
        conv.parent.className = "addChannelConv";
        conv.showSingle({"pseudo" : serverName, "message": "Nom du channel a ajouter ?", "date": getDate()});

        //clone submit to delete all event on it
        let submitClone = submit.cloneNode(true);
        submit.parentNode.replaceChild(submitClone,submit);
        submitClone.addEventListener("click", callback.bind(event));
    })
}

//Event function to launch other function
function callback(e){
    e.preventDefault();
    checkInput();
}

/**
 * Send request to api if the input value isnt empty
 */
function checkInput(){
    if(!alreadySend){
        if(conv.parent.className === "addChannelConv"){
            if(input.value.length > 0){
                if(id !== false){
                    let req = new Request("channel/post.php");
                    req.resetLink();
                    req.link += "?action=new";
                    req.setData({"name" : input.value, "id" : id });
                    req.send();
                    conv.showSingle({"pseudo" : "server", "message" : "Channel '" + input.value + "' bien ajouté. Relancez la page pour voir les changements ", "date" : getDate()});
                    alreadySend = true;
                }
            }
        }
    }
}