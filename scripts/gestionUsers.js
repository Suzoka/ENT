document.querySelector('form select').addEventListener('change', function () {
    if (this.value != '1') {
        document.querySelector('#numEtudiant').disabled=true;
    } else {
        document.querySelector('#numEtudiant').disabled=false;
    }
});

const search = document.querySelector('form.recherche input');
const error = document.querySelector('form.recherche .erreur');

error.style.position = 'absolute';
error.style.top = '-50px';
search.addEventListener('input', searchZone);
document.querySelector('form.recherche').addEventListener('submit', function (event) {
    event.preventDefault();

    error.innerHTML = "Veuillez sélectionner un utilisateur dans la liste.";
    error.style.display = 'block';
});

function searchZone() {
    fetch('../scripts/apiUsers.php?recherche=' + search.value).then(function (response) {
        response.json().then(function (users) {
            const liste = document.querySelector('datalist.usr');
            liste.innerHTML = '';
            if (users.length == 0) {
                error.innerHTML = "Aucun utilisateur trouvé.";
                error.style.display = 'block';
            }
            users.forEach(function (user) {
                liste.innerHTML += "<option value=\"" + user.identite + "\">"
                if (search.value == user.identite) {
                    window.location.replace("./gestionUsers?user=" + user.id);
                }
            });
        });
    });
};

document.querySelector('form.recherche').addEventListener('click', function () {
    document.querySelector('form.recherche input').focus();
});


setInterval(function () {
    if (error.style.display == 'block') {
        setTimeout(function () {
            error.style.display = 'none';
        }, 5000);
    }
}, 6000);

document.querySelector('a.delete').addEventListener('click', function (event) {
    event.preventDefault();
    if (confirm("Voulez-vous vraiment supprimer cet utilisateur ? Cette action est irréversible.")) {
        window.location.replace(this.href);
    }
});