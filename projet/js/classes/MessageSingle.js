let MessageSingle = function(data){
    this.div = document.createElement("div");
    this.div.className = "singleMessageDiv";
    this.data = data;
}


/**
 * Set content of single private message
 */
MessageSingle.prototype.setContent = function(){
    if(this.data.sent) {
        this.div.className += " sent";
    }
    this.div.innerHTML = `
        <div class="messageContent">
            <div class="data">
                <div class="pseudo">${this.data.pseudo}</div>
                <div class="text">${this.data.message}</div>
                <div class="date">${this.data.date}</div>
            </div>
        </div>
    `;


}

export {MessageSingle};