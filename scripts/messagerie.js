// document.querySelector('form textarea').addEventListener('input', function () {
//     this.style.height = '2.5rem';
//     this.style.height = (this.scrollHeight+0.45) + 'px';
// });
const search = document.querySelector('form.recherche input');
const error = document.querySelector('.erreur');

autosize(document.querySelectorAll('form textarea'));
searchZone();

const messageBox = document.querySelector('.messages');
messageBox.scrollTop = messageBox.scrollHeight;

document.querySelector('form.msg textarea').addEventListener('keydown', function (event) {
    if (event.key === 'Enter' && !event.shiftKey) {
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

search.addEventListener('input', searchZone);
document.querySelector('form.recherche').addEventListener('submit', function (event) {
    event.preventDefault();

    error.innerHTML = "Veuillez sélectionner un utilisateur dans la liste.";
    error.style.display = 'block';

    // searchZone();
});

function searchZone() {
    fetch('../scripts/apiUsers.php?recherche=' + search.value).then(function (response) {
        response.json().then(function (users) {
            const liste = document.querySelector('datalist.usr');
            liste.innerHTML = '';
            if (users.length == 0) {
                error.innerHTML = "Aucun utilisateur trouvé.";
                error.style.display = 'block';
                error.style.bottom = 'calc(100vh - ' + search.getBoundingClientRect().top + 'px)';
            }
            users.forEach(function (user) {
                liste.innerHTML += "<option value=\"" + user.identite + "\">"
                if (search.value == user.identite) {
                    window.location.replace("./messagerie?to=" + user.id);
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