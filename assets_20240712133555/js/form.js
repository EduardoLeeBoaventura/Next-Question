// mask functions
function mask(input, persistent_format, placeholder) {
  const value = input.value;
  const mask_formats = input.dataset.format.split('||').sort((v1, v2)=>{
    if(v1.length > v2.length){
      return 1;
    } else {
      return -1;
    }
  });
  
  let acepted_values = "0123456789";
  if(input.dataset.aceptedValues){
    acepted_values = input.dataset.aceptedValues;
  }

  let new_value = filterValueToMask(value, acepted_values);

  let val_arr = new_value.split('');

  let format_arr = selectFormatToMask(mask_formats, new_value);

  let value_final = applyFormat(format_arr, val_arr, persistent_format, placeholder);

  input.value = value_final;
}

function applyFormat(format_arr, val_arr, persistent_format, placeholder){
  let value = "";

  if(persistent_format){
    let placeholder_arr = filterValueToMask(placeholder, "abcdefghijklmnopqrstuvwxyz").split('');

    let delay = placeholder_arr.length - val_arr.length;
    
    let new_value_arr = [];
    for(let i = 0; i < placeholder_arr.length; i++){
      if(i >= delay){
        new_value_arr.push(val_arr[0]);
        val_arr.splice(0, 1);
      } else {
        new_value_arr.push(placeholder_arr[i]);
      }
    }

    val_arr = new_value_arr;
  }

  for (let i in format_arr) {
    const character = format_arr[i];

    if (character == "#") {
      if (val_arr.length > 0) {
        value += val_arr[0];
        val_arr.splice(0, 1);
      } else {
        break;
      }
    } else {
      if (val_arr[0] || val_arr[0] === 0) {
        value += format_arr[i];
      }
    }
  }

  return value;
}

function selectFormatToMask(mask_formats, value){
  let format_arr = [];
  if (mask_formats.length > 1) {
    let preliminary_format = mask_formats[0];
    for (let format of mask_formats) {
      if ((value.length <= filterValueToMask(format, '#').length) || (value.length > filterValueToMask(format, '#').length && filterValueToMask(format, '#').length > filterValueToMask(preliminary_format, '#').length)) {
        preliminary_format = format;
        break;
      }
    }

    format_arr = preliminary_format.split('');
  } else {
    format_arr = mask_formats[0].split('');
  }

  return format_arr;
}

function filterValueToMask(value, acepted_values) {
  let new_value = "";
  for (let num of value) {
    if (acepted_values.indexOf(num.toLowerCase()) > -1) {
      new_value += num;
    }
  }

  return new_value;
}
// mask functions \.

function sendValuesToEditForm(data) {
  for (let id_element in data) {
    const element = document.getElementById(id_element);

    if(element){
      if(!element.classList.contains('select-select')){
        if((element.type == "checkbox" || element.type == "radio") && data[id_element] == 1) {
          element.checked = true;
        } else if (!(element.type == "checkbox" || element.type == "radio")) {
          element.value = data[id_element];
        }
      } else {
        let value = data[id_element];
        if(value){
          if(Array.isArray(data[id_element])){
            applyOption({
              value: data[id_element][0],
              text: data[id_element][1]
            }, id_element);
          }
  
          selectOptionsInSelectPlusToEdit(`[data-to="${id_element}"]`, value);
        }
      }
    } else {
      console.log("Element is not found");
    }
  }
}

function selectOptionsInSelectPlusToEdit(selectPlus_selector, values){
  const options = document.querySelectorAll(`${selectPlus_selector} .select-options-list .selected`);
  if(options){
    for(let option of options){
      option.click();
    }
  }

  if(Array.isArray(values)){
    values.forEach(value=>{
      const option = document.querySelector(`${selectPlus_selector} [data-value="${value}"]`);
      option.click();
    });
  } else {
    const option = document.querySelector(`${selectPlus_selector} [data-value="${values}"]`);
    option.click();
  }
}

async function sendForm(e, form, callback) {
  e.preventDefault();
  
  const form_id     = form.id;
  const form_action = form.action;
  const form_method = form.method;
  
  const form_elements = document.querySelectorAll(`#${form_id} input, #${form_id} select, #${form_id} textarea`);

  let data_obj = {};
  for (let element of form_elements) {
    if (element.name) {
      const name = element.name;
      const value = element.value;

      data_obj[name] = value;
    }
  }

  const options = {
    method: form_method,
  };

  const json = await fetchData(form_action, data_obj, options);

  if(callback){
    callback(json);
  }

  const modal = document.querySelector('.modal.show');

  if(modal){
    const btn_close = document.querySelector('.modal.show .btn-close');

    btn_close.click();
  }
}

async function fetchData(url, data, options){
  let req = "";
  if(options.method.toLowerCase() == 'get'){
    let data_arr = [];
    for (let data_specific in data) {
      const name = data_specific;
      const value = data[data_specific];

      data_arr.push(name + "=" + value);
    }

    req = url + "?" + data_arr.join("&");
  } else {
    req = url;

    options.body = JSON.stringify(data);
  }

  options.headers = {
    'Content-type': 'application/json'
  }

  const response = await fetch(req, options);

  const json = await response.json();

  alert_handler(json.status, json.status_msg);
  
  return json;
}