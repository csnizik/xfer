const select = document.getElementById("edit-actions-update-profile");

select.addEventListener("mousedown", function handleChange() {
    var profile = document.getElementById("field_lab_profile");
    var profile_value = profile.options[profile.selectedIndex].text;
    if (profile_value != "- Select -") {
        this.style.display="none";
    }
}, false)
