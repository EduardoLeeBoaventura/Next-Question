function alert_handler(status, msg){
  let alert_element_id = "page-alert-success";
  if(!status){
    alert_element_id = "page-alert-danger";
  }

  const alert_element = document.querySelector(`#${alert_element_id}`);
  const span = document.querySelector(`#${alert_element_id} span`);

  span.innerHTML = msg;

  alert_element.style = '';
}

function clearOptions(select_id){
  const select_option_list = document.querySelector(`[data-to="${select_id}"] .select-options-list`);
  select_option_list.innerHTML = '';
}