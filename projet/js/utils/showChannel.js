import {Request} from "../classes/Request.js";
import {ChannelAll} from "../classes/ChannelAll.js";
import {ChannelSingle} from "../classes/ChannelSingle.js";
import {MessageAll} from "../classes/MessageAll.js";
import {sendMessageEvent} from "./sendMessageFunc.js";

let reqGet = new Request("channel/get.php", showChannel);
let channelReqGet = new Request("channel/get.php", setChat);

let projects = document.querySelectorAll(".projetCont h1");
let chat = document.getElementById("showMessage");

let scrollPosition;
let idFlag = -1;
let channelName = "loading";
let projectId;


//Add request trigger on project's name to show channels of selected project
for(let project of projects){
    project.addEventListener("click", () => {
        reqGet.resetLink();
        reqGet.link += "?action=channels&id=" + project.dataset.id;
        projectId = project.dataset.id;
        reqGet.get();
    })
}

/**
 * Show channel of a selected project
 * @param datas
 */
function showChannel(datas){
    //Need array conversion to use "for of"
    datas = Object.values(datas[0]);
    datas.pop();
    let Channels = new ChannelAll(projectId);
    for(let data of datas){
        let child = new ChannelSingle(data["project_id"]);
        child.setData(data);
        child.addToDom();
        Channels.childs.push(child);
    }
    Channels.showAll();
    for(let channel of Channels.childs){
        channel.div.addEventListener("click", () => {
            idFlag = channel.div.dataset.id;
            chat.className = "channelMessage";
            channelName = channel.div.innerText;
            interval(idFlag);
        })
    }
}

/**
 * Setup chat banner with selected channel name
 * @param data
 */
function setChat(data){
    let channel = new MessageAll();
    channel.resetContent();
    channel.setFirstContent("<div id='sendTo'>" + channelName + "</div>");
    channel.show(data);
    chat.scrollTop = scrollPosition;
}

/**
 * Set message send event and
 * @param id
 */
function interval(id){
    setTimeout(()=>{
        if(chat.className === "channelMessage" && idFlag === id){
            channelReqGet.resetLink();
            channelReqGet.link += "?action=see&id=" + id;
            scrollPosition = chat.scrollTop;
            channelReqGet.get();
            sendMessageEvent("channelMessage","channel",id);
            interval(id);
        }
    },1000);
}