import {MessageSingle} from "./MessageSingle.js";

let Channel = function(id){
    this.id = id;
    this.parent = document.getElementById("showMessage");
    this.parent.className = "channelMessage";
    this.data = null;
    this.child = [];
}

/**
 * Debug function
 * @param data
 */
Channel.prototype.show = function(data){
    for(let message of data){
        this.child.push(new MessageSingle(message));
    }
    for(let child of this.child){
        child.setContent();
        this.parent.append(child.div);
    }
}


export {Channel};