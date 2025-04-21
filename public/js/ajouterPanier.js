// Récupère le panier enregistré dans le localStorage.
// Si aucun panier n'existe, on initialise un tableau vide.
let panier = JSON.parse(localStorage.getItem('panier')) || [];

/**
 * Affiche un message flash personnalisé à l'utilisateur.
 *
 * @param {string} message - Le message à afficher.
 * @param {string} [type='info'] - Le type de message ('success', 'error' ou 'info').
 */
function showPopup(message, type = 'info') {
    // Récupère l'élément conteneur des messages flash s'il existe déjà.
    let container = document.getElementById('flash-container');
    if (!container) {
        // Sinon, crée le conteneur et l'ajoute au body.
        container = document.createElement('div');
        container.id = 'flash-container';
        document.body.appendChild(container);
    }

    // Crée un nouvel élément pour le message flash.
    const flash = document.createElement('div');
    // Ajoute des classes CSS pour le style : une classe générique et une spécifique au type.
    flash.classList.add('flash-message', type);
    flash.textContent = message; // Définit le contenu textuel du message.

    // Ajoute le message flash dans le conteneur.
    container.appendChild(flash);

    // Après 5 secondes, démarre la disparition du message.
    setTimeout(() => {
        flash.style.opacity = '0'; // Fait disparaître le message en modifiant l'opacité.
        // Après 500 ms (pour laisser le temps à la transition), retire le message du DOM.
        setTimeout(() => {
            flash.remove();
            // Si le conteneur est vide après la suppression du message, on le retire aussi.
            if (container.childNodes.length === 0) {
                container.remove();
            }
        }, 500);
    }, 5000);
}


/**
 * Ajoute un produit au panier.
 *
 * @param {string} id - L'identifiant du produit.
 * @param {string} nom - Le nom du produit.
 * @param {number} prix - Le prix unitaire du produit.
 */
function ajouterAuPanier(id, nom, prix) {
    // Vérifie si le produit existe déjà dans le panier.
    const produitExistant = panier.find(item => item.id === id);

    if (produitExistant) {
        // Si le produit est déjà présent, on augmente sa quantité.
        produitExistant.quantite += 1;
    } else {
        // Sinon, ajoute le produit avec une quantité initiale de 1.
        panier.push({ id, nom, prix, quantite: 1 });
    }

    // Enregistre la mise à jour du panier dans le localStorage.
    localStorage.setItem('panier', JSON.stringify(panier));

    // Affiche une confirmation à l'utilisateur.
    // Si la fonction showPopup existe (elle devrait toujours exister ici), on l'utilise.
    if (typeof showPopup === 'function') {
        showPopup(`${nom} a été ajouté au panier.`, 'success');
    } else {
        console.log(`${nom} a été ajouté au panier.`);
    }
}

// Ajoute un écouteur d'événements "click" à tous les boutons ayant la classe "add-to-cart".
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        // Récupère l'identifiant, le nom et le prix du produit à partir des attributs data-* du bouton.
        const id = button.getAttribute('data-id');
        const nom = button.getAttribute('data-nom');
        const prix = parseFloat(button.getAttribute('data-prix'));

        // Appelle la fonction pour ajouter le produit au panier.
        ajouterAuPanier(id, nom, prix);
    });
});