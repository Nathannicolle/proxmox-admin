window.addEventListener("load", () => {
    if(window.location.pathname == "/") {
        // ----Liens Smooth vers les différentes parties----
        // Lien vers la partie à propos
        document.querySelector("#next_1").addEventListener("click", () => {
            document.getElementById("other_content").scrollIntoView({
                behavior: "smooth",
            });
        });

        document.querySelector("#next_2").addEventListener("click", () => {
            document.getElementById("app_content").scrollIntoView({
                behavior: "smooth",
            });
        });

        window.addEventListener("scroll", () => {
            if (window.scrollY > document.querySelector(".banner").clientHeight) {
                document.querySelector(".logo").style = "height: 100px;";
                document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
            } else {
                document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_full.png";
                document.querySelector(".logo").style = "height: 200px;";
            }
        });
    } else if(window.location.pathname == "/dashboard/" || window.location.pathname == "/dashboard_VM/" || window.location.pathname.match("/oneVM/") ||  window.location.pathname == "/dashboard_groups/" || window.location.pathname == "/dashboard_servers/" || window.location.pathname.match("/oneServer/")) {
        document.querySelector(".ui.basic.inverted.segment.main_menu").style = "display: none !important;";
        document.querySelector(".page_container").style = "margin-top: 0; padding-bottom:0;";
        $(".subcategory").click(function () {
            $(".home_card_container").toggle();
            if(document.querySelector("#showbtn").classList.contains("fa-chevron-down")) {
                document.querySelector("#showbtn").classList.remove("fa-chevron-down");
                document.querySelector("#showbtn").classList.add("fa-chevron-right");
            } else {
                document.querySelector("#showbtn").classList.add("fa-chevron-down");
                document.querySelector("#showbtn").classList.remove("fa-chevron-right");
            }
        });

        $(".subcategory_2").click(function () {
            $(".home_card_container_group").toggle();
            if(document.querySelector("#showbtn_2").classList.contains("fa-chevron-down")) {
                document.querySelector("#showbtn_2").classList.remove("fa-chevron-down");
                document.querySelector("#showbtn_2").classList.add("fa-chevron-right");
            } else {
                document.querySelector("#showbtn_2").classList.add("fa-chevron-down");
                document.querySelector("#showbtn_2").classList.remove("fa-chevron-right");
            }
        });

        $(".subcategory_3").click(function () {
            $(".home_card_container_server").toggle();
            if(document.querySelector("#showbtn_3").classList.contains("fa-chevron-down")) {
                document.querySelector("#showbtn_3").classList.remove("fa-chevron-down");
                document.querySelector("#showbtn_3").classList.add("fa-chevron-right");
            } else {
                document.querySelector("#showbtn_3").classList.add("fa-chevron-down");
                document.querySelector("#showbtn_3").classList.remove("fa-chevron-right");
            }
        });
    } else {
        document.querySelector(".logo").style = "height: 100px;";
        document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
    }
});

$('.ui.fluid.dropdown')
    .dropdown({
    });
