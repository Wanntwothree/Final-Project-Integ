// script.js

// Reload only if page is loaded from back/forward cache
window.addEventListener("pageshow", function (event) {
  if (event.persisted) {
    // Use location.replace to avoid adding duplicate history entries
    window.location.replace(window.location.href);
  }
});
