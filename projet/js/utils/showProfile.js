import {Profile} from "../classes/Profile.js";
import {Request} from "../classes/Request.js";
import {resetDataDivZindex} from "./fonctionUtils.js";
import {setDivZindex} from "./fonctionUtils.js";
import {removeDiv} from "./fonctionUtils.js";

let profileReqGet = new Request("user/get.php?",callback);


let profileLinks = document.getElementsByClassName("profileLink");

//Set event on every profile links included connected user's one
for(let link of profileLinks){
    link.addEventListener("click", function(e){
        e.preventDefault();
        profileReqGet.resetLink();
        profileReqGet.link += "user=" + this.dataset.id + "&action=profile";
        showProfile(this.dataset.id);
    })
}

//Show profile that correspond to a certain id
function showProfile(d){
    resetDataDivZindex("data");
    //If a profile is already showed then it's deleted
    try{
        removeDiv(document.getElementById("profilePage"));
    }
    catch(e){}
    if(document.getElementById("profilePage") === null){
        profileReqGet.get();
    }


}

//Callback of get() methode
function callback(data){
    let profile = new Profile();
    setDivZindex(1,profile.div);
    profile.data = data;
    profile.show();
}