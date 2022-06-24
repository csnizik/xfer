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
    case "ltr":
      formPage = "lab_results";
      break;
    case "pro":
      formPage = "producer";
      break;
    //needs to be 'soil_health_sample' when form is created  
    case "ssa":
      formPage = "producer";
      break;
    //needs to be 'soil_health_management_unit' when form is created    
    case "shmu":
      formPage = "producer";
      break;
    //needs to be 'operation' when form is created
    case "oper":
      formPage = "operation";
      break;
    //needs to be 'field_assesment' when form is created  
    case "ifa":
      formPage = "field_assesment";
      break;
    default:
      formPage = "awardee_org";
  }

  window.location.assign(baseUrl + "/" + formPage);
});
