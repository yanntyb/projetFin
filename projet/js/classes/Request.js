let Request = function (link, callback, parentObject = null){
    this.data = null;
    this.folder = link;
    this.link = null;
    this.xhr = new XMLHttpRequest();
    this.folderCorrect = false;
    this.onload = callback;
    this.parentObject = parentObject;
}

/**
 * Stringify data
 * @param data
 * @returns {string}
 */
Request.prototype.toJson = function(data){
    return JSON.stringify(data);
}

/**
 * Send request to the specified api (this.link)
 */
Request.prototype.send = function (){
    this.xhr.open('POST', this.link);
    this.xhr.setRequestHeader('Content-Type', 'application/json');
    this.xhr.send(this.toJson(this.data));
}

/**
 * Send request and execute callback function with result (this.onload)
 * @param link
 */
Request.prototype.get = function (link = this.link) {
    this.xhr.open("GET", link);
    this.xhr.setRequestHeader('Content-Type', 'application/json');
    this.xhr.send();
    this.xhr.onload = () => {
        let result = JSON.parse(this.xhr.responseText);
        if(!this.folderCorrect){
            this.folderCorrected = this.folder.split("/");
            this.folderCorrected  = this.folderCorrected [this.folderCorrected .length - 2];
            this.folderCorrect = true;
        }
        this.onload(result);
    };
}

/**
 * set data sent in send prototype
 * @param data
 */
Request.prototype.setData = function(data){
    this.data = data;
}


/**
 * Set real link of the api
 */
Request.prototype.resetLink = function(){
    this.link =  "/api/";
    this.link += this.folder;
}

export {Request};