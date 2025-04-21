// Menu burger
document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger-menu');
  const navContainer = document.querySelector('.nav-container');

  burger.addEventListener('click', function () {
    navContainer.classList.toggle('open');
  });
});

// Scroll de la barre de navigation
document.addEventListener('DOMContentLoaded', function () {
  window.addEventListener('scroll', function () {
    const navContainer = document.querySelector('.nav-container');
    if (window.pageYOffset > 50) {
      navContainer.classList.add('scrolled');
    } else {
      navContainer.classList.remove('scrolled');
    }
  });
});

// Remplace l'image du produit dynamiquement
document.addEventListener("DOMContentLoaded", function () {
  var thumbnails = document.querySelectorAll(".thumbnail");
  var mainImage = document.getElementById("mainImage");

  thumbnails.forEach(function (thumb) {
    thumb.addEventListener("click", function () {
      var clickedSrc = this.getAttribute("data-image");
      if (clickedSrc) {
        var temp = mainImage.src;
        mainImage.src = clickedSrc;
        this.src = temp;
        this.setAttribute("data-image", temp);
      }
    });
  });
});

