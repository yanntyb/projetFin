import {MessageAll} from "../classes/MessageAll.js";

let helps = document.getElementsByClassName("help");

for(let help of helps){
    if(!help.className.includes("infoServe")){
        help.addEventListener("click", (e) => {
            e.preventDefault();
            callback();
        })
    }
}

//Show help messages
function callback(){
    let convHelp = new MessageAll();
    convHelp.resetContent();
    convHelp.setFirstContent("<div id='sendTo'>Help</div>")
    convHelp.parent.className = "";
    convHelp.showSingle({"pseudo" : "server", "message": "TODO help message","date": ""});
}