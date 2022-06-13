const select = document.getElementById("edit-create-new");

select.addEventListener("change", function handleChange(event) {
  console.log(event.target.value);
  var formPage = "";
  var getUrl = window.location;

  var baseUrl =
    getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split("/")[1];
  console.log("base: ", baseUrl);
  // 👉️ get selected VALUE

  // 👇️ get selected VALUE even outside event handler
  //console.log(select.options[select.selectedIndex].value);

  // 👇️ get selected TEXT in or outside event handler
  // console.log(select.options[select.selectedIndex].text);

  switch (event.target.value) {
    case "pr":
      formPage = "producer";
      break;
    case "awo":
      formPage = "awardee";
      break;
    case "proj":
      formPage = "project";
      break;
    case "ltm":
      formPage = "lab_test_profiles_admin";
      break;
    default:
      formPage = "producer";
  }

  window.location.assign(baseUrl + "/" + formPage);
});
