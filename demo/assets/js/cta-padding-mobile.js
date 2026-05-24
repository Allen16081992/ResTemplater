"use strict";
(() => {
  const items = document.querySelectorAll(".cta-band.p-6");
  if (items.length === 0) return;

  let t = null;

  function setCtaPadding() {
    const isMobilePortrait = window.innerHeight > window.innerWidth && window.innerWidth <= 768;
    if (!isMobilePortrait) return;

    items.forEach((el) => {
      el.classList.remove("p-6");
      el.classList.add("p-5");
    });
  }

  function onResize() {
    clearTimeout(t);
    t = setTimeout(setCtaPadding, 120);
  }

  // Initial run (defer-safe)
  setCtaPadding();

  // Resize handling
  window.addEventListener("resize", onResize);
})();