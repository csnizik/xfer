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
      formPage = "lab_test_profiles_admin";
      break;
    case "ltr":
      formPage = "lab_result";
      break;
    case "pro":
      formPage = "producer";
      break;
    case "ssa":
      formPage = "soil_health_sample";
      break;
    case "shmu":
      formPage = "soil_health_management_unit";
      break;
    case "oper":
      formPage = "operation";
      break;
    case "ifa":
      formPage = "field_assesment";
      break;
    default:
      formPage = "awardee_org";
  }

  window.location.assign(baseUrl + "/" + formPage);
});
