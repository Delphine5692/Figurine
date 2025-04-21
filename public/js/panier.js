// Récupère le panier depuis le localStorage, ou initialise un tableau vide s'il n'existe pas.
let panier = JSON.parse(localStorage.getItem('panier')) || [];

/**
 * Affiche un message popup à l'utilisateur.
 * @param {string} message Le message à afficher.
 * @param {string} type Le type de message ('info', 'error', 'success'), utilisé pour le style.
 */
function showPopup(message, type = 'info') {
    // Vérifie si un conteneur pour les messages de type flash existe déjà dans le DOM.
    let container = document.getElementById('flash-container');
    if (!container) {
        // Si non, crée le conteneur et l'ajoute au body.
        container = document.createElement('div');
        container.id = 'flash-container';
        document.body.appendChild(container);
    }

    // Crée un élément div pour le message flash.
    const flash = document.createElement('div');
    flash.classList.add('flash-message', type); // Ajoute une classe pour le style selon le type.
    flash.textContent = message; // Définit le contenu textuel du message.

    // Ajoute le message flash dans le conteneur.
    container.appendChild(flash);

    // Après 5 secondes, amorce une transition en réduisant l'opacité.
    setTimeout(() => {
        flash.style.opacity = '0';
        // Après 500 ms (la durée de la transition), supprime le message flash.
        setTimeout(() => {
            flash.remove();
            // Si aucun autre message n'est présent, supprime également le conteneur.
            if (container.childNodes.length === 0) {
                container.remove();
            }
        }, 500);
    }, 5000);
}

/**
 * Met à jour la quantité d'un produit dans le panier et réaffiche le panier.
 * @param {string} id Identifiant du produit.
 * @param {number} nouvelleQuantite Nouvelle quantité voulue.
 */
function modifierQuantite(id, nouvelleQuantite) {
    const produit = panier.find(item => item.id === id);
    if (produit) {
        produit.quantite = nouvelleQuantite; // Met à jour la quantité du produit.
        localStorage.setItem('panier', JSON.stringify(panier)); // Sauvegarde le panier modifié dans le localStorage.
        showCart(); // Rafraîchit l'affichage du panier.
    }
}

/**
 * Affiche l'ensemble des produits du panier dans le DOM.
 */
function showCart() {
    // Récupère les éléments du DOM où afficher le panier et le prix total.
    const panierContainer = document.getElementById('panier-container');
    const totalPrixElement = document.getElementById('total-prix');

    // Vérifie que ces éléments existent.
    if (!panierContainer || !totalPrixElement) {
        console.warn('Les éléments #panier-container ou #total-prix sont introuvables dans le DOM.');
        return;
    }

    // Réinitialise le contenu du conteneur du panier.
    panierContainer.innerHTML = '';

    // Si le panier est vide, affiche un message et vide le total.
    if (panier.length === 0) {
        panierContainer.innerHTML = '<p>Votre panier est vide.</p>';
        totalPrixElement.textContent = '';
        return;
    }

    let totalPrix = 0;
    // Parcourt chaque produit du panier pour afficher ses informations.
    panier.forEach(item => {
        totalPrix += item.prix * item.quantite; // Calcule le prix total.
        panierContainer.innerHTML += `
            <div class="panier-item">
                <h3>${item.nom}</h3>
                <label for="quantite-${item.id}">Quantité :</label>
                <input type="number" id="quantite-${item.id}" class="quantite-input" data-id="${item.id}" value="${item.quantite}" min="1">
                <p>Prix unitaire : ${item.prix} €</p>
                <p><strong>Total : ${(item.prix * item.quantite).toFixed(2)} €</strong></p>
                <button class="remove-from-cart" data-id="${item.id}">Supprimer</button>
            </div>
        `;
    });
    // Affiche le total du panier.
    totalPrixElement.textContent = `Total : ${totalPrix.toFixed(2)} €`;

    // Ajoute des écouteurs pour détecter les modifications de quantité.
    document.querySelectorAll('.quantite-input').forEach(input => {
        input.addEventListener('change', (event) => {
            const id = event.target.getAttribute('data-id');
            const nouvelleQuantite = parseInt(event.target.value, 10);
            modifierQuantite(id, nouvelleQuantite);
        });
    });

    // Ajoute des écouteurs sur les boutons de suppression pour retirer un produit.
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            supprimerProduit(id);
        });
    });
}

/**
 * Supprime un produit du panier et met à jour l'affichage.
 * @param {string} id Identifiant du produit à supprimer.
 */
function supprimerProduit(id) {
    const index = panier.findIndex(item => item.id === id);
    if (index !== -1) {
        panier.splice(index, 1); // Retire le produit du panier.
        localStorage.setItem('panier', JSON.stringify(panier)); // Met à jour le localStorage.
        showCart(); // Rafraîchit l'affichage.
    }
}

// Lorsque le DOM est entièrement chargé, on ajoute les écouteurs d'événements aux boutons.
document.addEventListener('DOMContentLoaded', () => {
    // Bouton pour vider complètement le panier.
    const viderPanierButton = document.getElementById('vider-panier');
    if (viderPanierButton) {
        viderPanierButton.addEventListener('click', () => {
            panier = [];
            localStorage.removeItem('panier');
            showCart(); // Met à jour l'affichage du panier.
        });
    }

    // Bouton pour valider le panier et envoyer la commande au serveur.
    const validerPanierButton = document.getElementById('valider-panier');
    if (validerPanierButton) {
        validerPanierButton.addEventListener('click', () => {
            // Vérifie que le panier n'est pas vide.
            if (panier.length === 0) {
                showPopup('Votre panier est vide.', 'error');
                return;
            }

            console.log(panier); // Affiche le contenu du panier dans la console pour le debug.

            // Envoie les données du panier au serveur via une requête POST asynchrone.
            fetch('index.php?url=valider-panier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                // Convertit le panier en chaîne JSON.
                body: JSON.stringify(panier),
            })
                .then(response => {
                    console.log('Réponse brute :', response); // Affiche la réponse brute pour le debug.
                    // Vérifie si la réponse du serveur est correcte.
                    if (!response.ok) {
                        throw new Error('Erreur réseau : ' + response.status);
                    }
                    // Transforme la réponse en texte.
                    return response.text();
                })
                .then(text => {
                    console.log('Réponse texte :', text); // Affiche la réponse texte pour le debug.
                    // Convertit le texte en objet JavaScript.
                    const data = JSON.parse(text);
                    if (data.success) {
                        // Si la commande est validée, affiche un popup de succès.
                        showPopup('Commande validée avec succès !', 'success');
                        // Réinitialise le panier.
                        panier = [];
                        localStorage.removeItem('panier');
                        showCart(); // Met à jour l'affichage pour refléter le panier vide.
                    } else {
                        // Affiche un message d'erreur en cas de problème avec la commande.
                        showPopup(data.message || 'Une erreur est survenue lors de la validation de la commande.', 'error');
                    }
                })
                .catch(error => {
                    // En cas d'erreur réseau ou de traitement, affiche un message d'erreur.
                    console.error('Erreur :', error);
                    showPopup('Une erreur est survenue.', 'error');
                });
        });
    }
});