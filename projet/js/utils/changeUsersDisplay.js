import {Request} from "../classes/Request.js";
import {removeDiv, resetDataDivZindex, setDivZindex} from "./fonctionUtils.js";
import {Profile} from "../classes/Profile.js";

let followedRequest = new Request("user/get.php", callback);
let channelUsers = new Request("channel/get.php", callback);

let profileReqGet = new Request("user/get.php?",callbackProfile);

const titles = ["Followed users <i class='fas fa-exchange-alt'></i>", "Project's users <i class='fas fa-exchange-alt'></i>"]
let id = 0;

let displayTitle = document.getElementById("usersDisplay");
let infos = document.getElementsByClassName("infoClick");
let profileLinks = document.getElementsByClassName("profileLink");

//Change title and ask information of the selected api (followed users or project's users)
displayTitle.addEventListener("click", () => {
    id += 1;
    if(id === 2){
        id = 0;
    }
    displayTitle.innerHTML = titles[id];

    if(id === 0){
        followedRequest.resetLink();
        followedRequest.link += "?action=followed";
        followedRequest.get();
    }
    else{
        let id = document.getElementById("channels").dataset.id;
        channelUsers.resetLink();
        channelUsers.link += "?action=users&id=" + id;
        channelUsers.get();
    }
})

//Show user (channel's ones are followed ones)
function callback(data){
    let parent = document.getElementById("users");
    parent.innerHTML = '';

    //Create user list
    let ul = document.createElement("ul");
    displayTitle.innerHTML = titles[id];
    ul.append(displayTitle);
    for(let user of data){
        let li= document.createElement("li");
        li.innerHTML = `
            <div class="profile">
                <span id="name">${user.name}</span>
            <div class="infoClick">
                <a class="chatLink" data-id="${user.id}" href="#"><i class="far fa-comments"></i></a>
                <a class="profileLink" data-id="${user.id}" href="#"><i class="far fa-user"></i></a>
            </div>
            </div>
        `
        ul.append(li);
    }
    parent.append(ul);

    //Get
    let userList = document.getElementsByClassName("profile");
    profileLinks = document.getElementsByClassName("profileLink");
    infos = document.getElementsByClassName("infoClick");

    //Set event on click on user's name
    for(let user of userList){
        user.addEventListener("click", () => showOption(user));
    }

    //Set event on profile icons to display them
    for(let link of profileLinks){
        link.addEventListener("click", function(e){
            e.preventDefault();
            profileReqGet.resetLink();
            profileReqGet.link += "user=" + this.dataset.id + "&action=profile";
            showProfile(this.dataset.id);
        })
    }



    resetDisplay();

}

//Set info's icons hidden
function resetDisplay(){
    for (let info of infos){
        info.style.display = "none";
    }
}

//Show action icon
function showOption(elem){
    let info = elem.getElementsByClassName("infoClick")[0];
    resetDisplay();
    if(info.style.display === "none"){
        info.style.display = "block";
    }
    else{
        info.style.display = "none";
    }
}

//Show profile that correspond to a certain id
function showProfile(d){
    resetDataDivZindex("data");
    //If a profile is already showed then it's deleted
    try{
        removeDiv(document.getElementById("profilePage"));
    }
    catch(e){}
    if(document.getElementById("profilePage") === null) {
        profileReqGet.get();
    }
}

//Callback of get() methode
function callbackProfile(data){
    let profile = new Profile();
    setDivZindex(1,profile.div);
    profile.data = data;
    profile.show();
}