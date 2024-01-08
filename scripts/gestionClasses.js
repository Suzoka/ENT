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
    titre.innerHTML = "Gestion des classes";
    backButton.style.display = 'none';
    filAriane.innerHTML = '';
    var newButton = document.createElement('button');
    newButton.classList.add('add');
    newButton.innerHTML = "Ajouter une classe";
    bandeau.appendChild(newButton);
    fetch('../scripts/apiGetAllClass').then(function (response) {
        response.json().then(function (classes) {
            dynamic.innerHTML = '';
            classes.forEach(classe => {
                dynamic.innerHTML += "<button id=\"" + classe.id_classe + "\">" + classe.nom_classe + "</button>";
            });
            document.querySelector('.add').addEventListener('click', function () {
                formulaire("newClass")
            });
            document.querySelectorAll('.dynamic button').forEach(button => {
                button.addEventListener('click', function () {
                    displayPage2(this.getAttribute('id'));
                });
            });
        });
    });
}

function displayPage2(id) {
    page = 2;
    backButton.style.display = 'block';
    filAriane.innerHTML = "<button>Classe</button> \>";
    filArianeTechnical();
    fetch('../scripts/apiGetCompetences?id=' + id).then(function (response) {
        response.json().then(function (competences) {
            try {bandeau.removeChild(document.querySelector('.add'))}catch(e){};
            dynamic.innerHTML = '';
            titre.innerHTML = "Gestion des compétences";
            var newButton = document.createElement('button');
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
            modules.forEach(module => {
                newDiv.innerHTML += "<button id=\"mod" + module.id_module + "\">" + module.nom_module + "</button>";
            });
            document.querySelectorAll('.modules button').forEach(button => {
                button.addEventListener('click', function () {
                    fetch('../scripts/apiGetModule?id=' + this.getAttribute('id').replace("mod", "")).then(function (response) {
                        response.json().then(function (response) {
                            displayPage3(response, id);
                        });
                    });
                });
            });
        });
    });
}

function displayPage3(module, idClasse) {
    page = 3;
    backButton.style.display = 'block';
    filAriane.innerHTML += "<button>Module</button> \>";
    filArianeTechnical();
    bandeau.removeChild(document.querySelector('.add'));
    document.querySelector('.modules').remove();
    dynamic.innerHTML = '';
    titre.innerHTML = module[0].nom_module;
    fetch('../scripts/apiGetCompetences?id=' + idClasse).then(function (response) {
        response.json().then(function (competences) {
            competences.forEach(competence => {
                dynamic.innerHTML += "<div class='coef'><label for='coef" + competence.id_competence + "'>Coefficient " + competence.nom_competence + " : </label><input type='number' name='coef" + competence.id_competence + "' id='coef" + competence.id_competence + "' value='" + (module.find(element => element.ext_id_competence == competence.id_competence) ? module.find(element => element.ext_id_competence == competence.id_competence).coef_module : "") + "'></div>";
            });
            dynamic.innerHTML += "<button class='enregistrer'>Enregistrer</button>";
        });
        setTimeout(function () {
            document.querySelector('button.enregistrer').addEventListener('click', function () {
                var modules = [];
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
}

function formulaire(type, idFrom) {
    popup.style.display = "flex";
    switch (type) {
        case "newClass":
            document.querySelector('.popup h2').innerHTML = "Création d'une nouvelle classe";
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom de la classe : </label><input type='text' name='nom' id='nom' required>";
            var newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            document.querySelector('.confirm').addEventListener('click', function () {
                if (document.querySelector('#nom').value == "") {
                    alert("Veuillez renseigner le nom de la classe.");
                    return;
                }
                var classe = {
                    nom: document.querySelector('#nom').value
                };
                fetch('../scripts/apiCreateClass', {
                    method: 'POST',
                    body: JSON.stringify(classe)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            bandeau.removeChild(document.querySelector('.add'));
                            document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
                            displayPage1();
                            popup.style.display = "none";
                        }
                        else {
                            alert("Une erreur est survenue lors de la création de la classe.");
                        }
                    });
                });
            });
            break;
        case "newComp":
            document.querySelector('.popup h2').innerHTML = "Création d'une nouvelle compétence";
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom de la compétence : </label><input type='text' name='nom' id='nom' required>";
            var newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            document.querySelector('.confirm').addEventListener('click', function () {
                if (document.querySelector('#nom').value == "") {
                    alert("Veuillez renseigner le nom de la classe.");
                    return;
                }
                var competence = {
                    nom: document.querySelector('#nom').value, id: idFrom
                };
                fetch('../scripts/apiCreateComp', {
                    method: 'POST',
                    body: JSON.stringify(competence)
                }).then(function (response) {
                    response.json().then(function (result) {
                        console.log(result);
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

function filArianeTechnical() { }