import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";


let createProject = document.getElementById("createProjet");
let form = document.getElementById("sendMessageForm");
let input = form.querySelector("input[type=text]");
let submit = form.querySelector("input[type=submit]")
let response = [];
let index = 0;
let next = true;

const messages = [
    {"pseudo" : "server", "message": "Quel est le nom du server que vous voulez créer ?", "date" : getDate()},
    {"pseudo" : "server", "message": "Message adressé a l'admin en charge de l'admission ?", "date" : getDate()},
    {"pseudo" : "server", "message": "Merci votre demande a bien été prise en compte", "date" : getDate()},
]

//Request that return already asked project's names
let request = new Request("project/get.php",callback);
request.resetLink();
request.get();


function callback(datas){

    //Create messages with already asked server's name
    let asks = [];
    for(let data of datas){
        asks.push({"pseudo" : "server", "message" : "Nom : " + data["name"], "date": getDate()});
    }

    //Set event on createProject link to show a conversation between server and users to create a new project
    createProject.addEventListener("click", function(e){
        e.preventDefault();
        response = [];
        let createProject = new MessageAll();
        createProject.resetContent();
        createProject.setFirstContent("<div id='sendTo'>Create project</div>");
        createProject.parent.className = "createServeurChat";

        //If user already ask for a project then he cant ask for an other one
        //Show all server's name
        if(datas.length > 0){
           createProject.showSingle({"pseudo": "server", "message": "Vous avez deja un projet en cours d'admission :", "date" : getDate()})
            for(let ask of asks){
                createProject.showSingle(ask);
            }
        }
        else{
            createProject.showSingle(messages[index]);
            submit.addEventListener("click", (e) => {
                e.preventDefault();
                event(createProject);
            });
        }

    })
}


/**
 * Fonction that check question after the user made a sentence
 * @param chat
 */
function event(chat){
    if(input.value.length > 0){
        response.push(input.value);
        next = true;
        index++;
        if(index < messages.length){

            //Show the user's sentence
            chat.showSingle({"pseudo" : "", "message": input.value, "date" : getDate(), "sent" : "true"})
            window.setTimeout(() =>{
                //Show the nex question
                chat.showSingle(messages[index]);
            },200)

            //When all sentence has been asked a request is send to the api that will traite project's admission
            if(index === messages.length-1){
                let reqPostProject = new Request("project/post.php");
                reqPostProject.resetLink();
                reqPostProject.link += "?action=ask";
                reqPostProject.setData(response);
                reqPostProject.send();
            }
        }

    }
}