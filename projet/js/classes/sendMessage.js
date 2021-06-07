import {Request} from "./Request.js";

let SendMessage = function (api){
    this.data = null;
    this.api = api;
}

/**
 * Set data
 * @param data
 */
SendMessage.prototype.setData = function (data){
    this.data = data;
}

/**
 * Send data to the selected api in POST with
 */
SendMessage.prototype.send = function (){
    if(this.data !== null){
        let req = new Request(this.api + "/post.php");
        req.resetLink();
        req.setData(this.data);
        req.send();
    }
}

export {SendMessage};