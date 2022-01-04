window.addEventListener("load", () => {
    if(window.location.pathname == "/") {
        window.addEventListener("scroll", () => {
            if (window.scrollY > document.querySelector(".banner").clientHeight) {
                document.querySelector(".logo").style = "height: 100px;";
                document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
            } else {
                document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_full.png";
                document.querySelector(".logo").style = "height: 200px;";
            }
        });
    } else if(window.location.pathname == "/dashboard/" || window.location.pathname == "/dashboard_VM/") {
        document.querySelector(".ui.basic.inverted.segment.main_menu").style = "display: none !important;";
        document.querySelector(".page_container").style = "margin-top: 0; padding-bottom:0;";
    } else {
        document.querySelector(".logo").style = "height: 100px;";
        document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
    }
});

$('.ui.fluid.dropdown')
    .dropdown({
    });
