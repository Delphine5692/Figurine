// Gestion du menu burger pour afficher/cacher la navigation
document.addEventListener('DOMContentLoaded', function () {
  // Récupère l'élément du menu burger
  const burger = document.querySelector('.burger-menu');
  // Récupère le conteneur de la navigation
  const navContainer = document.querySelector('.nav-container');

  // Quand l'utilisateur clique sur le burger, on bascule la classe "open"
  burger.addEventListener('click', function () {
    navContainer.classList.toggle('open');
  });
});

// Modification de l'apparence de la barre de navigation lors du scroll
document.addEventListener('DOMContentLoaded', function () {
  // Lors du scroll de la fenêtre
  window.addEventListener('scroll', function () {
    // Récupère le conteneur de la navigation
    const navContainer = document.querySelector('.nav-container');
    // Si l'utilisateur a scrollé plus de 50 pixels, on ajoute la classe "scrolled"
    // Sinon, on la retire
    if (window.pageYOffset > 50) {
      navContainer.classList.add('scrolled');
    } else {
      navContainer.classList.remove('scrolled');
    }
  });
});

// Remplace l'image principale du produit de manière dynamique via les miniatures
document.addEventListener("DOMContentLoaded", function () {
  // Sélectionne toutes les images miniatures
  var thumbnails = document.querySelectorAll(".thumbnail");
  // Récupère l'image principale par son ID
  var mainImage = document.getElementById("mainImage");

  // Pour chaque miniature, on ajoute un écouteur de clic
  thumbnails.forEach(function (thumb) {
    thumb.addEventListener("click", function () {
      // Récupère l'attribut "data-image" de la miniature cliquée
      var clickedSrc = this.getAttribute("data-image");
      if (clickedSrc) {
        // Permute l'image principale avec la miniature cliquée
        var temp = mainImage.src;
        mainImage.src = clickedSrc;
        // Met à jour l'attribut data-image de la miniature avec l'ancienne source de l'image principale
        this.src = temp;
        this.setAttribute("data-image", temp);
      }
    });
  });
});