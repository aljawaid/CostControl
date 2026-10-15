// KANBOARD PLUGIN ASSET FILE
// Scrolls anchor controls accessibly. Related coverage: tests/maintenance_contract.php.

document.addEventListener("DOMContentLoaded", () => {
    const links = document.querySelectorAll('a[href^="#main"], a[href^="#PluginTop"]');
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

    links.forEach((link) => {
        link.addEventListener("click", (event) => {
            const target = document.querySelector(link.getAttribute("href"));

            if (target === null) {
                return;
            }

            event.preventDefault();
            window.scrollTo({
                top: target.getBoundingClientRect().top + window.scrollY - 100,
                behavior: reducedMotion.matches ? "auto" : "smooth"
            });
        });
    });
});
