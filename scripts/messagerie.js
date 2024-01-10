// ! Version codée en vanilla JS, sans utiliser de library
//document.querySelector('form textarea').addEventListener('input', function () {
//     this.style.height = '2.5rem';
//     this.style.height = (this.scrollHeight+0.45) + 'px';
// });

//Séléction le champs de recherche
const search = document.querySelector('form.recherche input');

//Séléction le champs d'erreur  
const error = document.querySelector('.erreur');


//Librairie pour redimensionner automatiquement le textarea en fonction de son contenu
autosize(document.querySelectorAll('form textarea'));

//Exécute une première fois la fonction searchZone
searchZone();


//Descend automatiquement la barre de scroll vers le bas pour avoir les derniers messages affichés à l'ouverture de la page
const messageBox = document.querySelector('.messages');
messageBox.scrollTop = messageBox.scrollHeight;

//Ajoute un évènement sur le clavier, lorsque nous somme dans le champs de texte
document.querySelector('form.msg textarea').addEventListener('keydown', function (event) {
    //Si on appuie sur la touche "Entrée" sans appuyer sur "Shift"
    if (event.key === 'Enter' && !event.shiftKey) {

        //On envoie le formulaire si il est valide, sinon on affiche un message d'erreur
        event.preventDefault();
        const form = document.querySelector('form');
        if (form.checkValidity()) {
            form.submit();
        }
        else {
            form.reportValidity();
        }
    }
});

//Ajoute un évènement sur la zone de recherche
search.addEventListener('input', searchZone);

//Ajoute un évènement sur l'envoie du formulaire de recherche
document.querySelector('form.recherche').addEventListener('submit', function (event) {
    event.preventDefault();

    //Affiche un message d'erreur pour prévenir qu'il faut cliquer sur un utilisateur dans la liste
    error.innerHTML = "Veuillez sélectionner un utilisateur dans la liste.";
    error.style.display = 'block';
});

function searchZone() {
    //Envoie une requête à l'API pour récupérer les utilisateurs correspondants à la recherche
    fetch('../scripts/apiUsers.php?recherche=' + search.value).then(function (response) {
        response.json().then(function (users) {
            const liste = document.querySelector('datalist.usr');
            liste.innerHTML = '';
            //Si aucun utilisateur n'est trouvé, affiche un message d'erreur
            if (users.length == 0) {
                error.innerHTML = "Aucun utilisateur trouvé.";
                error.style.display = 'block';
                error.style.bottom = 'calc(100vh - ' + search.getBoundingClientRect().top + 'px)';
            }
            //Sinon, affiche les utilisateurs dans la liste
            users.forEach(function (user) {
                liste.innerHTML += "<option value=\"" + user.identite + "\">"
                //Si la recherche correspond exactement à un utilisateur, redirige vers la page de messagerie, dans la conversation avec l'utilisateur en question
                if (search.value == user.identite) {
                    window.location.replace("./messagerie?to=" + user.id);
                }
            });
        });
    });
};

//Ajoute un évènement de clic sur la loupe, pour focus le champs de recherche en cas de clic
document.querySelector('form.recherche').addEventListener('click', function () {
    document.querySelector('form.recherche input').focus();
});

//Affiche le message d'erreur pendant 5 secondes
setInterval(function () {
    if (error.style.display == 'block') {
        setTimeout(function () {
            error.style.display = 'none';
        }, 5000);
    }
}, 6000);