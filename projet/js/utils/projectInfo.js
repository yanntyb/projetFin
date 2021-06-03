import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let reqProjectName = new Request("project/get.php", setProjectLink);
let perpage = 100;

let events = [];
let morePage = true
let page = 1;
let next = true;
let id = 0;

let graph = new MessageAll();


let infos = document.getElementsByClassName("infoServe");

for(let info of infos){
    info.addEventListener("click", () => {
        reqProjectName.resetLink();
        id = info.dataset.id;
        reqProjectName.link += "?action=name&id=" + id;
        reqProjectName.get();
    })
}

//Set project link and get event according to this link
function setProjectLink(data){
    let reqInfo = new Request("", callbackInfo);
    reqInfo.get("https://api.github.com/repos/" + data.link + "/events?page=0&per_page="+ perpage);
    page ++;
    next = false;
}


//Set events array with data received
function callbackInfo(data){
    events = [];
    graph.parent.className = "graphConv";
    //If data received is Iterable then it mean that the project's link is good and the github request returned what we want
    if(isIterable(data)){
        for(let event of data){
            events.push([event["type"], event["actor"]["login"], event["created_at"]]);
        }
        //If there is event then display the graphic
        if(events.length > 0 ){
            displayGraph();
        }
        else{
            graph.resetContent();
            graph.setFirstContent("<div id='sendTo'>Last's events on project's github repository</div>")
            graph.showSingle({"pseudo": "server", "message" : "Project isn't linked to a valide github repository or have no event", "date" : getDate()})
        }

    }
    else{
        graph.resetContent();
        graph.setFirstContent("<div id='sendTo'>Last's events on project's github repository</div>")
        graph.showSingle({"pseudo": "server", "message" : "Project isn't linked to a valide github repository", "date" : getDate()})
    }
}

//Display a graph showing all user's participation to project
function displayGraph(){
    let users = [];
    let usersEvent = [];
    let backgroundcolor = [];
    //Setup usernames
    for(let event of events){
        if(!users.includes(event[1])){
            users.push(event[1]);
        }
    }
    for(let user of users){
        //Create a random rgb color
        var r = () => Math.random() * 256 >> 0;
        var color = `rgb(${r()}, ${r()}, ${r()})`;
        backgroundcolor.push(color);

        //Set event's number according to username
        for(let event of events){
            if(event[1] === user){
                let keys = Object.keys(usersEvent);
                if(!keys.includes(user)){
                    usersEvent[user] = 1;
                }
                else{
                    usersEvent[user]++;
                }
            }
        }
    }

    //Set array values
    usersEvent = Object.keys(usersEvent).map(k => usersEvent[k])

    graph.resetContent();
    graph.setFirstContent("<div id='sendTo'>Last's events on project's github repository</div>")

    //Create a <canvas> where graph will be displayed
    graph.showSingle({"pseudo": "server", "message" : "<div><canvas id='graph'></canvas></div>", "date": getDate()});
    const data = {
        labels: users,
        datasets: [{
            label: "Lasts Project's events",
            backgroundColor: backgroundcolor,
            borderColor: 'rgb(255, 99, 132)',
            data: usersEvent,
            borderWidth: 0,
        }]
    };
    const config = {
        type: 'pie',
        data: data,

    };

    //Create the graphics according to the config
    if(events.length > 0){
       (new Chart(
            document.getElementById('graph'),
            config
        ));
    }
}

//Check if an object is iterable
function isIterable(obj) {
    // checks for null and undefined
    if (obj == null) {
        return false;
    }
    return typeof obj[Symbol.iterator] === 'function';
}
//
