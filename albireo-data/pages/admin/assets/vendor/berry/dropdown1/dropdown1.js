"use strict";

function dropdownToggle(btn, id, direction = 'down') {
    let content = document.getElementById(id);
    
    // down — default
    content.style['top'] = (btn.offsetHeight + 5) + 'px';
    content.style['left'] = 0;
    
    if (direction == 'right') {
        content.style['top'] = 0;
        content.style['left'] = (btn.offsetWidth + 5) + 'px';
    }
    
    if (direction == 'top') {
        content.style['left'] = 0;
        content.style['top'] = -(btn.offsetWidth + 5) + 'px';
    }
    
    content.classList.toggle("b-block-imp");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropdown-button')) {
        let dropdowns = document.getElementsByClassName("dropdown-content");
        let i;
    
        for (i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            
            if (openDropdown.classList.contains('b-block-imp')) {
                openDropdown.classList.remove('b-block-imp');
            }
        }
    }
}
