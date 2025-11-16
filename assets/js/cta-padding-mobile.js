"use strict";
function setCtaPadding() {
  // Portrait + reasonably small width → treat as mobile
  if (window.innerHeight > window.innerWidth && window.innerWidth <= 768) {
    const items = document.querySelectorAll(".cta-band.p-6");

    items.forEach(el => {
      el.classList.remove("p-6");
      el.classList.add("p-5");
    });
  }
}

document.addEventListener("DOMContentLoaded", setCtaPadding);
window.addEventListener("resize", setCtaPadding);