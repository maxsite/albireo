"use strict";

function modal1Init() {
    let modals = document.getElementsByClassName("modal-trigger");

    for (let i = 0; i < modals.length; i++) {
        modals[i].addEventListener("click", function (e) {

            let id = this.getAttribute("data-modal");
            let modal = document.getElementById(id);
            let closeIcon = modal.querySelector(".modal-close-icon");
            let closeOther = modal.querySelector(".modal-close");
            let modalclose = modal.getAttribute("data-modalclose");

            if (closeIcon) {
                closeIcon.addEventListener("click", function () {
                    modal.classList.remove("modal-show");
                });
            }

            if (closeOther) {
                closeOther.addEventListener("click", function () {
                    modal.classList.remove("modal-show");
                });
            }

            if (!modalclose) {
                window.addEventListener("click", function (e) {
                    if (e.target === modal)
                        modal.classList.remove("modal-show");
                });
            }

            modal.classList.toggle("modal-show");
            e.preventDefault();
        });
    }
}

// аля ready из jQuery
let ready = (callback) => {
    if (document.readyState != "loading")
        callback();
    else
        document.addEventListener("DOMContentLoaded", callback);
}

ready(() => {
    modal1Init();
});
