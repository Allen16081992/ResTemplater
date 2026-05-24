"use strict";

(() => {
  function sparkBurst(target, count = 12) {
    if (!target) return;

    // prevent spam bursts every few ms
    if (target.dataset.sparkLock) return;
    target.dataset.sparkLock = "1";
    setTimeout(() => delete target.dataset.sparkLock, 200);

    const cs = getComputedStyle(target);
    if (cs.position === "static") target.style.position = "relative";

    const wrap = document.createElement("div");
    wrap.style.position = "absolute";
    wrap.style.inset = "0";
    wrap.style.overflow = "visible";
    wrap.style.pointerEvents = "none";
    target.appendChild(wrap);

    for (let i = 0; i < count; i++) {
      const p = document.createElement("span");
      p.className = "spark-particle";

      const angle = Math.random() * 360;
      const dist = 20 + Math.random() * 36;

      p.style.setProperty("--th", `${angle}deg`);
      p.style.setProperty("--dist", `${dist}px`);

      const size = 2 + Math.random() * 3;
      p.style.width = `${size}px`;
      p.style.height = `${size}px`;
      p.style.animationDuration = `${0.45 + Math.random() * 0.35}s`;
      wrap.appendChild(p);
    }

    setTimeout(() => wrap.remove(), 800);
  }

  // Defensive: if neither exists, do nothing
  const cta = document.getElementById("cta");
  const signUp = document.getElementById("signUp");
  if (!cta && !signUp) return;

  const wire = (el) => {
    el.addEventListener("mouseenter", (e) => sparkBurst(e.currentTarget));
    el.addEventListener("touchstart", (e) => sparkBurst(e.currentTarget), { passive: true });
  };

  if (cta) wire(cta);
  if (signUp) wire(signUp);
})();