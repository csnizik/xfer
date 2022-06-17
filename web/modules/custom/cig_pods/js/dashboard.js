const select = document.getElementById("edit-create-new");

select.addEventListener("change", function handleChange(event) {
  var formPage = "";
  var getUrl = window.location;
  var baseUrl = getUrl.protocol + "//" + getUrl.host + "/create";

  switch (event.target.value) {
    case "awo":
      formPage = "awardee_org";
      break;
    case "proj":
      formPage = "project";
      break;
    case "ltm":
      formPage = "lab_testing_method";
      break;
    default:
      formPage = "awardee_org";
  }

  window.location.assign(baseUrl + "/" + formPage);
});
