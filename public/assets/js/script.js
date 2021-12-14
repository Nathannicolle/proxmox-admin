window.addEventListener("scroll", () => {
    if (window.scrollY > document.querySelector(".banner").clientHeight) {
        document.querySelector("img").src = "Proxmox pannel V7.2_light.png";
        document.querySelector("img").style = "width: 100px; height: auto;";
    } else {
        document.querySelector("img").src = "Proxmox pannel V7.2_full.png";
        document.querySelector("img").style = "width: 300px; height: auto;";
    }
});