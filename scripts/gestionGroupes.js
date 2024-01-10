//Déclaration de différentes constantes régulièrement utilisées
const titre = document.querySelector('h1');
const dynamic = document.querySelector('.dynamic');
const backButton = document.querySelector('button.back');
const filAriane = document.querySelector('.filAriane');
const bandeau = document.querySelector('.title');
const popup = document.querySelector('.popup');

//Déclaration de variables globales
let page;
let ariane = 0;
let newButton;

//Affiche la page 1
displayPage1();

function displayPage1() {
    page = 1;
    //Affiche le titre
    titre.innerHTML = "Gestion des classes";
    //Cache le bouton de retour et vide le fil d'ariane
    backButton.style.display = 'none';
    filAriane.innerHTML = '';
    //Créer le bouton d'ajout de classe
    newButton = document.createElement('button');
    newButton.classList.add('add');
    newButton.innerHTML = "Ajouter une classe";
    bandeau.appendChild(newButton);
    //Récupère les classes
    fetch('../scripts/apiGetAllClass').then(function (response) {
        response.json().then(function (classes) {
            //Vide la zone dynamique
            dynamic.innerHTML = '';
            //Affiche les classes
            classes.forEach(classe => {
                dynamic.innerHTML += "<div class='classeButton' role='button' tabindex='0' aria-label='" + classe.nom_classe + "' id='linkClasse" + classe.id_classe + "'><h2>" + classe.nom_classe + "</h2><button class='delete' id='deleteClass" + classe.id_classe + "'><p class='sr-only'>Supprimer la classe</p></button></div>";
            });

            //Ajoute les évènements sur le bouton d'ajout de classe
            setTimeout(function () {
                document.querySelector('.add').addEventListener('click', function () {
                    //Affiche le formulaire de création de classe
                    formulaire("newClass");
                });

                //Ajoute les évènements sur les boutons de suppression de classe
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        //Demande une confirmation avant de supprimer la classe
                        if (confirm("Voulez-vous vraiment supprimer cette classe ? Cette action est irréversible.")) {
                            //Demande une double confirmation avant de supprimer la classe car il s'agit d'une action supprimant énormément de données
                            if (confirm("Cette action va supprimer également toutes les notes associés à la classe. Etes vous certain de vouloir continuer ? Cette action est irréversible.")) {
                                //Envoie une requête à l'API pour supprimer la classe
                                fetch('../scripts/apiDeleteClass?id=' + this.getAttribute('id').replace("deleteClass", "")).then(function (response) {
                                    response.json().then(function (response) {
                                        if (response == "ok") {
                                            //Si la classe est supprimée, affiche la page 1
                                            displayPage1();
                                        }
                                        else {
                                            //Sinon, affiche une erreur
                                            alert("Une erreur est survenue.");
                                        }
                                    });
                                });
                            }
                        }
                    });
                });
                //Ajoute les évènements sur les boutons de chaque classe
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
    //Affiche le bouton de retour
    backButton.style.display = 'block';
    //Créer le fil d'ariane
    filAriane.innerHTML = "<button>Classe</button> \>";
    filArianeTechnical(id);
    //Récupère les groupes de la classe
    fetch('../scripts/apiGetGroupes?id=' + id).then(function (response) {
        response.json().then(function (groupes) {
            //Essaye de supprimer un bouton d'ajout possiblement existant dans le bandeau
            try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
            //Vide la zone dynamique
            dynamic.innerHTML = '';
            //Affiche le titre
            titre.innerHTML = "Gestion des groupes de la classe";
            //Créer le bouton d'ajout de groupe
            newButton = document.createElement('button');
            newButton.classList.add('add');
            newButton.innerHTML = "Ajouter un groupe";
            bandeau.appendChild(newButton);
            //Ajoute l'évènements clic sur le bouton d'ajout de groupe
            document.querySelector('.add').addEventListener('click', function () {
                //Affiche le formulaire de création de groupe
                formulaire("newGroup", id)
            });
            //Affiche les groupes
            groupes.forEach(groupe => {
                dynamic.innerHTML += "<div class='groupe' role='button' tabindex='0' aria-label='" + groupe.nom_groupe + "' id='linkGroup" + groupe.id_groupe + "'><h2>" + groupe.nom_groupe + "</h2><button class='delete' id='group" + groupe.id_groupe + "'><p class='sr-only'>Supprimer le groupe</p></button></div>";
            });
            setTimeout(function () {
                //Ajoute les évènements sur les boutons de suppression de groupe
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        //Demande une confirmation avant de supprimer le groupe
                        if (confirm("Voulez-vous vraiment supprimer ce groupe ? Cette action est irréversible.")) {
                            //Envoie une requête à l'API pour supprimer le groupe
                            fetch('../scripts/apiDeleteGroup?id=' + this.getAttribute('id').replace("group", "")).then(function (response) {
                                response.json().then(function (response) {
                                    if (response == "ok") {
                                        //Si le groupe est supprimé, la page est rafraichie
                                        displayPage2(id);
                                    }
                                    else {
                                        //Sinon, affiche une erreur
                                        alert("Une erreur est survenue.");
                                    }
                                });
                            });
                        }
                    });
                });
                //Ajoute les évènements sur les boutons de chaque groupe
                document.querySelectorAll('.groupe').forEach(groupe => {
                    groupe.addEventListener('click', function (e) {
                        //Si le clic est sur le bouton de suppression, ne rien faire
                        if (e.target.classList.contains('delete')) {
                            return;
                        }
                        //Sinon, affiche la page 3
                        displayPage3(this.getAttribute('id').replace("linkGroup", ""), id, document.querySelector('#' + this.getAttribute('id') + ' h2').innerHTML);
                    });
                });
            });
        }, 100);
    });
}

function displayPage3(groupe, idClasse, nomGroupe) {
    //Met à jour le bouton de retour
    backButton.setAttribute('id', "from" + idClasse);
    page = 3;
    backButton.style.display = 'block';
    //Met à jour le fil d'ariane
    filAriane.innerHTML += "<button>Groupe</button> \>";
    filArianeTechnical(idClasse);
    //Créer le bouton d'ajout d'étudiant
    newButton = document.createElement('button');
    newButton.classList.add('add');
    newButton.innerHTML = "Ajouter un étudiant";
    bandeau.appendChild(newButton);
    //Ajoute l'évènement clic sur le bouton d'ajout d'étudiant
    setTimeout(function () {
        document.querySelector('.add').addEventListener('click', function () {
            //Affiche le formulaire d'ajout d'étudiant
            formulaire("newEtudiant", groupe, idClasse, nomGroupe)
        });
    }, 100);
    //Essaye de supprimer un bouton d'ajout possiblement existant dans le bandeau
    try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
    //Vide la zone dynamique
    dynamic.innerHTML = '';
    //Affiche le titre
    titre.innerHTML = nomGroupe;
    //Récupère les étudiants du groupe
    fetch('../scripts/apiGetStudentsInGroup?id=' + groupe).then(function (response) {
        response.json().then(function (etudiants) {
            //Affiche les étudiants
            etudiants.forEach(etudiant => {
                dynamic.innerHTML += "<div class='groupe'><h2>" + etudiant.prenom + " " + etudiant.nom + "</h2><button class='delete' id='stud" + etudiant.id + "'><p class='sr-only'>Supprimer le groupe</p></button></div>";
            });
            setTimeout(function () {
                //Ajoute les évènements sur les boutons de suppression d'étudiant
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        //Envoyer une requête à l'API pour supprimer l'étudiant du groupe
                        fetch('../scripts/apiDeleteStudentOfGroup?idStudent=' + this.getAttribute('id').replace("stud", "") + "&idGroupe=" + groupe).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    //Si l'étudiant est supprimé, la page est rafraichie
                                    filAriane.removeChild(filAriane.lastChild);
                                    filAriane.removeChild(filAriane.lastChild);
                                    displayPage3(groupe, idClasse, nomGroupe);
                                }
                                else {
                                    //Sinon, affiche une erreur
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
    //Affiche le formulaire correspondant au type
    switch (type) {
        //Si c'est un formulaire de création de classe
        case "newClass":
            //Affiche le titre
            document.querySelector('.popup h2').innerHTML = "Création d'une nouvelle classe";
            //Affiche le formulaire
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom de la classe<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            //Créer le bouton de confirmation
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            //Ajoute l'évènement clic sur le bouton de confirmation
            document.querySelector('.confirm').addEventListener('click', function () {
                //Si le nom de la classe n'est pas renseigné, affiche une erreur
                if (document.querySelector('#nom').value == "") {
                    alert("Veuillez renseigner le nom de la classe.");
                    return;
                }
                //Créer un objet classe
                var classe = {
                    nom: document.querySelector('#nom').value
                };
                //Envoie une requête à l'API pour créer la classe
                fetch('../scripts/apiCreateClass', {
                    method: 'POST',
                    body: JSON.stringify(classe)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            //Si la classe est créée, rafraichir la page
                            bandeau.removeChild(document.querySelector('.add'));
                            document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
                            displayPage1();
                            popup.style.display = "none";
                        }
                        else {
                            //Sinon, affiche une erreur
                            alert("Une erreur est survenue lors de la création de la classe.");
                        }
                    });
                });
            });
            break;
        //Si c'est un formulaire de création de groupe
        case "newGroup":
            //Affiche le titre
            document.querySelector('.popup h2').innerHTML = "Création d'un nouveau groupe";
            //Affiche le formulaire
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom du groupe<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            //Créer le bouton de confirmation
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            //Ajoute l'évènement clic sur le bouton de confirmation
            document.querySelector('.confirm').addEventListener('click', function () {
                //Si le nom du groupe n'est pas renseigné, affiche une erreur
                if (document.querySelector('#nom').value == "") {
                    alert("Veuillez renseigner le nom du groupe.");
                    return;
                }
                //Créer un objet groupe
                let groupe = {
                    nom: document.querySelector('#nom').value, classe: idFrom
                };
                //Envoie une requête à l'API pour créer le groupe
                fetch('../scripts/apiCreateGroup', {
                    method: 'POST',
                    body: JSON.stringify(groupe)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            //Si le groupe est créé, rafraichir la page
                            bandeau.removeChild(document.querySelector('.add'));
                            document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
                            displayPage2(idFrom);
                            popup.style.display = "none";
                        }
                        else {
                            //Sinon, affiche une erreur
                            alert("Une erreur est survenue lors de la création du groupe.");
                        }
                    });
                });
            });
            break;
        //Si c'est un formulaire d'ajout d'étudiant
        case "newEtudiant":
            //Affiche le titre
            document.querySelector('.popup h2').innerHTML = "Ajout d'un étudiant au groupe";
            //Affiche le formulaire
            document.querySelector('.formulaire').innerHTML = "<label for='identite'>Nom de l'étudiant<span class=\"rouge\">*</span> : </label><input type='search' name='identite' id='identite' list='listeStudent' required>";
            //Créer la liste déroulante
            let newDatalist = document.createElement('datalist');
            newDatalist.setAttribute('id', 'listeStudent');
            document.querySelector('.formulaire').appendChild(newDatalist);
            //Récupère les étudiants qui doivent être affichés dans la liste déroulante
            fetch('../scripts/apiGetAllStudents?id=' + idFrom).then(function (response) {
                response.json().then(function (students) {
                    //Affiche les étudiants dans la liste déroulante
                    students.forEach(student => {
                        document.querySelector('#listeStudent').innerHTML += "<option id=\"choixStud" + student.id + "\" value=\"" + student.prenom + " " + student.nom + "\">";
                    });
                });
            });
            //Créer le bouton de confirmation
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            //Ajoute l'évènement clic sur le bouton de confirmation
            document.querySelector('.confirm').addEventListener('click', function () {
                let input = document.querySelector('#identite');
                let datalist = document.querySelector('#listeStudent');
                //Vérifie que l'étudiant sélectionné existe bien dans la liste déroulante
                let exists = Array.from(datalist.options).find(option => option.value === input.value);
                if (!exists) {
                    //Si l'étudiant n'existe pas, affiche une erreur
                    alert("Veuillez sélectionner l'étudiant directement dans la liste.");
                    return;
                }
                //Créer un objet étudiant
                let etudiant = {
                    idEtudiant: exists.getAttribute('id').replace("choixStud", ""), idGroupe: idFrom
                };
                //Envoie une requête à l'API pour ajouter l'étudiant au groupe
                fetch('../scripts/apiAssignStudent', {
                    method: 'POST',
                    body: JSON.stringify(etudiant)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            //Si l'étudiant est ajouté, rafraichir la page
                            filAriane.removeChild(filAriane.lastChild);
                            filAriane.removeChild(filAriane.lastChild);
                            displayPage3(idFrom, idClasse, nomGroupe);
                            document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
                            popup.style.display = "none";
                        }
                        else {
                            //Sinon, affiche une erreur
                            alert("Une erreur est survenue lors de la création de la classe.");
                        }
                    });
                });
            });
            break;
    }
}

//Ajoute l'évènement clic sur le bouton d'annulation du popup
document.querySelector('.cancelAll').addEventListener('click', function () {
    //Cache le popup
    popup.style.display = "none";
    //Supprime le bouton de confirmation
    document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
});

//Ajoute l'évènement clic sur le fond du popup
popup.addEventListener('click', function (event) {
    if (event.target == popup) {
        //Cache le popup
        popup.style.display = "none";
        //Supprime le bouton de confirmation
        document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
    }
});

function filArianeTechnical(idClasse) {
    //Ajoute les évènements sur les boutons du fil d'ariane
    filAriane.querySelectorAll('button').forEach(function (button) {
        //Redirige vers la page correspondante au clic dans le fil d'ariane
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

//Ajoute l'évènement clic sur le bouton de retour
backButton.addEventListener('click', function () {
    //Affiche la page précédent la page actuelle
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