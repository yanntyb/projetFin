import {MessageSingle} from "./MessageSingle.js";

let MessageAll = function(id){
    this.id = id;
    this.parent = document.getElementById("showMessage");
    this.data = null;
    this.child = [];
}

/**
 * Display all child to DOM
 * @param data
 */
MessageAll.prototype.show = function(data){
    for(let message of data){
        this.child.push(new MessageSingle(message));
    }
    for(let child of this.child){
        child.setContent();
        this.parent.append(child.div);

        //set date height then I can place date to bottom of text element
        let text = child.div.getElementsByClassName("text")[0];
        let date = child.div.getElementsByClassName("date")[0];

        //Set date position relative to text element
        date.style.height = getComputedStyle(text).height;
    }
}

//Reset chat
MessageAll.prototype.resetContent = function(user){
    this.parent.innerHTML = "";
}

//Set first content usualy channel name
MessageAll.prototype.setFirstContent = function(content){
    this.parent.innerHTML = content;
}

//Function to show a single message
MessageAll.prototype.showSingle = function(data){
    let message = new MessageSingle(data);
    message.setContent();
    this.parent.append(message.div);
}

export {MessageAll};