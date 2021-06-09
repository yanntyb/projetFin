import {Request} from "../classes/Request.js";
import {MessageAll} from "../classes/MessageAll.js";
import {sendMessageEvent} from "./sendMessageFunc.js";

const privateMessageReqGet = new Request("privateMessage/get.php?", callback);
const userReqGet = new Request("user/get.php?", setUserPrivateMessageData)

const form = document.getElementById("sendMessageForm");
let chat = document.getElementById("showMessage");

let title = document.getElementById("usersDisplay");
let scrollPostion;

let user2 = null;
let userSet = false;
let user2name = "";


//MutationObserver is an object I use to catch change on DOM element
let observer = new MutationObserver(function() {
    //Set a event on every private chat links
    let links = document.getElementsByClassName("chatLink");
    for(let link of links){
        link.addEventListener("click", function(e){
            e.preventDefault();

            //set user2 to selected user's id
            user2 = link.dataset.id;
            userReqGet.resetLink();
            userReqGet.link += "user=" + user2;
            chat.className = "privateChat";
            userSet = false;
        })
    }
})




/**
 * Time out to show private message between 2 users
 */
function timeOutRecurePrivateMessage(){
    setTimeout(function(){
        if(user2 !== null && chat.className === "privateChat" ){
            if(!userSet){
                //reset request link to base api url set when object is created line.3
                privateMessageReqGet.resetLink();
                //Add user2 variable that correspond to the user2 id in the link of the api
                //So I open the api with url's parameters
                privateMessageReqGet.link += "user=" + user2.toString();
                userSet = true;

            }

            //get private chat between user2
            privateMessageReqGet.get();

            //set a send message event on the input submit
            sendMessageEvent("privateChat","privateMessage", user2, true);
        }
        timeOutRecurePrivateMessage();
    },1000)
}


/**
 * Callback of get() methode to show top banner info (user 2 name)
 * @param result
 */
function callback(result){
    try{
        scrollPostion = chat.scrollTop;
    }
    catch(e){}
    let privateChat = new MessageAll();
    privateChat.setFirstContent("<div id='sendTo'>" + user2name + "</div>");
    privateChat.show(result);
    userReqGet.get();
    //When the scollFlag is set to false , private chat div with scroll to the last message send
    chat.scrollTop = scrollPostion;

}

/**
 * Set placeholder of send message input to the selected user
 * @param result
 */
function setUserPrivateMessageData(result){
    user2name = result.name;
    form.getElementsByTagName("input")[0].placeholder = "Envoyer un message a " + user2name;
}

observer.observe(title, {subtree: true, childList: true});
timeOutRecurePrivateMessage();
