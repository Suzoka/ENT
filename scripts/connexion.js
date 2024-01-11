//Récupérer l'URL de la page
const url = new URL(document.location.href);

//Ajout des évènements sur les boutons pour afficher les pages suivantes
document.querySelectorAll('.displayPopup').forEach((button, i) => {
    button.addEventListener('click', () => {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup'+i).style.display = "block";
    });
});

//Ajout des évènements sur les boutons pour revenir à la page précédente
document.querySelectorAll('.backButton').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.formulaire').forEach(popup => {
            popup.style.display = "none";
        });
        document.querySelector('.flexbox').style.display = "flex";
    });
});

//Si l'URL contient un paramètre "error", afficher la page de connexion corespondante
if (url.searchParams.get('error')) {
    //Si c'est une connection étudiant
    if (url.searchParams.get('error').split('!')[0] === 'usr') {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup0').style.display = "block";
    }
    //Si c'est une connection professeur
    if (url.searchParams.get('error').split('!')[0] === 'prof') {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup1').style.display = "block";
    }
    //Si c'est une connection administrateur
    if (url.searchParams.get('error').split('!')[0] === 'admin') {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup2').style.display = "block";
    }
}