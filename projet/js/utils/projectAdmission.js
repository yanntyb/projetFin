import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";

let projects = document.querySelectorAll("i[data-stat = '0']");

for(let project of projects){
    project.addEventListener("click", (e) => {
        e.preventDefault();
        let request = new Request("project/get.php", callback);
        request.resetLink();
        request.link += "?id=" + project.dataset.id;
        request.get();
    })
}

/**
 * Show project asked information
 * @param data
 */
function callback(data){
    let conv = new MessageAll();
    conv.resetContent();
    conv.parent.className = "admission";
    conv.showSingle({"pseudo" : "server", "message" : "Nom du server : " + data.name, "date": ""});
    conv.showSingle({"pseudo" : "server", "message" : "Lien Github du projet : " + "<a href='" + data.link +"'>lien</a>", "date" : ""});
    conv.showSingle({"pseudo" : "server", "message" : "Pseudo du demandeur : " + data.username, "date" : ""});
    conv.showSingle({"pseudo" : "server", "message" : "Message : " + data.message, "date" : ""});
    conv.showSingle({"pseudo" : "server", "message" : "<a href=api/project/index.php?ok=yes&id="+ data.id +">Accepter</a> <a href=api/project/index.php?ok=no&id="+ data.id +">Refuser</a>", "date": ""})
}