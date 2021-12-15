window.addEventListener("load", () => {
    window.addEventListener("scroll", () => {
        if (window.scrollY > document.querySelector(".banner").clientHeight) {
            document.querySelector(".logo").style = "height: 100px;";
            document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_light.png";
        } else {
            document.querySelector(".logo").src = "assets/img/Proxmox_pannel_V7.2_full.png";
            document.querySelector(".logo").style = "height: 200px;";
        }
    });
});