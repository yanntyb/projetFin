import {Request} from "../classes/Request.js";

let deleteReq = new Request("project/post.php");

let deletes = document.getElementsByClassName("delete");

//Delet project when click on icon
for(let delet of deletes){
    delet.addEventListener("click", () => {
        console.log(delet.parentNode)
        deleteProject(delet.parentNode.getElementsByTagName("h1")[0].dataset.id);
    })
}

/**
 * Delet project with given id
 * @param id
 */
function deleteProject(id){
    deleteReq.resetLink();
    deleteReq.link += "?action=delete";
    deleteReq.setData({"id":id});
    deleteReq.send();
}