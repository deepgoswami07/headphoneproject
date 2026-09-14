/* Soundphere — minimal front-end JS.
   Cart count, login state, and product data now come from PHP + MySQL,
   rendered server-side in includes/header.php. This file only handles
   small UI niceties that don't need the server. */

document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".burger");
  const links = document.querySelector(".nav-links");
  if (burger && links) {
    burger.addEventListener("click", () => {
      links.classList.toggle("mobile-open");
    });
  }
});
