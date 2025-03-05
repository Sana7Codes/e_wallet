document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");

    if (menuToggle) {
        menuToggle.addEventListener("click", () => {
            navLinks.classList.toggle("active");
        });
    }

    // Handle Dropdowns
    document.addEventListener("click", (event) => {
        const dropdown = event.target.closest(".dropdown");
        if (!dropdown) {
            document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.remove("active"));
            return;
        }
        event.preventDefault();
        dropdown.querySelector(".dropdown-menu").classList.toggle("active");
    });
});
