const titre = document.querySelector('h1');
const dynamic = document.querySelector('.dynamic');
const backButton = document.querySelector('button.back');
const filAriane = document.querySelector('.filAriane');
const bandeau = document.querySelector('.title');
const popup = document.querySelector('.popup');
let page;
let ariane = 0;
let newButton;

displayPage1();

function displayPage1() {
    page = 1;
    titre.innerHTML = "Gestion des classes";
    backButton.style.display = 'none';
    filAriane.innerHTML = '';
    newButton = document.createElement('button');
    newButton.classList.add('add');
    newButton.innerHTML = "Ajouter une classe";
    bandeau.appendChild(newButton);
    fetch('../scripts/apiGetAllClass').then(function (response) {
        response.json().then(function (classes) {
            dynamic.innerHTML = '';
            classes.forEach(classe => {
                dynamic.innerHTML += "<div class='classeButton' role='button' tabindex='0' aria-label='" + classe.nom_classe + "' id='linkClasse" + classe.id_classe + "'><h2>" + classe.nom_classe + "</h2><button class='delete' id='deleteClass" + classe.id_classe + "'><p class='sr-only'>Supprimer la classe</p></button></div>";
            });


            setTimeout(function () {
                document.querySelector('.add').addEventListener('click', function () {
                    formulaire("newClass");
                });

                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        if (confirm("Voulez-vous vraiment supprimer cette classe ? Cette action est irréversible.")) {
                            if (confirm("Cette action va supprimer également toutes les notes associés à la classe. Etes vous certain de vouloir continuer ? Cette action est irréversible.")) {
                                fetch('../scripts/apiDeleteClass?id=' + this.getAttribute('id').replace("deleteClass", "")).then(function (response) {
                                    response.json().then(function (response) {
                                        if (response == "ok") {
                                            displayPage1();
                                        }
                                        else {
                                            console.log(response);
                                            alert("Une erreur est survenue.");
                                        }
                                    });
                                });
                            }
                        }
                    });
                });
                document.querySelectorAll('.classeButton').forEach(groupe => {
                    groupe.addEventListener('click', function (e) {
                        if (e.target.classList.contains('delete')) {
                            return;
                        }
                        displayPage2(this.getAttribute('id').replace("linkClasse", ""));
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
    fetch('../scripts/apiGetGroupes?id=' + id).then(function (response) {
        response.json().then(function (groupes) {
            try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
            dynamic.innerHTML = '';
            titre.innerHTML = "Gestion des groupes de la classe";
            newButton = document.createElement('button');
            newButton.classList.add('add');
            newButton.innerHTML = "Ajouter un groupe";
            bandeau.appendChild(newButton);
            document.querySelector('.add').addEventListener('click', function () {
                formulaire("newGroup", id)
            });
            groupes.forEach(groupe => {
                dynamic.innerHTML += "<div class='groupe' role='button' tabindex='0' aria-label='" + groupe.nom_groupe + "' id='linkGroup" + groupe.id_groupe + "'><h2>" + groupe.nom_groupe + "</h2><button class='delete' id='group" + groupe.id_groupe + "'><p class='sr-only'>Supprimer le groupe</p></button></div>";
            });
            setTimeout(function () {
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        if (confirm("Voulez-vous vraiment supprimer ce groupe ? Cette action est irréversible.")) {
                            fetch('../scripts/apiDeleteGroup?id=' + this.getAttribute('id').replace("group", "")).then(function (response) {
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
                document.querySelectorAll('.groupe').forEach(groupe => {
                    groupe.addEventListener('click', function (e) {
                        if (e.target.classList.contains('delete')) {
                            return;
                        }
                        displayPage3(this.getAttribute('id').replace("linkGroup", ""), id, document.querySelector('#' + this.getAttribute('id') + ' h2').innerHTML);
                    });
                });
            });
        }, 100);
    });
}

function displayPage3(groupe, idClasse, nomGroupe) {
    backButton.setAttribute('id', "from" + idClasse);
    page = 3;
    backButton.style.display = 'block';
    filAriane.innerHTML += "<button>Groupe</button> \>";
    newButton = document.createElement('button');
    newButton.classList.add('add');
    newButton.innerHTML = "Ajouter un étudiant";
    bandeau.appendChild(newButton);
    setTimeout(function () {
        document.querySelector('.add').addEventListener('click', function () {
            formulaire("newEtudiant", groupe, idClasse, nomGroupe)
        });
    }, 100);
    filArianeTechnical(idClasse);
    try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
    dynamic.innerHTML = '';
    titre.innerHTML = nomGroupe;
    fetch('../scripts/apiGetStudentsInGroup?id=' + groupe).then(function (response) {
        response.json().then(function (etudiants) {
            etudiants.forEach(etudiant => {
                dynamic.innerHTML += "<div class='groupe'><h2>" + etudiant.prenom + " " + etudiant.nom + "</h2><button class='delete' id='stud" + etudiant.id + "'><p class='sr-only'>Supprimer le groupe</p></button></div>";
            });
            setTimeout(function () {
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        fetch('../scripts/apiDeleteStudentOfGroup?idStudent=' + this.getAttribute('id').replace("stud", "") + "&idGroupe=" + groupe).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    filAriane.removeChild(filAriane.lastChild);
                                    filAriane.removeChild(filAriane.lastChild);
                                    displayPage3(groupe, idClasse, nomGroupe);
                                }
                                else {
                                    alert("Une erreur est survenue.");
                                }
                            });
                        });
                    });
                });
            }, 100);
        });
    });
}

function formulaire(type, idFrom, idClasse, nomGroupe) {
    popup.style.display = "flex";
    switch (type) {
        case "newClass":
            document.querySelector('.popup h2').innerHTML = "Création d'une nouvelle classe";
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom de la classe<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            newButton = document.createElement('button');
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
        case "newGroup":
            document.querySelector('.popup h2').innerHTML = "Création d'un nouveau groupe";
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom du groupe<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            document.querySelector('.confirm').addEventListener('click', function () {
                if (document.querySelector('#nom').value == "") {
                    alert("Veuillez renseigner le nom du groupe.");
                    return;
                }
                let groupe = {
                    nom: document.querySelector('#nom').value, classe: idFrom
                };
                fetch('../scripts/apiCreateGroup', {
                    method: 'POST',
                    body: JSON.stringify(groupe)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            bandeau.removeChild(document.querySelector('.add'));
                            document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
                            displayPage2(idFrom);
                            popup.style.display = "none";
                        }
                        else {
                            alert("Une erreur est survenue lors de la création du groupe.");
                        }
                    });
                });
            });
            break;
        case "newEtudiant":
            document.querySelector('.popup h2').innerHTML = "Ajout d'un étudiant au groupe";
            document.querySelector('.formulaire').innerHTML = "<label for='identite'>Nom de l'étudiant<span class=\"rouge\">*</span> : </label><input type='search' name='identite' id='identite' list='listeStudent' required>";
            let newDatalist = document.createElement('datalist');
            newDatalist.setAttribute('id', 'listeStudent');
            document.querySelector('.formulaire').appendChild(newDatalist);
            fetch('../scripts/apiGetAllStudents').then(function (response) {
                response.json().then(function (students) {
                    students.forEach(student => {
                        document.querySelector('#listeStudent').innerHTML += "<option id=\"choixStud" + student.id + "\" value=\"" + student.prenom + " " + student.nom + "\">";
                    });
                });
            });
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            document.querySelector('.confirm').addEventListener('click', function () {
                let input = document.querySelector('#identite');
                let datalist = document.querySelector('#listeStudent');
                let exists = Array.from(datalist.options).find(option => option.value === input.value);
                if (!exists) {
                    alert("Veuillez sélectionner l'étudiant directement dans la liste.");
                    return;
                }
                let etudiant = {
                    idEtudiant: exists.getAttribute('id').replace("choixStud", ""), idGroupe: idFrom
                };
                fetch('../scripts/apiAssignStudent', {
                    method: 'POST',
                    body: JSON.stringify(etudiant)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            filAriane.removeChild(filAriane.lastChild);
                            filAriane.removeChild(filAriane.lastChild);
                            displayPage3(idFrom, idClasse, nomGroupe);
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
                    filAriane.innerHTML = "";
                    displayPage1();
                    ariane = -1;
                });
                break;
            case "Groupe":
                button.addEventListener('click', function () {
                    try { document.querySelector('.title .add').remove(); } catch (e) { }
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
            document.querySelector('.title .add').remove();
            break;
        case 3:
            displayPage2(backButton.getAttribute('id')[backButton.getAttribute('id').length - 1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            titre.classList.remove(titre.classList[0]);
            break;
    }
});