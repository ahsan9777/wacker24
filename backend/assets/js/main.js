$(document).ready(function () {
    // Toggle sidebar collapse
    $(".toggle-sidebar").click(function () {
        $(".sidebar").toggleClass("collapsed");
        $(".main-content").toggleClass("collapsed");
    });

    // Toggle subcategories
    // $(".menu-item > a").click(function (e) {
    //     e.preventDefault();
    //     const parent = $(this).closest(".menu-item");
    //     parent.toggleClass("open");
    //     parent.find(".sub-menu").slideToggle();
    // });

    // // Load dynamic content
    // $(".menu-link, .sub-menu-link").click(function (e) {
    //     e.preventDefault();
    //     const content = $(this).text();
    //     $("#main-content").html(`<h1>${content}</h1><p>This is the ${content} section.</p>`);
    // });
});

// $(document).ready(function () {
//     // Accordion effect for submenus
//     $(".menu-item > a").click(function (e) {
//         e.preventDefault(); // Prevent default anchor behavior

//         const parent = $(this).closest(".menu-item");
//         const submenu = parent.find(".sub-menu");

//         if (submenu.is(":visible")) {
//             // If the submenu is already visible, slide it up
//             submenu.slideUp();
//             parent.removeClass("open");
//         } else {
//             // Close all other open submenus (optional, for single open submenu behavior)
//             $(".menu-item").not(parent).find(".sub-menu").slideUp();
//             $(".menu-item").not(parent).removeClass("open");

//             // Open the clicked submenu
//             submenu.slideDown();
//             parent.addClass("open");
//         }
//     });
// });


var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}

$(document).ready(function () {
    // Toggle sidebar on small screens
    $(".sidebar-toggle").click(function () {
        $(".sidebar").toggleClass("open");
    });

    // Close sidebar when clicking outside (optional)
    $(document).click(function (e) {
        if (
            !$(e.target).closest(".sidebar").length &&
            !$(e.target).closest(".sidebar-toggle").length
        ) {
            $(".sidebar").removeClass("open");
        }
    });
});

