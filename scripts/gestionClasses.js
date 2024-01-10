const titre = document.querySelector('h1');
const dynamic = document.querySelector('.dynamic');
const backButton = document.querySelector('button.back');
const filAriane = document.querySelector('.filAriane');
const bandeau = document.querySelector('.title');
const popup = document.querySelector('.popup');
let page;
let ariane = 0;

displayPage1();

function displayPage1() {
    page = 1;
    titre.innerHTML = "Choix de la classe";
    backButton.style.display = 'none';
    filAriane.innerHTML = '';
    fetch('../scripts/apiGetAllClass').then(function (response) {
        response.json().then(function (classes) {
            dynamic.innerHTML = '';
            classes.forEach(classe => {
                dynamic.innerHTML += "<button id=\"" + classe.id_classe + "\">" + classe.nom_classe + "</button>";
            });
            setTimeout(function () {
                document.querySelectorAll('.dynamic button').forEach(button => {
                    button.addEventListener('click', function () {
                        displayPage2(this.getAttribute('id'));
                    });
                });
            }, 100);
        });
    });
}

function displayPage2(id) {
    page = 2;
    backButton.style.display = 'block';
    filAriane.innerHTML = "<button>Classe</button> \>";
    filArianeTechnical(id);
    fetch('../scripts/apiGetCompetences?id=' + id).then(function (response) {
        response.json().then(function (competences) {
            try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
            dynamic.innerHTML = '';
            titre.innerHTML = "Gestion des compétences";
            let newButton = document.createElement('button');
            newButton.classList.add('add');
            newButton.innerHTML = "Ajouter une compétence";
            bandeau.appendChild(newButton);
            document.querySelector('.add').addEventListener('click', function () {
                formulaire("newComp", id)
            });
            competences.forEach(competence => {
                dynamic.innerHTML += "<div class='competence'><h2>" + competence.nom_competence + "</h2><button class='delete' id='comp" + competence.id_competence + "'><p class='sr-only'>Supprimer la competence</p></button></div>";
            });
            document.querySelectorAll('button.delete').forEach(button => {
                button.addEventListener('click', function () {
                    if (confirm("Voulez-vous vraiment supprimer cette compétence ? Cette action est irréversible.")) {
                        fetch('../scripts/apiDeleteCompetence?id=' + this.getAttribute('id').replace("comp", "")).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    displayPage2(id);
                                }
                                else {
                                    alert("Une erreur est survenue.");
                                }
                            });
                        });
                    }
                });
            });
        });
    });
    let newDiv = document.createElement('div');
    newDiv.classList.add('modules');
    newDiv.innerHTML = "<h2>Gestion des modules</h2>";
    document.querySelector('body').appendChild(newDiv);
    fetch('../scripts/apiGetModules?id=' + id).then(function (response) {
        response.json().then(function (modules) {
            newDiv.innerHTML += "<button class=\"add\">Ajouter un module</button>";
            modules.forEach(module => {
                newDiv.innerHTML += "<button class=\"module\" id=\"mod" + module.id_module + "\">" + module.nom_module + "</button>";
            });
            setTimeout(function () {
                document.querySelector('.modules .add').addEventListener('click', function () {
                    formulaire("newMod", id);
                });
                document.querySelectorAll('.modules button.module').forEach(button => {
                    button.addEventListener('click', function () {
                        fetch('../scripts/apiGetModule?id=' + this.getAttribute('id').replace("mod", "")).then(function (response) {
                            response.json().then(function (response) {
                                displayPage3(response, id);
                            });
                        });
                    });
                });
            }, 100);
        });
    });
}

function displayPage3(module, idClasse) {
    backButton.setAttribute('id', "from" + idClasse);
    page = 3;
    backButton.style.display = 'block';
    filAriane.innerHTML += "<button>Module</button> \>";
    filArianeTechnical(idClasse);
    try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
    try { document.querySelector('.modules').remove() } catch (e) { };
    dynamic.innerHTML = '';
    titre.innerHTML = module[0].nom_module;
    fetch('../scripts/apiGetCompetences?id=' + idClasse).then(function (response) {
        response.json().then(function (competences) {
            competences.forEach(competence => {
                dynamic.innerHTML += "<div class='coef'><label for='coef" + competence.id_competence + "'>Coefficient " + competence.nom_competence + " : </label><input type='number' name='coef" + competence.id_competence + "' id='coef" + competence.id_competence + "' value='" + (module.find(element => element.ext_id_competence == competence.id_competence) ? module.find(element => element.ext_id_competence == competence.id_competence).coef_module : "") + "'></div>";
            });
            dynamic.innerHTML += "<button class='enregistrer'>Enregistrer</button>";
        });
        fetch('../scripts/apiGetProfs?id=' + module[0].id_module).then(function (response) {
            response.json().then(function (profs) {

                let newDiv = document.createElement('div');
                newDiv.classList.add('professeurs');
                newDiv.innerHTML = "<h2>Gestion des professeurs sur le module</h2>";
                document.querySelector('body').appendChild(newDiv);

                let newButton = document.createElement('button');
                newButton.classList.add('add');
                newButton.innerHTML = "Ajouter un professeur";
                document.querySelector('.professeurs').appendChild(newButton);
                setTimeout(function () {
                    document.querySelector('.professeurs .add').addEventListener('click', function () {
                        formulaire("newProf", module, idClasse)
                    });
                }, 100);
                profs.forEach(prof => {
                    document.querySelector('.professeurs').innerHTML += "<div class='professeur'><h2>" + prof.prenom + " " + prof.nom + "</h2><button class='delete' id='prof" + prof.id + "'><p class='sr-only'>Supprimer ce professeur</p></button></div>";
                });
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        fetch('../scripts/apiDeleteProf?idProf=' + this.getAttribute('id').replace("prof", "") + "&idMod=" + module[0].id_module).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    document.querySelector('.professeurs').remove();
                                    filAriane.removeChild(filAriane.lastChild);
                                    filAriane.removeChild(filAriane.lastChild);
                                    displayPage3(module, idClasse);
                                }
                                else {
                                    alert("Une erreur est survenue.");
                                }
                            });
                        });
                    });
                });
                setTimeout(function () {
                    document.querySelector('button.enregistrer').addEventListener('click', function () {
                        let modules = [];
                        document.querySelectorAll('.coef input').forEach(input => {
                            modules.push({ idComp: input.getAttribute('id').replace("coef", ""), coef: input.value });
                        });
                        fetch('../scripts/apiUpdateCoefModule', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: module[0].id_module, modules: modules })
                        }).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    document.querySelector('.professeurs').remove();
                                    displayPage2(idClasse);
                                }
                                else {
                                    alert("Une erreur est survenue.");
                                }
                            });
                        });
                    });
                }, 100);
            });
        });
    });
}

function formulaire(type, idFrom, idClasse) {
    popup.style.display = "flex";
    switch (type) {
        case "newComp":
            document.querySelector('.popup h2').innerHTML = "Création d'une nouvelle compétence";
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom de la compétence<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            let newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            document.querySelector('.confirm').addEventListener('click', function () {
                if (document.querySelector('#nom').value == "") {
                    alert("Veuillez renseigner le nom de la classe.");
                    return;
                }
                let competence = {
                    nom: document.querySelector('#nom').value, id: idFrom
                };
                fetch('../scripts/apiCreateComp', {
                    method: 'POST',
                    body: JSON.stringify(competence)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            displayPage2(idFrom);
                            popup.style.display = "none";
                        }
                        else {
                            alert("Une erreur est survenue lors de la création de la classe.");
                        }
                    });
                });
            });
            break;
        case "newProf":
            document.querySelector('.popup h2').innerHTML = "Ajout d'un professeur";
            document.querySelector('.formulaire').innerHTML = "<label for='identite'>Nom du professeur<span class=\"rouge\">*</span> : </label><input type='search' name='identite' id='identite' list='listeProfs' required>";
            let newDatalist = document.createElement('datalist');
            newDatalist.setAttribute('id', 'listeProfs');
            document.querySelector('.formulaire').appendChild(newDatalist);
            fetch('../scripts/apiGetAllProfs').then(function (response) {
                response.json().then(function (profs) {
                    profs.forEach(prof => {
                        document.querySelector('#listeProfs').innerHTML += "<option id=\"choixProf" + prof.id + "\" value=\"" + prof.prenom + " " + prof.nom + "\">";
                    });
                });
            });
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            document.querySelector('.confirm').addEventListener('click', function () {
                let input = document.querySelector('#identite');
                let datalist = document.querySelector('#listeProfs');
                let exists = Array.from(datalist.options).find(option => option.value === input.value);
                if (!exists) {
                    alert("Veuillez sélectionner le professeur directement dans la liste.");
                    return;
                }
                let prof = {
                    idProf: exists.getAttribute('id').replace("choixProf", ""), idMod: idFrom[0].id_module
                };
                fetch('../scripts/apiAssignProf', {
                    method: 'POST',
                    body: JSON.stringify(prof)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            document.querySelector('.professeurs').remove();
                            filAriane.removeChild(filAriane.lastChild);
                            filAriane.removeChild(filAriane.lastChild);
                            displayPage3(idFrom, idClasse);
                            document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
                            popup.style.display = "none";
                        }
                        else {
                            alert("Une erreur est survenue lors de la création de la classe.");
                        }
                    });
                });
            });
            break;
        case "newMod":
            document.querySelector('.popup h2').innerHTML = "Création d'un nouveau module";
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom du module<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            fetch('../scripts/apiGetCompetences?id=' + idFrom).then(function (response) {
                response.json().then(function (competences) {
                    competences.forEach(competence => {
                        document.querySelector('.formulaire').innerHTML += "<div class='coef'><label for='coef" + competence.id_competence + "'>Coefficient " + competence.nom_competence + " : </label><input type='number' name='coef" + competence.id_competence + "' id='coef" + competence.id_competence + "'></div>";
                    });
                    let newButton = document.createElement('button');
                    newButton.classList.add('confirm');
                    newButton.innerHTML = "Confirmer";
                    document.querySelector('.popup .boutons').appendChild(newButton);
                    setTimeout(function () {
                        document.querySelector('.confirm').addEventListener('click', function () {
                            if (document.querySelector('#nom').value == "") {
                                alert("Veuillez renseigner le nom de la classe.");
                                return;
                            }
                            let module = {
                                nom: document.querySelector('#nom').value, coefs: []
                            };
                            document.querySelectorAll('.coef input').forEach(input => {
                                if (input.value != "") {
                                    module.coefs.push({ idComp: input.getAttribute('id').replace("coef", ""), coef: input.value });
                                }
                            });
                            fetch('../scripts/apiCreateModule', {
                                method: 'POST',
                                body: JSON.stringify(module)
                            }).then(function (response) {
                                response.json().then(function (result) {
                                    if (result == 'ok') {
                                        document.querySelector('.modules').remove();
                                        displayPage2(idFrom);
                                        popup.style.display = "none";
                                    }
                                    else {
                                        alert("Une erreur est survenue lors de la création de la classe.");
                                    }
                                });
                            });
                        });
                    }, 100);
                });
            });
            break;
    }
}


document.querySelector('.cancelAll').addEventListener('click', function () {
    popup.style.display = "none";
    document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
});

popup.addEventListener('click', function (event) {
    if (event.target == popup) {
        popup.style.display = "none";
        document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
    }
});

function filArianeTechnical(idClasse) {
    filAriane.querySelectorAll('button').forEach(function (button) {
        switch (button.innerHTML) {
            case "Classe":
                button.addEventListener('click', function () {
                    try { document.querySelector('.title .add').remove(); } catch (e) { }
                    try { document.querySelector('.modules').remove(); } catch (e) { }
                    try { document.querySelector('.professeurs').remove(); } catch (e) { }
                    filAriane.innerHTML = "";
                    displayPage1();
                    ariane = -1;
                });
                break;
            case "Module":
                button.addEventListener('click', function () {
                    try { document.querySelector('.title>p').remove(); } catch (e) { }
                    document.querySelector('.professeurs').remove();
                    displayPage2(idClasse);
                    titre.classList.remove(titre.classList[0]);
                    ariane = 0;
                });
                break;
        }
        ariane++;
    });
}

backButton.addEventListener('click', function () {
    switch (page) {
        case 1:
        default:
            break;
        case 2:
            displayPage1();
            filAriane.innerHTML = "";
            document.querySelector('.modules').remove();
            document.querySelector('.title .add').remove();
            break;
        case 3:
            displayPage2(backButton.getAttribute('id')[backButton.getAttribute('id').length - 1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            titre.classList.remove(titre.classList[0]);
            document.querySelector('.professeurs').remove();
            break;
    }
});