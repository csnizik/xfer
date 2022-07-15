const select = document.getElementById("edit-actions-update-profile");
console.log(select);

const divCollapse = document.getElementById("autoload_button");

const autoLoadDiv = document.getElementById("edit-autoload-container-field-lab-method-aggregate-stability-method");

select.addEventListener("change", function handleChange(event){
    console.log("Change happened");
    select.click();
    select.click();
    select.click();
})

select.addEventListener("mousedown", function handleChange(event) {
    console.log("Clicked 5");
    // this.style.color="red";
    // this.style.borderColor="red";
    // this.disabled = true;
    this.style.display="none";

})



// select.onClick="this.disabled=true";