window.addEventListener("load", () => {
    if(window.location.pathname == "/") {
        if(window.innerWidth <= 1300) {
            document.querySelector(".logo").style = "height: 100px;";
            document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
        } else {
            window.addEventListener("scroll", () => {
                if (window.scrollY > document.querySelector(".banner").clientHeight) {
                    document.querySelector(".logo").style = "height: 100px;";
                    document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
                } else {
                    document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_full.png";
                    document.querySelector(".logo").style = "height: 200px;";
                }
            });
        }
        const items = $(".slider > img");
        const nbslides = items.length;
        const suivant = $(".next_img");
        const precedent = $(".previous_img");
        let count = 0;

        // ----Liens Smooth vers les différentes parties----
        // Lien vers la partie à propos
        document.querySelector("#next_1").addEventListener("click", () => {
            document.getElementById("other_content").scrollIntoView({
                behavior: "smooth",
            });
        });

        document.querySelector("#next_2").addEventListener("click", () => {
            document.getElementById("app_content_title").scrollIntoView({
                behavior: "smooth",
            });
        });

        // ---- Slider ----
        function slideSuivante() {
            items[count].classList.remove('active');
            if(count < nbslides - 1) {
                count++;
            } else {
                count=0;
            }

            items[count].classList.add('active');
        }

        suivant.click(function () {
            slideSuivante();
        });

        function slidePrecedente() {
            items[count].classList.remove('active');
            if(count > 0) {
                count--;
            } else {
                count = nbslides - 1;
            }

            items[count].classList.add('active');
        }

        precedent.click(function () {
            slidePrecedente();
        });

        // slider automatique
        /*const ratio = 0.1;
        const nav = document.querySelector(".main_menu");
        const options = {
            root: null,
            rootMargin: "0px",
            threshold: ratio,
        };*/

        /*const handleIntersect1 = function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.intersectionRatio > ratio) {
                    slideSuivante();
                }
            });
        };

        const observer1 = new IntersectionObserver(handleIntersect1, options);
        observer1.observe(document.querySelector("#other_content"));*/

        document.querySelector("footer").style.display = "block";
    } else if(window.location.pathname.match("/dashboard/") || window.location.pathname.match("/dashboard_[a-zA-Z]{0,8}/") ||  window.location.pathname.match("/oneVM/") || window.location.pathname.match("/oneServer/")) {
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

        // reduced menu on certain width
        if(window.innerWidth <= 900) {
            $(".minimize_menu i").removeClass("fa-caret-square-left");
            $(".minimize_menu i").addClass("fa-caret-square-right");
            document.querySelector("aside.dashboard_menu").style = "width: 5vw !important;";
            document.querySelector("aside.dashboard_menu ul").style = "margin-left: 0 !important; padding-left: 0 !important;";
            document.querySelectorAll("aside.dashboard_menu li a").forEach(element => {
                element.querySelector("span").style = "display: none !important;";
                element.querySelector("i").style = "font-size: 1.7em;";
                element.style = "margin-left: auto; margin-bottom: 7%; padding-right: 40%;";
            });
        }

        // minimize menu
        $(".minimize_menu").click(function () {
            if(document.querySelector(".minimize_menu i").classList.contains("fa-caret-square-left")) {
                $(".minimize_menu i").removeClass("fa-caret-square-left");
                $(".minimize_menu i").addClass("fa-caret-square-right");
                document.querySelector("aside.dashboard_menu").style = "width: 5vw !important;";
                document.querySelector("aside.dashboard_menu ul").style = "margin-left: 0 !important; padding-left: 0 !important;";
                document.querySelectorAll("aside.dashboard_menu li a").forEach(element => {
                    element.querySelector("span").style = "display: none !important;";
                    element.querySelector("i").style = "font-size: 1.7em;";
                    element.style = "margin-left: auto; margin-bottom: 7%; padding-right: 40%;";
                });
            } else if(document.querySelector(".minimize_menu i").classList.contains("fa-caret-square-right")) {
                $(".minimize_menu i").removeClass("fa-caret-square-right");
                $(".minimize_menu i").addClass("fa-caret-square-left");
                document.querySelector("aside.dashboard_menu span").style = "display: inline !important;";
                document.querySelector("aside.dashboard_menu").style = "width: 12vw !important;";
                if(window.innerWidth >= 1300) {
                    document.querySelector("aside.dashboard_menu ul").style = "padding-left: 40px !important;";
                }
                document.querySelectorAll("aside.dashboard_menu li a").forEach(element => {
                    element.querySelector("span").style = "display: inline !important;";
                    element.querySelector("i").style = "font-size: 1em;";
                    element.style = "margin-bottom: 0; padding-right: 6%";
                });
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
