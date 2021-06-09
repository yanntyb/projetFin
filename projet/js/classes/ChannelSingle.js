let ChannelSingle = function (id) {
    this.div = document.createElement("div");
    this.div.className = "channelSingle";

}

/**
 * Set data
 * @param data
 */
ChannelSingle.prototype.setData = function(data){
    this.data = data;
    this.div.dataset.id = data["id"];
}

/**
 * Append div to DOM
 */
ChannelSingle.prototype.addToDom = function(){
    this.div.innerHTML = `
        <h1 class="channelName">${this.data.name}</h1>    
    `
}

export {ChannelSingle};