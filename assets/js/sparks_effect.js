"use strict";
function sparkBurst(target, count = 12) {
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
    const dist  = 20 + Math.random() * 36;
    p.style.setProperty("--th", angle + "deg");
    p.style.setProperty("--dist", dist + "px");
    const size = 2 + Math.random() * 3;
    p.style.width = size + "px";
    p.style.height = size + "px";
    p.style.animationDuration = (0.45 + Math.random() * 0.35) + "s";
    wrap.appendChild(p);
  }

  setTimeout(() => wrap.remove(), 800);
}

function burstAt(item, count = 14) {
  const marker = item.querySelector(".pw-emitter");
  if (!marker) return;
  marker.innerHTML = "";
  for (let i = 0; i < count; i++) {
    const p = document.createElement("span");
    p.className = "spark-particle";
    const angle = Math.random() * 360;
    const dist  = 20 + Math.random() * 36;
    p.style.setProperty("--th", angle + "deg");
    p.style.setProperty("--dist", dist + "px");
    const size = 2 + Math.random() * 3;
    p.style.width = size + "px";
    p.style.height = size + "px";
    p.style.animationDuration = (0.45 + Math.random() * 0.35) + "s";
    marker.appendChild(p);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  // Reveal-on-scroll + timeline bursts
  const items = document.querySelectorAll(".pw-item");
  const io = new IntersectionObserver((entries) => {
    for (const e of entries) {
      if (!e.isIntersecting) continue;
      const item = e.target;
      item.classList.add("is-visible");
      burstAt(item);
      io.unobserve(item);
    }
  }, { threshold: 0.25 });
  items.forEach(el => io.observe(el));

  // CTA hover bursts (guard nulls)
  const cta = document.getElementById("cta");
  if (cta) {
    cta.addEventListener("mouseenter", e => sparkBurst(e.currentTarget));
    cta.addEventListener("touchstart", e => sparkBurst(e.currentTarget), { passive: true });
  }
  const signIn = document.getElementById("signIn");
  if (signIn) {
    signIn.addEventListener("mouseenter", e => sparkBurst(e.currentTarget));
    signIn.addEventListener("touchstart", e => sparkBurst(e.currentTarget), { passive: true });
  }
});