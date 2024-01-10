//Déclare le popup
const popup = document.querySelector('.newProjetForm');

//Déclare les boutons d'ouverture et de fermeture du popup
const buttonOpenPopup = document.querySelector('button.newProjet');
const buttonClosePopup = document.querySelector('button.cancel');


//Ajoute un évènement au clic sur le bouton d'ouverture du popup
buttonOpenPopup.addEventListener('click', function () {
    //Affiche le popup
    popup.style.display = 'flex';
});


//Ajoute un évènement au clic sur le bouton de fermeture du popup
buttonClosePopup.addEventListener('click', function () {
    //Cache le popup
    popup.style.display = 'none';
});


//Ajoute un évènement au clic sur le fond du popup
popup.addEventListener('click', function (event) {
    if (event.target == popup) {
        //Cache le popup
        popup.style.display = 'none';
    }
});