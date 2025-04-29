document.addEventListener("DOMContentLoaded", function () {
    // ------------------------------
    // Sidebar toggle + submenu logic
    // ------------------------------
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("sidebarCollapse");

    if (toggle && sidebar) {
        toggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
            sidebar.classList.toggle("sidebar-collapsed");
        });

        document.querySelectorAll("#sidebar .dropdown-toggle").forEach(function (el) {
            el.addEventListener("click", function (e) {
                e.preventDefault();
                const next = el.nextElementSibling;
                el.parentElement.classList.toggle("active");
                if (next) next.classList.toggle("collapse");
            });
        });
    }

    // Expand active submenu on page load
    document.querySelectorAll(".menu-item-has-children.current-menu-ancestor > .submenu").forEach(submenu => {
        submenu.style.display = "block";
    });
    // Expand the current page's submenu if inside one
    document.querySelectorAll("#sidebar .menu-item-has-children").forEach(item => {
        if (
            item.classList.contains("current-menu-ancestor") ||
            item.querySelector(".current-menu-item")
        ) {
            const submenu = item.querySelector(".submenu");
            if (submenu) {
                submenu.style.display = "block";
                item.classList.add("active");
            }
        }
    });


});
document.querySelectorAll("#sidebar .dropdown-toggle").forEach(function (el) {
    el.addEventListener("click", function (e) {
        e.preventDefault();

        const parent = el.parentElement;
        const submenu = parent.querySelector(".submenu");

        // Collapse other open menus (optional)
        document.querySelectorAll("#sidebar .menu-item-has-children").forEach(item => {
            if (item !== parent) {
                item.classList.remove("active");
                const sub = item.querySelector(".submenu");
                if (sub) sub.style.display = "none";
            }
        });

        // Toggle current
        parent.classList.toggle("active");
        if (submenu) {
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        }
    });
});
