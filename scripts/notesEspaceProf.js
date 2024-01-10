const id_prof = document.querySelector('.panel').getAttribute('id').replace("prof", "")
const titre = document.querySelector('.panel h1');
const dynamic = document.querySelector('.dynamic');
const backButton = document.querySelector('button.back');
const filAriane = document.querySelector('.filAriane');
const popup = document.querySelector('.popup');
let page;
let ariane = 0;
let modifs = [];
let erreur = false;
let coef = 0;

displayPage1();
technicalNewDevoirForm();

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
    let nouveauDevoir = document.createElement('button');
    nouveauDevoir.innerHTML = "Nouveau devoir";
    nouveauDevoir.classList.add('newDevoir');
    document.querySelector('.title').appendChild(nouveauDevoir);
    document.querySelector('.title .newDevoir').addEventListener('click', function () {
        document.querySelector('.newDevoirForm').style.display = 'flex';
    });
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
    titre.classList.add('mod' + id_module);
    page = 3;
    document.querySelectorAll('.dynamic button').forEach(function (button) {
        button.addEventListener('click', function () {
            backButton.setAttribute('id', backButton.getAttribute('id') + id_module);
            filAriane.innerHTML += "<button id=\"goto" + id_module + "\">Devoir</button> \>";
            filArianeTechnical();
            displayPage4(button.getAttribute('id'), button.innerHTML);
        });
    });
}

async function displayPage4(id_devoir, nom_devoir) {
    coef = await getCoefDevoir(id_devoir);
    try {document.querySelector('.title .newDevoir').remove();} catch (e) {}
    titre.classList.remove(titre.classList[0]);
    fetch('../scripts/apiNotesOfWork.php?classe=' + backButton.getAttribute('id')[4] + "&devoir=" + id_devoir).then(function (response) {
        response.json().then(function (notes) {
            page = 4;
            titre.innerHTML = nom_devoir;
            console.log('coucou');
            let coefDevoir = document.createElement('p');
            coefDevoir.innerHTML = "Coefficient <input type='number' value='" + coef + "'>";
            document.querySelector('.title').appendChild(coefDevoir);
            dynamic.innerHTML = "";
            dynamic.innerHTML += "<div class='notes'><table id='" + id_devoir + "'><thead><tr><th scope=\"col\">N°étudiant</th><th scope=\"col\">Nom</th><th scope=\"col\">Prénom</th><th scope=\"col\">Note</th></tr></thead><tbody><tbody></table></div>";
            notes.forEach(function (note) {
                document.querySelector("table tbody").innerHTML += "<tr><td>" + note.numEtud + "</td><th scope=\"row\">" + note.nom + "</th><td>" + note.prenom + "</td><td><input type='number' value='" + (note.valeur != null ? note.valeur : "") + "' id='usr" + note.id + "'></td></tr>";
            });
            dynamic.innerHTML += "<p class='erreur'>Aucune valeur n'a été modifiée</p><button class='save'>Enregistrer</button>";
            setTimeout(function () {
                technicalPage4(notes);
            }, 100);
        });
    });
}

function technicalPage4(notes) {
    document.querySelector('.save').addEventListener('click', function () {
        getModifs(notes);
        if (modifs.length > 0) {
            affichePopup();
        }
        else {
            document.querySelector('.erreur').style.display = 'block';
            document.querySelector('.save').style.marginLeft = '0'
            if (!erreur) {
                setTimeout(function () {
                    document.querySelector('.erreur').style.display = 'none';
                    document.querySelector('.save').style.marginLeft = 'auto'
                    erreur = false;
                }, 3000);
                erreur = true;
            }
        }
    });
};

function getModifs(notes) {
    modifs = [];
    if (document.querySelector('.title input').value != coef) {
        modifs.push({ type: 1, devoir: document.querySelector('table').getAttribute('id'), old_coef: coef, new_coef: document.querySelector('.title input').value });
    }
    document.querySelectorAll('table input').forEach(function (input, i) {
        let test = notes[i].valeur == null ? "" : notes[i].valeur;
        if (input.value.replaceAll(",", ".") != test) {
            modifs.push({ type: 2, id: notes[i].id, nom: notes[i].nom, prenom: notes[i].prenom, old_valeur: notes[i].valeur, new_valeur: input.value.replaceAll(",", ".") != "" ? input.value.replaceAll(",", ".") : null })
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
        if (modif.type == 1) {
            document.querySelector('.popup .modifs').innerHTML += "<div class='modification'><p>Coefficient : " + modif.old_coef + " -> " + modif.new_coef + "</p><button class='cancelCoef'><p class='sr-only'>Annuler cette modification</p></button></div>";
        }
        if (modif.type == 2) {
            document.querySelector('.popup .modifs').innerHTML += "<div class='modification'><p>" + modif.nom + " " + modif.prenom + " : " + modif.old_valeur + " -> " + modif.new_valeur + "</p><button class='cancel' id='cancel" + modif.id + "'><p class='sr-only'>Annuler cette modification</p></button></div>";
        }
    });
    document.querySelectorAll('.cancelCoef').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelector('.title input').value = modifs[0].old_coef;
            modifs.splice(0, 1);
            document.querySelector('.popup .modifs').innerHTML = '';
            affichePopup();
        });
    });
    document.querySelectorAll('.popup .cancel').forEach(function (button, i) {
        button.addEventListener('click', function () {
            document.querySelector('table input#usr' + button.getAttribute('id').replace('cancel', '')).value = modifs[i].old_valeur;
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
    document.querySelector('.title>p').remove();
    displayPage4(document.querySelector('table').getAttribute('id'), titre.innerHTML);
});

document.querySelector('.popup .confirm').addEventListener('click', function () {
    popup.style.display = 'none';
    document.querySelector('.popup .modifs').innerHTML = '';
    fetch('../scripts/apiUpdateNotes.php?devoir=' + document.querySelector('table').getAttribute('id'), {
        method: 'POST',
        body: JSON.stringify(modifs)
    }).then(function (response) {
        response.json().then(function (result) {
            if (result == 'ok') {
                document.querySelector('.title>p').remove();
                displayPage4(document.querySelector('table').getAttribute('id'), titre.innerHTML);
            }
            else {
                alert("Une erreur est survenue lors de l'enregistrement des modifications.");
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
            filAriane.innerHTML = "";
            break;
        case 3:
            displayPage2(backButton.getAttribute('id')[backButton.getAttribute('id').length - 1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            document.querySelector('.title .newDevoir').remove();
            titre.classList.remove(titre.classList[0]);
            break;
        case 4:
            displayPage3(backButton.getAttribute('id')[backButton.getAttribute('id').length - 1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            document.querySelector('.title>p').remove();
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
                    try { document.querySelector('.title>p').remove(); } catch (e) { }
                    try { document.querySelector('.title .newDevoir').remove(); } catch (e) { }
                    titre.classList.remove(titre.classList[0]);
                    filAriane.innerHTML = "";
                    ariane = -1;
                });
                break;
            case "Module":
                button.addEventListener('click', function () {
                    displayPage2(button.getAttribute('id').replace("goto", ""));
                    try { document.querySelector('.title>p').remove(); } catch (e) { }
                    try { document.querySelector('.title .newDevoir').remove(); } catch (e) { }
                    titre.classList.remove(titre.classList[0]);
                    ariane = 0;
                });
                break;
            case "Devoir":
                button.addEventListener('click', function () {
                    displayPage3(button.getAttribute('id').replace("goto", ""));
                    filAriane.removeChild(filAriane.lastChild);
                    filAriane.removeChild(filAriane.lastChild);
                    try { document.querySelector('.title>p').remove(); } catch (e) { }
                    ariane = 1;
                });
                break;
        }
        ariane++;
    });
}

async function getCoefDevoir(id_devoir) {
    const response = await fetch('../scripts/apiCoefDevoir.php?devoir=' + id_devoir);
    const result = await response.json();
    return result;
}

function technicalNewDevoirForm() {
    document.querySelectorAll('.newDevoirForm input').forEach(function (input) {
        input.addEventListener('input', function () {
            this.style.border = "1px solid #ced4da";
            this.setCustomValidity("");
        });
    });
    document.querySelector('.newDevoirForm').addEventListener('click', function (e) {
        if (e.target.classList.contains('newDevoirForm')) {
            document.querySelector('.newDevoirForm').style.display = 'none';
        }
    });
    document.querySelectorAll('.newDevoirForm button').forEach(function (button) {
        button.addEventListener('click', function (e) {
            if (e.target.classList.contains('cancel')) {
                document.querySelector('.newDevoirForm').style.display = 'none';
            }
            else {
                if (document.querySelector('.newDevoirForm input#nom').value != "" && document.querySelector('.newDevoirForm input#coef').value != "") {
                    const newDevoir = {
                        nom: document.querySelector('.newDevoirForm input#nom').value,
                        coef: document.querySelector('.newDevoirForm input#coef').value,
                        module: titre.classList[0].replace("mod", ""),
                        prof: id_prof
                    };

                    fetch('../scripts/apiNewDevoir.php', {
                        method: 'POST',
                        body: JSON.stringify(newDevoir)
                    }).then(function (response) {
                        response.json().then(function (result) {
                            if (result == 'ok') {
                                document.querySelector('.newDevoirForm').style.display = 'none';
                                displayPage3(titre.classList[0].replace("mod", ""));
                                titre.classList.remove(titre.classList[0]);
                            }
                            else {
                                alert("Une erreur est survenue lors de l'enregistrement des modifications.");
                            }
                        });
                    });
                }
                else {
                    document.querySelectorAll('.newDevoirForm input').forEach(function (input) {
                        if (input.value == "") {
                            input.style.border = "1px solid red";
                            input.setCustomValidity("Ce champ est obligatoire");
                            input.reportValidity();
                        }
                    });
                }
            }
        });
    });
}