// Charger le panier depuis localStorage ou initialiser un panier vide
let panier = JSON.parse(localStorage.getItem('panier')) || [];

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

// Fonction pour modifier la quantité d'un produit dans le panier
function modifierQuantite(id, nouvelleQuantite) {
    const produit = panier.find(item => item.id === id);
    if (produit) {
        produit.quantite = nouvelleQuantite; // Mettre à jour la quantité
        localStorage.setItem('panier', JSON.stringify(panier)); // Mettre à jour le localStorage
        showCart(); // Réafficher le panier pour mettre à jour l'affichage
    }
}

// Fonction pour afficher le contenu du panier
function showCart() {
    const panierContainer = document.getElementById('panier-container');
    const totalPrixElement = document.getElementById('total-prix');

    // Vérifier si les éléments existent
    if (!panierContainer || !totalPrixElement) {
        console.warn('Les éléments #panier-container ou #total-prix sont introuvables dans le DOM.');
        return;
    }

    // Réinitialiser le conteneur
    panierContainer.innerHTML = '';

    if (panier.length === 0) {
        panierContainer.innerHTML = '<p>Votre panier est vide.</p>';
        totalPrixElement.textContent = '';
        return;
    }

    let totalPrix = 0;
    panier.forEach(item => {
        totalPrix += item.prix * item.quantite;
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
    totalPrixElement.textContent = `Total : ${totalPrix.toFixed(2)} €`;

    // Événements pour modification de quantité
    document.querySelectorAll('.quantite-input').forEach(input => {
        input.addEventListener('change', (event) => {
            const id = event.target.getAttribute('data-id');
            const nouvelleQuantite = parseInt(event.target.value, 10);
            modifierQuantite(id, nouvelleQuantite);
        });
    });

    // Événements pour suppression d'un produit
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            supprimerProduit(id);
        });
    });
}

// Fonction pour supprimer un produit du panier
function supprimerProduit(id) {
    const index = panier.findIndex(item => item.id === id);
    if (index !== -1) {
        panier.splice(index, 1);
        localStorage.setItem('panier', JSON.stringify(panier));
        showCart();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Bouton pour vider le panier
    const viderPanierButton = document.getElementById('vider-panier');
    if (viderPanierButton) {
        viderPanierButton.addEventListener('click', () => {
            panier = [];
            localStorage.removeItem('panier');
            showCart();
        });
    }

    // Bouton pour valider le panier
    const validerPanierButton = document.getElementById('valider-panier');
    if (validerPanierButton) {
        validerPanierButton.addEventListener('click', () => {
            if (panier.length === 0) {
                showPopup('Votre panier est vide.', 'error');
                return;
            }

            console.log(panier); // Debug: afficher le panier

            // Envoyer les données du panier au serveur
            fetch('index.php?url=valider-panier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(panier),
            })
                .then(response => {
                    console.log('Réponse brute :', response);
                    if (!response.ok) {
                        throw new Error('Erreur réseau : ' + response.status);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Réponse texte :', text);
                    const data = JSON.parse(text);
                    if (data.success) {
                        showPopup('Commande validée avec succès !', 'success');
                        panier = [];
                        localStorage.removeItem('panier');
                        showCart();
                    } else {
                        showPopup(data.message || 'Une erreur est survenue lors de la validation de la commande.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    showPopup('Une erreur est survenue.', 'error');
                });
        });
    }
});