"use strict";

function tabs1(evt, tabGroupId, tabId) {
    var i, y, tabNavs;
    var container = document.getElementById(tabGroupId);
    var x = container.getElementsByClassName("tab-content");

    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }

    tabNavs = evt.target.parentElement.getElementsByClassName("tab-nav");

    for (i = 0; i < x.length; i++) {
        y = tabNavs[i].getAttribute('data-tab-active');
        tabNavs[i].className = tabNavs[i].className.replace(" " + y, "");
    }

    document.getElementById(tabId).style.display = "block";

    y = evt.currentTarget;
    y.className += " " + y.getAttribute('data-tab-active');
}