let gridElement;

document.addEventListener('DOMContentLoaded', () => {
    // grid pour les collections
    gridElement = document.querySelector('.collections-grid');

    if (gridElement) {

        fetch('https://www.myminifactory.com/api/v2/users/belksasar3dprint/collections?per_page=4&key=fa3ff7c8-d2ab-4e5e-9b53-6886290da4ef')
                
        .then(response => {
            // console.log('Réponse brute :', response);
            if (!response.ok) {
                throw new Error('Erreur réseau : ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // console.log('Réponse texte :', text);
            if (data) {
                showCollection(data.items);
            }
        })
        .catch(error => {
            // console.error('Erreur :', error);
            // showPopup('Une erreur est survenue.', 'error');
        });
    }
});

function showCollection(collections) {
    //console.log(gridElement);
    collections.forEach(collection => {
        //console.log(collection.items);
        let cardElement = document.createElement("div");
        cardElement.classList.add("collection-card");
        cardElement.innerHTML += '<img src="' + collection.obj_img[0] + '">';
        cardElement.innerHTML += '<h3>' + collection.name + '</h3>';
        cardElement.innerHTML += '<p>Créateur : ' + collection.owner.name + '</p>';
        gridElement.appendChild(cardElement);
    });

};