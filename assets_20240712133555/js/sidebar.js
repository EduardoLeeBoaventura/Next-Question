onload_functions.push(sidebarOnload);

function sidebarOnload(){
  const sidebar_collapse = document.querySelectorAll('.sidebar-collapse');

  for(let sidebar_item of sidebar_collapse){
    applyHeightOfCollapse(sidebar_item);

    sidebar_item.addEventListener('mouseenter', ()=>{
      toggleVisibilit(sidebar_item);
    });
    sidebar_item.addEventListener('mouseleave', ()=>{
      toggleVisibilit(sidebar_item);
    });
  }
}

function toggleVisibilitSidebarr(){
  const sidebar = document.querySelector('#sidebar');
  const toggle_sidebarr = document.querySelector('#toggle-sidebarr');

  if(sidebar.classList.contains('visible')){
    sidebar.classList.remove('visible');
    toggle_sidebarr.innerHTML = "arrow_forward";
  } else {
    sidebar.classList.add('visible');
    toggle_sidebarr.innerHTML = "arrow_back";
  }
}

function applyHeightOfCollapse(element){
  let toggle_element_id = element.dataset.subList;
  let toggle_element    = document.getElementById(toggle_element_id);
  let height = 0;

  if(toggle_element){
    for(let child of toggle_element.children){
      height += child.clientHeight;
    }
    
    toggle_element.style.height = `${height}px`;
  }
}

function toggleVisibilit(element){
  const sidebar = document.querySelector('#sidebar');
  let toggle_element_id = element.dataset.subList;
  let toggle_element    = document.getElementById(toggle_element_id);

  if(toggle_element && sidebar.classList.contains('visible')){
    if(toggle_element.classList.contains('hide')){
      toggle_element.classList.remove('hide');
    } else {
      toggle_element.classList.add('hide');
    }
  }
}