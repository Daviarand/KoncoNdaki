// Inisialisasi dropdown profile dan mobile menu
document.addEventListener("DOMContentLoaded", function() {
  // Dropdown profile
  const profileBtn = document.getElementById("profileBtn");
  const profileMenu = document.getElementById("profileMenu");

  if (profileBtn && profileMenu) {
    profileBtn.addEventListener("click", function(e) {
      e.stopPropagation();
      profileMenu.classList.toggle("active");
    });

    document.addEventListener("click", function(e) {
      if (!profileMenu.contains(e.target) && e.target !== profileBtn) {
        profileMenu.classList.remove("active");
      }
    });

    profileMenu.addEventListener("click", function(e) {
      e.stopPropagation();
    });
  }

  // Mobile menu
  const menuBtn = document.querySelector(".mobile-menu-btn");
  const mobileNav = document.getElementById("mobile-nav");
  const menuIcon = document.getElementById("menu-icon");

  if (menuBtn && mobileNav && menuIcon) {
    menuBtn.addEventListener("click", () => {
      mobileNav.classList.toggle("active");
      if (mobileNav.classList.contains("active")) {
        menuIcon.classList.remove("fa-bars");
        menuIcon.classList.add("fa-times");
      } else {
        menuIcon.classList.remove("fa-times");
        menuIcon.classList.add("fa-bars");
      }
    });

    // Tutup mobile menu saat klik link
    const mobileNavLinks = document.querySelectorAll(".mobile-nav-link");
    mobileNavLinks.forEach((link) => {
      link.addEventListener("click", function () {
        if (!this.classList.contains("logout")) {
          mobileNav.classList.remove("active");
          menuIcon.classList.remove("fa-times");
          menuIcon.classList.add("fa-bars");
        }
      });
    });

    // Tutup mobile menu saat klik di luar
    document.addEventListener("click", (event) => {
      const isClickInsideNav = mobileNav.contains(event.target);
      const isClickOnMenuBtn = menuBtn.contains(event.target);

      if (!isClickInsideNav && !isClickOnMenuBtn && mobileNav.classList.contains("active")) {
        mobileNav.classList.remove("active");
        menuIcon.classList.remove("fa-times");
        menuIcon.classList.add("fa-bars");
      }
    });
  }
});