let ChannelAll = function(id){
    this.parent = document.getElementById("channels");
    this.parent.dataset.id = id;
    this.childs = [];
}

/**
 * Display all child element to the DOM
 */
ChannelAll.prototype.showAll = function(){
    this.parent.innerHTML = '';
    for(let child of this.childs){
        this.parent.appendChild(child.div);
    }
}

export {ChannelAll}