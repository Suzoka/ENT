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

//Affichage de la page 1
displayPage1();

function displayPage1() {
    page = 1;
    //Affichage du titre de la page 1 et masquage du bouton de retour
    titre.innerHTML = "Choix de la classe";
    backButton.style.display = 'none';
    filAriane.innerHTML = '';
    //Récupération des classes
    fetch('../scripts/apiGetAllClass').then(function (response) {
        response.json().then(function (classes) {
            //div vidée pour afficher les classes
            dynamic.innerHTML = '';
            //Affichage des classes
            classes.forEach(classe => {
                dynamic.innerHTML += "<button id=\"" + classe.id_classe + "\">" + classe.nom_classe + "</button>";
            });
            //Ajout d'un évènement sur chaque bouton de classe pour afficher la page 2 de la classe sélectionnée
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
    //Vidage du formulaire pour éviter un doublons d'id
    document.querySelector('.formulaire').innerHTML = "";
    page = 2;
    //Affichage du bouton de retour
    backButton.style.display = 'block';
    //Affichage du fil d'ariane
    filAriane.innerHTML = "<button>Classe</button> \>";
    filArianeTechnical(id);
    //Récupération de toutes les compétences de la classe
    fetch('../scripts/apiGetCompetences?id=' + id).then(function (response) {
        response.json().then(function (competences) {
            //Essaye d'enlever un bouton possiblement déjà présent dans le bandeau
            try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
            //Vide la div dynamique pour afficher les compétences
            dynamic.innerHTML = '';
            //Change le titre de la page
            titre.innerHTML = "Gestion des compétences";
            //Ajoute un bouton d'ajout de compétence dans le bandeau
            newButton = document.createElement('button');
            newButton.classList.add('add');
            newButton.innerHTML = "Ajouter une compétence";
            bandeau.appendChild(newButton);
            //Ajoute un évènement au clic sur le bouton d'ajout de compétence
            document.querySelector('.add').addEventListener('click', function () {
                //Appelle le formulaire d'ajout de compétence
                formulaire("newComp", id)
            });
            //Affichage des compétences
            competences.forEach(competence => {
                dynamic.innerHTML += "<div class='competence'><h2>" + competence.nom_competence + "</h2><button class='delete' id='comp" + competence.id_competence + "'><p class='sr-only'>Supprimer la competence</p></button></div>";
            });
            //Ajout d'un évènement au clic sur le bouton de suppression des compétences
            document.querySelectorAll('button.delete').forEach(button => {
                button.addEventListener('click', function () {
                    //Demande de confirmation avant la suppression
                    if (confirm("Voulez-vous vraiment supprimer cette compétence ? Cette action est irréversible.")) {
                        //Envoie de la requête de suppression
                        fetch('../scripts/apiDeleteCompetence?id=' + this.getAttribute('id').replace("comp", "")).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    //Si la suppression est réussie, recharge la page
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
        });
    });
    //Ajout d'une div pour afficher les modules
    let newDiv = document.createElement('div');
    newDiv.classList.add('modules');
    //Ajout d'un titre
    newDiv.innerHTML = "<h2>Gestion des modules</h2>";
    document.querySelector('body').appendChild(newDiv);
    //Envoie de la requête pour récupérer les modules
    fetch('../scripts/apiGetModules?id=' + id).then(function (response) {
        response.json().then(function (modules) {
            //Ajout d'un bouton d'ajout de module
            newDiv.innerHTML += "<button class=\"add\">Ajouter un module</button>";
            //Affichage des modules
            modules.forEach(module => {
                newDiv.innerHTML += "<button class=\"module\" id=\"mod" + module.id_module + "\">" + module.nom_module + "</button>";
            });
            //Ajout d'un évènement au clic sur le bouton d'ajout de module
            setTimeout(function () {
                document.querySelector('.modules .add').addEventListener('click', function () {
                    //Appelle le formulaire d'ajout de module
                    formulaire("newMod", id);
                });
                //Ajout d'un évènement au clic sur les module
                document.querySelectorAll('.modules button.module').forEach(button => {
                    button.addEventListener('click', function () {
                        fetch('../scripts/apiGetModule?id=' + this.getAttribute('id').replace("mod", "")).then(function (response) {
                            response.json().then(function (response) {
                                //Affiche la page du module sélectionné
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
    //Vidage du formulaire pour éviter un doublons d'id
    document.querySelector('.formulaire').innerHTML = "";
    //Met à jour le bouton de retour
    backButton.setAttribute('id', "from" + idClasse);
    page = 3;
    backButton.style.display = 'block';
    //Met à jour le fil d'ariane
    filAriane.innerHTML += "<button>Module</button> \>";
    filArianeTechnical(idClasse);
    //Essaye d'enlever un bouton possiblement déjà présent dans le bandeau, et la div des modules si elle existe
    try { bandeau.removeChild(document.querySelector('.add')) } catch (e) { };
    try { document.querySelector('.modules').remove() } catch (e) { };
    //Vide la div dynamique pour afficher les coefficients des modules pour les compétences
    dynamic.innerHTML = '';
    //Change le titre de la page
    titre.innerHTML = module[0].nom_module;
    //Récupération des compétences de la classe
    fetch('../scripts/apiGetCompetences?id=' + idClasse).then(function (response) {
        response.json().then(function (competences) {
            //Affichage des coefficients des modules pour les compétences
            competences.forEach(competence => {
                dynamic.innerHTML += "<div class='coef'><label for='coef" + competence.id_competence + "'>Coefficient " + competence.nom_competence + " : </label><input type='number' name='coef" + competence.id_competence + "' id='coef" + competence.id_competence + "' value='" + (module.find(element => element.ext_id_competence == competence.id_competence) ? module.find(element => element.ext_id_competence == competence.id_competence).coef_module : "") + "'></div>";
            });
            //Ajout d'un bouton d'enregistrement des coefficients
            dynamic.innerHTML += "<button class='enregistrer'>Enregistrer</button>";
        });
        //Récupération des professeurs associés au module
        fetch('../scripts/apiGetProfs?id=' + module[0].id_module).then(function (response) {
            response.json().then(function (profs) {
                //Ajout d'une div pour afficher les professeurs
                let newDiv = document.createElement('div');
                newDiv.classList.add('professeurs');
                //Ajout d'un titre
                newDiv.innerHTML = "<h2>Gestion des professeurs sur le module</h2>";
                document.querySelector('body').appendChild(newDiv);

                //Ajout d'un bouton d'ajout de professeur
                newButton = document.createElement('button');
                newButton.classList.add('add');
                newButton.innerHTML = "Ajouter un professeur";
                document.querySelector('.professeurs').appendChild(newButton);

                //Ajout d'un évènement au clic sur le bouton d'ajout de professeur
                setTimeout(function () {
                    document.querySelector('.professeurs .add').addEventListener('click', function () {
                        //Appelle le formulaire d'ajout de professeur
                        formulaire("newProf", module, idClasse)
                    });
                }, 100);
                //Affichage des professeurs, avec un bouton de suppression
                profs.forEach(prof => {
                    document.querySelector('.professeurs').innerHTML += "<div class='professeur'><h2>" + prof.prenom + " " + prof.nom + "</h2><button class='delete' id='prof" + prof.id + "'><p class='sr-only'>Supprimer ce professeur</p></button></div>";
                });
                //Ajout d'un évènement au clic sur le bouton de suppression des professeurs
                document.querySelectorAll('button.delete').forEach(button => {
                    button.addEventListener('click', function () {
                        //Fait la requête de suppression
                        fetch('../scripts/apiDeleteProf?idProf=' + this.getAttribute('id').replace("prof", "") + "&idMod=" + module[0].id_module).then(function (response) {
                            response.json().then(function (response) {
                                if (response == "ok") {
                                    //Si la suppression est réussie, recharge la page, en supprimant les informations qui seront de nouveau chargées
                                    document.querySelector('.professeurs').remove();
                                    filAriane.removeChild(filAriane.lastChild);
                                    filAriane.removeChild(filAriane.lastChild);
                                    displayPage3(module, idClasse);
                                }
                                else {
                                    //Sinon, affiche une erreur
                                    alert("Une erreur est survenue.");
                                }
                            });
                        });
                    });
                });
                //Ajout d'un évènement au clic sur le bouton d'enregistrement des coefficients
                setTimeout(function () {
                    document.querySelector('button.enregistrer').addEventListener('click', function () {
                        //Stocke les coefficients dans un tableau d'objets
                        let modules = [];
                        document.querySelectorAll('.coef input').forEach(input => {
                            modules.push({ idComp: input.getAttribute('id').replace("coef", ""), coef: input.value });
                        });
                        //Envoie la requête de mise à jour des coefficients
                        fetch('../scripts/apiUpdateCoefModule', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: module[0].id_module, modules: modules })
                        }).then(function (response) {
                            response.json().then(function (response) {
                                //Si la mise à jour est réussie, recharge la page
                                if (response == "ok") {
                                    document.querySelector('.professeurs').remove();
                                    displayPage2(idClasse);
                                }
                                //Sinon, affiche une erreur
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
    //Affiche le popup
    popup.style.display = "flex";
    //Teste le type de formulaire à afficher
    switch (type) {
        //Si c'est un formulaire de création de compétence
        case "newComp":
            //Change le titre du popup
            document.querySelector('.popup h2').innerHTML = "Création d'une nouvelle compétence";
            //Ajoute le formulaire
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom de la compétence<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            //Ajoute un bouton de confirmation
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            //Ajoute un évènement au clic sur le bouton de confirmation
            document.querySelector('.confirm').addEventListener('click', function () {
                //Vérifie que le nom de la compétence est renseigné
                if (document.querySelector('#nom').value == "") {
                    //Si ce n'est pas le cas, affiche une erreur
                    alert("Veuillez renseigner le nom de la classe.");
                    return;
                }
                //Envoie la requête de création de compétence
                let competence = {
                    nom: document.querySelector('#nom').value, id: idFrom
                };
                fetch('../scripts/apiCreateComp', {
                    method: 'POST',
                    body: JSON.stringify(competence)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            //Si la création est réussie, recharge la page
                            displayPage2(idFrom);
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
        //Si c'est un formulaire d'ajout de professeur
        case "newProf":
            //Change le titre du popup
            document.querySelector('.popup h2').innerHTML = "Ajout d'un professeur";
            //Ajoute le formulaire
            document.querySelector('.formulaire').innerHTML = "<label for='identite'>Nom du professeur<span class=\"rouge\">*</span> : </label><input type='search' name='identite' id='identite' list='listeProfs' required>";
            //Ajoute une liste déroulante pour sélectionner le professeur
            let newDatalist = document.createElement('datalist');
            newDatalist.setAttribute('id', 'listeProfs');
            document.querySelector('.formulaire').appendChild(newDatalist);
            //Envoie la requête pour récupérer les professeurs
            fetch('../scripts/apiGetAllProfs?id=' + idFrom[0].id_module).then(function (response) {
                response.json().then(function (profs) {
                    profs.forEach(prof => {
                        //Ajoute les professeurs dans la liste déroulante
                        document.querySelector('#listeProfs').innerHTML += "<option id=\"choixProf" + prof.id + "\" value=\"" + prof.prenom + " " + prof.nom + "\">";
                    });
                });
            });
            //Ajoute un bouton de confirmation
            newButton = document.createElement('button');
            newButton.classList.add('confirm');
            newButton.innerHTML = "Confirmer";
            document.querySelector('.popup .boutons').appendChild(newButton);
            //Ajoute un évènement au clic sur le bouton de confirmation
            document.querySelector('.confirm').addEventListener('click', function () {
                let input = document.querySelector('#identite');
                let datalist = document.querySelector('#listeProfs');
                //Vérifie que le professeur a bien été sélectionné dans la liste déroulante
                let exists = Array.from(datalist.options).find(option => option.value === input.value);
                if (!exists) {
                    //Si ce n'est pas le cas, affiche une erreur
                    alert("Veuillez sélectionner le professeur directement dans la liste.");
                    return;
                }
                //Envoie la requête d'ajout du professeur
                let prof = {
                    idProf: exists.getAttribute('id').replace("choixProf", ""), idMod: idFrom[0].id_module
                };
                fetch('../scripts/apiAssignProf', {
                    method: 'POST',
                    body: JSON.stringify(prof)
                }).then(function (response) {
                    response.json().then(function (result) {
                        if (result == 'ok') {
                            //Si l'ajout est réussi, recharge la page
                            document.querySelector('.professeurs').remove();
                            filAriane.removeChild(filAriane.lastChild);
                            filAriane.removeChild(filAriane.lastChild);
                            displayPage3(idFrom, idClasse);
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
        //Si c'est un formulaire de création de module
        case "newMod":
            //Change le titre du popup
            document.querySelector('.popup h2').innerHTML = "Création d'un nouveau module";
            //Ajoute le formulaire
            document.querySelector('.formulaire').innerHTML = "<label for='nom'>Nom du module<span class=\"rouge\">*</span> : </label><input type='text' name='nom' id='nom' required>";
            //Envoie la requête pour récupérer les compétences de la classe
            fetch('../scripts/apiGetCompetences?id=' + idFrom).then(function (response) {
                response.json().then(function (competences) {
                    competences.forEach(competence => {
                        //Ajoute un champs de coefficient pour chaque compétence
                        document.querySelector('.formulaire').innerHTML += "<div class='coef'><label for='coef" + competence.id_competence + "'>Coefficient " + competence.nom_competence + " : </label><input type='number' name='coef" + competence.id_competence + "' id='coef" + competence.id_competence + "'></div>";
                    });
                    //Ajoute un bouton de confirmation
                    newButton = document.createElement('button');
                    newButton.classList.add('confirm');
                    newButton.innerHTML = "Confirmer";
                    document.querySelector('.popup .boutons').appendChild(newButton);
                    setTimeout(function () {
                        //Ajoute un évènement au clic sur le bouton de confirmation
                        document.querySelector('.confirm').addEventListener('click', function () {
                            //Vérifie que le nom du module est renseigné
                            if (document.querySelector('#nom').value == "") {
                                //Si ce n'est pas le cas, affiche une erreur
                                alert("Veuillez renseigner le nom de la classe.");
                                return;
                            }
                            let module = {
                                nom: document.querySelector('#nom').value, coefs: []
                            };
                            //Stocke les coefficients non null dans un tableau d'objets
                            document.querySelectorAll('.coef input').forEach(input => {
                                if (input.value != "") {
                                    module.coefs.push({ idComp: input.getAttribute('id').replace("coef", ""), coef: input.value });
                                }
                            });
                            //Envoie la requête de création de module
                            fetch('../scripts/apiCreateModule', {
                                method: 'POST',
                                body: JSON.stringify(module)
                            }).then(function (response) {
                                response.json().then(function (result) {
                                    if (result == 'ok') {
                                        //Si la création est réussie, recharge la page
                                        document.querySelector('.modules').remove();
                                        displayPage2(idFrom);
                                        popup.style.display = "none";
                                    }
                                    else {
                                        //Sinon, affiche une erreur
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

//Ajout d'un évènement au clic sur le bouton d'annulation du popup
document.querySelector('.cancelAll').addEventListener('click', function () {
    //Cache le popup
    popup.style.display = "none";
    //Enlève le bouton de confirmation
    document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
});

//Ajout d'un évènement au clic sur le fond du popup
popup.addEventListener('click', function (event) {
    if (event.target == popup) {
        //Cache le popup
        popup.style.display = "none";
        //Enlève le bouton de confirmation
        document.querySelector('.popup .boutons').removeChild(document.querySelector('.confirm'));
    }
});

function filArianeTechnical(idClasse) {
    //Ajoute un évènement au clic sur chaque bouton du fil d'ariane
    filAriane.querySelectorAll('button').forEach(function (button) {
        //En fonction du bouton, affiche la page correspondante
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

//Ajout d'un évènement au clic sur le bouton de retour
backButton.addEventListener('click', function () {
    //En fonction de la page actuelle, affiche la page précédente
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