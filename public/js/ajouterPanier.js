// Charger le panier depuis localStorage ou initialiser un panier vide
let panier = JSON.parse(localStorage.getItem('panier')) || [];

/**
 * Fonction pour afficher un message flash personnalisé.
 * @param {string} message Le message à afficher.
 * @param {string} type Le type de message : 'success', 'error' ou 'info' (par défaut 'info').
 */
function showPopup(message, type = 'info') {
    // Vérifier si le conteneur flash existe déjà
    let container = document.getElementById('flash-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'flash-container';

        document.body.appendChild(container);
    }

    // Créer l'élément message flash
    const flash = document.createElement('div');
    flash.classList.add('flash-message', type);
    flash.textContent = message;

    // Ajouter le message dans le conteneur
    container.appendChild(flash);

    // Faire disparaître le message après 5 secondes
    setTimeout(() => {
        flash.style.opacity = '0';
        setTimeout(() => {
            flash.remove();
            // Supprimer le conteneur s'il est vide
            if (container.childNodes.length === 0) {
                container.remove();
            }
        }, 500);
    }, 5000);
}


// Fonction pour ajouter un produit au panier
function ajouterAuPanier(id, nom, prix) {
    // Vérifier si le produit existe déjà dans le panier
    const produitExistant = panier.find(item => item.id === id);

    if (produitExistant) {
        // Incrémenter la quantité si le produit existe déjà
        produitExistant.quantite += 1;
    } else {
        // Ajouter un nouveau produit au panier
        panier.push({ id, nom, prix, quantite: 1 });
    }

    // Sauvegarder le panier dans localStorage
    localStorage.setItem('panier', JSON.stringify(panier));

    // // Afficher un message de confirmation
    // alert(`${nom} a été ajouté au panier.`);
    // Afficher un message de confirmation via flash message
    if (typeof showPopup === 'function') {
        showPopup(`${nom} a été ajouté au panier.`, 'success');
    } else {
        console.log(`${nom} a été ajouté au panier.`);
    }
}

// Ajouter un événement "click" à tous les boutons "Ajouter au panier"
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const nom = button.getAttribute('data-nom');
        const prix = parseFloat(button.getAttribute('data-prix'));

        ajouterAuPanier(id, nom, prix);
    });
});