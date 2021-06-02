import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {getDate} from "./fonctionUtils.js";

let reqProjectName = new Request("project/get.php", setProjectName);
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

function setProjectName(data){
    let reqInfo = new Request("", callbackInfo);
    reqInfo.get("https://api.github.com/repos/" + data.link + "/events?page=0&per_page="+ perpage);
    page ++;
    next = false;
}


function callbackInfo(data){
    events = [];
    graph.parent.className = "graphConv";
    if(isIterable(data)){
        for(let event of data){
            events.push([event["type"], event["actor"]["login"], event["created_at"]]);
        }
        let userReq = new Request("user/get.php", displayGraph);
        userReq.resetLink()
        userReq.link += "?action=project&id=" + id;
        userReq.get();
    }
    else{
        graph.resetContent();
        graph.setFirstContent("<div id='sendTo'>Last's events on project's github repository</div>")
        graph.showSingle({"pseudo": "server", "message" : "Project isn't link to a valide github repository", "date" : getDate()})
    }
}

function displayGraph(datas){
    let names = [];
    let links = []
    let usersEvent = [];
    let backgroundcolor = [];
    for(let user of datas){
        names.push(user.name);
        links.push(user.link);
        usersEvent.push(0);
        var r = () => Math.random() * 256 >> 0;
        var color = `rgb(${r()}, ${r()}, ${r()})`;

        backgroundcolor.push(color);
    }
    for(let i = 0; i < links.length; i++){
        for(let event of events){
            if(event[1] === links[i]){
                usersEvent[i]++;
            }
        }
    }
    graph.resetContent();
    graph.setFirstContent("<div id='sendTo'>Last's events on project's github repository</div>")
    graph.showSingle({"pseudo": "server", "message" : "<div><canvas id='graph'></canvas></div>", "date": getDate()});
    const data = {
        labels: names,
        datasets: [{
            label: "Lasts Project's events",
            backgroundColor: backgroundcolor,
            borderColor: 'rgb(255, 99, 132)',
            data: usersEvent
        }]
    };
    const config = {
        type: 'pie',
        data: data,
    };
    let myChart = new Chart(
        document.getElementById('graph'),
        config
    );
}


function isIterable(obj) {
    // checks for null and undefined
    if (obj == null) {
        return false;
    }
    return typeof obj[Symbol.iterator] === 'function';
}