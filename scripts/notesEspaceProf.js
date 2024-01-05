const id_prof = document.querySelector('.panel').getAttribute('id').replace("prof", "")
const titre = document.querySelector('.panel h1');
const dynamic = document.querySelector('.dynamic');
const backButton = document.querySelector('button.back');
const filAriane = document.querySelector('.filAriane');
const popup = document.querySelector('.popup');
let page;
let ariane = 0;
let modifs = [];

displayPage1();

backButton.addEventListener('click', back);

function displayPage1() {
    backButton.style.display = 'none';
    page = 1;
    titre.innerHTML = "Sélection de la classe";
    fetch('../scripts/apiClassesOfTeacher.php?login=' + id_prof).then(function (response) {
        response.json().then(function (classes) {
            dynamic.innerHTML = '';
            classes.forEach(function (classe) {
                dynamic.innerHTML += "<button id=\"" + classe.id_classe + "\">" + classe.nom_classe + "</button>";
            });
            setTimeout(function () {
                technicalPage1();
            }, 100);
        });
    });
}

function technicalPage1() {
    document.querySelectorAll('.dynamic button').forEach(function (button) {
        button.addEventListener('click', function () {
            displayPage2(button.getAttribute('id'));
        });
    });
}

function displayPage2(id_classe) {
    fetch('../scripts/apiModOfClasseForTeacher.php?login=' + id_prof + "&classe=" + id_classe).then(function (response) {
        response.json().then(function (modules) {
            titre.innerHTML = "Sélection du module";
            dynamic.innerHTML = '';
            modules.forEach(function (module) {
                dynamic.innerHTML += "<button id=\"" + module.id_module + "\">" + module.nom_module + "</button>";
            });
            setTimeout(function () {
                technicalPage2(id_classe);
            }, 100);
        });
    });
}

function technicalPage2(id_classe) {
    backButton.style.display = 'block';
    filAriane.innerHTML = "<button>Classe</button> \>";
    filArianeTechnical();
    page = 2;
    document.querySelectorAll('.dynamic button').forEach(function (button) {
        button.addEventListener('click', function () {
            backButton.setAttribute('id', "from" + id_classe);
            filAriane.innerHTML += "<button id=\"goto" + id_classe + "\">Module</button> \>";
            filArianeTechnical();
            displayPage3(button.getAttribute('id'));
        });
    });
}

function displayPage3(id_module) {
    fetch('../scripts/apiDevoirOfModForTeacher.php?login=' + id_prof + "&module=" + id_module).then(function (response) {
        response.json().then(function (devoirs) {
            titre.innerHTML = "Sélection du devoir";
            dynamic.innerHTML = '';
            devoirs.forEach(function (devoir) {
                dynamic.innerHTML += "<button id=\"" + devoir.id_devoir + "\">" + devoir.nom_devoir + "</button>";
            });
            setTimeout(function () {
                technicalPage3(id_module);
            }, 100);
        });
    });
}

function technicalPage3(id_module) {
    page = 3;
    document.querySelectorAll('.dynamic button').forEach(function (button) {
        button.addEventListener('click', function () {
            backButton.setAttribute('id', backButton.getAttribute('id') + id_module);
            filAriane.innerHTML += "<button id=\"goto" + id_module + "\">Devoir</button> \>";
            filArianeTechnical();
            displayPage4(button.getAttribute('id'));
        });
    });
}

function displayPage4(id_devoir) {
    fetch('../scripts/apiNotesOfWork.php?classe=' + backButton.getAttribute('id')[4] + "&devoir=" + id_devoir).then(function (response) {
        response.json().then(function (notes) {
            page = 4;
            dynamic.innerHTML = "";
            dynamic.innerHTML += "<div class='notes'><table id='" + id_devoir + "'><thead><tr><th>N°étudiant</th><th>Nom</th><th>Prénom</th><th>Note</th></tr></thead><tbody><tbody></table></div>";
            notes.forEach(function (note) {
                document.querySelector("table tbody").innerHTML += "<tr><td>" + note.numEtud + "</td><td>" + note.nom + "</td><td>" + note.prenom + "</td><td><input type='text' value='" + (note.valeur != null ? note.valeur : "") + "' id='usr" + note.id + "'></td></tr>";
            });
            dynamic.innerHTML += "<button id='save'>Enregistrer</button>";
            setTimeout(function () {
                technicalPage4(notes);
            }, 100);
        });
    });
}

function technicalPage4(notes) {
    document.querySelector('#save').addEventListener('click', function () {
        getModifs(notes);
        if (modifs.length > 0) {
            affichePopup();
        }
    });
};

function getModifs(notes) {
    modifs = [];
    document.querySelectorAll('table input').forEach(function (input, i) {
        let test = notes[i].valeur == null ? "" : notes[i].valeur;
        if (input.value.replaceAll(",", ".") != test) {
            modifs.push({ id: notes[i].id, nom: notes[i].nom, prenom: notes[i].prenom, old_valeur: notes[i].valeur, new_valeur: input.value.replaceAll(",", ".") })
        }
    });
};

function affichePopup() {
    if (modifs.length == 0) {
        popup.style.display = 'none';
        return;
    }
    popup.style.display = 'flex';
    modifs.forEach(function (modif) {
        document.querySelector('.popup .modifs').innerHTML += "<div class='modification'><p>" + modif.nom + " " + modif.prenom + " : " + modif.old_valeur + " -> " + modif.new_valeur + "</p><button class='cancel' id='cancel" + modif.id + "'><p class='sr-only'>Annuler cette modification</p></button></div>";
    });
    document.querySelectorAll('.popup .cancel').forEach(function (button, i) {
        button.addEventListener('click', function () {
            document.querySelector('table input#usr'+button.getAttribute('id').replace('cancel', '')).value = modifs[i].old_valeur;
            modifs.splice(i, 1);
            document.querySelector('.popup .modifs').innerHTML = '';
            affichePopup();
        });
    });
}

document.querySelector('.popup .cancelAll').addEventListener('click', function () {
    popup.style.display = 'none';
    document.querySelector('.popup .modifs').innerHTML = '';
    modifs = [];
    displayPage4(document.querySelector('table').getAttribute('id'));
});

document.querySelector('.popup .confirm').addEventListener('click', function () {
    popup.style.display = 'none';
    document.querySelector('.popup .modifs').innerHTML = '';
    fetch('../scripts/apiUpdateNotes.php?devoir='+document.querySelector('table').getAttribute('id'), {
        method: 'POST',
        body: JSON.stringify(modifs)
    }).then(function (response) {
        response.json().then(function (result) {
            if (result == 'ok') {
                displayPage4(document.querySelector('table').getAttribute('id'));
            }
            else {
                // alert("Une erreur est survenue lors de l'enregistrement des modifications.");
                console.log(result);
            }
        });
    });
});

function back() {
    switch (page) {
        case 1:
        default:
            break;
        case 2:
            displayPage1();
            filAriane.removeChild(filAriane.lastChild);
            break;
        case 3:
            displayPage2(backButton.getAttribute('id')[backButton.getAttribute('id').length - 1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            break;
        case 4:
            displayPage3(backButton.getAttribute('id')[backButton.getAttribute('id').length - 1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            filAriane.removeChild(filAriane.lastChild);
            filAriane.removeChild(filAriane.lastChild);
            break;
    }
}

function filArianeTechnical() {
    filAriane.querySelectorAll('button').forEach(function (button) {
        switch (button.innerHTML) {
            case "Classe":
                button.addEventListener('click', function () {
                    displayPage1();
                    filAriane.innerHTML = "";
                    ariane = -1
                });
                break;
            case "Module":
                button.addEventListener('click', function () {
                    displayPage2(button.getAttribute('id').replace("goto", ""));
                    ariane = 0
                });
                break;
            case "Devoir":
                button.addEventListener('click', function () {
                    displayPage3(button.getAttribute('id').replace("goto", ""));
                    filAriane.removeChild(filAriane.lastChild);
                    filAriane.removeChild(filAriane.lastChild);
                    ariane = 1
                });
                break;
        }
        ariane++;
    });
}