import {Request} from "./Request.js";
import {Profile} from "./Profile.js";

import {removeDiv, resetDataDivZindex, setDivZindex} from "../utils/fonctionUtils.js";

let Users = function(data){
    this.parent = document.getElementById("channelUsers");
}

/**
 * Set data
 * @param data
 */
Users.prototype.setData = function(data){
    this.data = data;
}


/**
 * Show user's names and add event on click to show icons (message and profile)
 */
Users.prototype.show = function(){
    this.parent.innerHTML = "<h1 class='usersTitle'>Project's users</h1>";
    for(let user of this.data){
        let div = document.createElement("div");
        div.className = "channelUser";
        div.innerHTML = `
            <div class="channelUserSolo">
                <div class="channelUserName">${user.name}</div>
                <div class="infoClick">
                    <a class="profileLink" data-id="${user.id}" href="#"><i class="far fa-user"></i></a>
                </div>
            </div>
            
        `;
        this.parent.appendChild(div);

    }

    //Request to get the profile
    let profileReqGet = new Request("user/get.php?",callback);
    let profile = new Profile();

    let profileLinks = document.getElementsByClassName("profileLink");

    //Event when click on profile link
    for(let link of profileLinks){
        link.addEventListener("click", function(e){
            e.preventDefault();
            profileReqGet.resetLink();
            profileReqGet.link += "user=" + this.dataset.id + "&action=profile";

            //Show profile of the clicked user's one
            showProfile(this.dataset.id);
        })
    }

    function showProfile(){
        resetDataDivZindex("data");
        //If a profile is already showed then it's deleted
        try{
            removeDiv(document.getElementById("profilePage"));
        }
        catch(e){}

        profileReqGet.get();
        setDivZindex(1,profile.div);

    }

    function callback(data){
        profile.data = data;
        profile.show();
    }
}

export {Users};