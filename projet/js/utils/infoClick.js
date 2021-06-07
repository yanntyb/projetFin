let infos = document.getElementsByClassName("infoClick");

/**
 * Set user's icon to display none so they arent visible without click on them
 */
function resetDisplay(){
    for (let info of infos){
        info.style.display = "none";
    }
}

resetDisplay();


