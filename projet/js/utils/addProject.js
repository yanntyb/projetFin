import {Request} from "../classes/Request.js";
import {removeDiv} from "./fonctionUtils.js";

let addProject = document.querySelector("#addProject i");
let reqNewProject = new Request("project/post.php")
let projectCont = document.getElementById("projet");


/**
 * Event when a super_admin click on new serveur button (+)
 */
addProject.addEventListener("click", (e)=>{
    let input = document.createElement("input");
    input.placeholder = "Project name";
    input.id = "newProjectInput";

    let submit = document.createElement("div");
    submit.id = "newProjectSubmit";
    submit.innerHTML = "<i class=\"far fa-check-circle\"></i>";

    let div = document.createElement("div");
    div.id = "divNewProjectForm";

    //Remove form div if already exist
    if(document.getElementById("newProjectInput")){
        removeDiv(document.getElementById("divNewProjectForm"));
    }
    div.append(input);
    div.append(submit);
    projectCont.append(div);

    submit.addEventListener("click", () => {
        let name = input.value;
        if(name.length > 0){
            removeDiv(document.getElementById("divNewProjectForm"));
            reqNewProject.resetLink();
            reqNewProject.link += "?action=new";
            reqNewProject.setData({"name": name});
            reqNewProject.send();
        }
    })
})

