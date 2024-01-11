//Séléction le champs de selection du type de compte dans le formulaire
document.querySelector('form select').addEventListener('change', function () {
    //S'il ne s'agit pas d'un compte étudiant, désactive le champs de saisie du numéro étudiant
    if (this.value != '1') {
        document.querySelector('#numEtudiant').disabled=true;
    } else {
        document.querySelector('#numEtudiant').disabled=false;
    }
});

//Séléction du champs de recherche
const search = document.querySelector('form.recherche input');
//Séléction du champs d'erreur
const error = document.querySelector('form.recherche .erreur');

//Positionne le message d'erreur
error.style.position = 'absolute';
error.style.top = '-50px';
//Ajoute un évènement sur la zone de recherche
search.addEventListener('input', searchZone);
//Si le formulaire est envoyé
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
            //Vide la liste des utilisateurs
            const liste = document.querySelector('datalist.usr');
            liste.innerHTML = '';
            //Si aucun utilisateur n'est trouvé, affiche un message d'erreur
            if (users.length == 0) {
                error.innerHTML = "Aucun utilisateur trouvé.";
                error.style.display = 'block';
            }
            //Sinon, affiche les utilisateurs dans la liste
            users.forEach(function (user) {
                liste.innerHTML += "<option value=\"" + user.identite + "\">"
                //Si la recherche correspond exactement à un utilisateur, redirige vers la page de gestion de l'utilisateur en question
                if (search.value == user.identite) {
                    window.location.replace("./gestionUsers?user=" + user.id);
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

//Ajoute un évènement sur le bouton de suppression de l'utilisateur
document.querySelector('a.delete').addEventListener('click', function (event) {
    event.preventDefault();
    //Demande une confirmation avant de supprimer l'utilisateur
    if (confirm("Voulez-vous vraiment supprimer cet utilisateur ? Cette action est irréversible.")) {
        //Redirige vers la page de suppression de l'utilisateur
        window.location.replace(this.href);
    }
});