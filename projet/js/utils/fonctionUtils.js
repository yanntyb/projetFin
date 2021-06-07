const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

/**
 * Set child element of given DOM element to z-index 0
 * @param parent
 */
function resetDataDivZindex(parent){
    let dataDivChildren = document.getElementById(parent.toString()).children;
    for(let child of dataDivChildren){
        child.style.zIndex = "0";
    }
}

/**
 * Set element to given z-index value
 * @param zindex
 * @param div
 */
function setDivZindex(zindex, div){
    div.style.zIndex = zindex.toString();
}

/**
 * Remove element from DOM
 * @param div
 */
function removeDiv(div){
    div.parentNode.removeChild(div);
}

/**
 * Return curent date
 * @returns {string}
 */
function getDate(){
    let date = new Date();
    let day = days[date.getDay()];
    let daynumber = date.getDate();
    let month = monthNames[date.getMonth()];
    let year = date.getFullYear();
    return day + " " + daynumber + "th of " + month + " " + year;
}

export {resetDataDivZindex, setDivZindex, removeDiv, getDate};