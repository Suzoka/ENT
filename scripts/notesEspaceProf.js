//Déclaration de constantes utilisés régulièrement
const id_prof = document.querySelector('.panel').getAttribute('id').replace("prof", "")
const titre = document.querySelector('.panel h1');
const dynamic = document.querySelector('.dynamic');
const backButton = document.querySelector('button.back');
const filAriane = document.querySelector('.filAriane');
const popup = document.querySelector('.popup');

//Déclaration de variables globales
let page;
let ariane = 0;
let modifs = [];
let erreur = false;
let coef = 0;

//Affichage de la page 1
displayPage1();
//Partie technique du formulaire de création de devoir
technicalNewDevoirForm();

//Ajout d'un évènement au clic sur le bouton de retour
backButton.addEventListener('click', back);

function displayPage1() {
    //Cache le bouton de retour
    backButton.style.display = 'none';
    page = 1;
    //Affiche le titre de la page
    titre.innerHTML = "Sélection de la classe";
    //Récupère les classes du professeur
    fetch('../scripts/apiClassesOfTeacher.php?login=' + id_prof).then(function (response) {
        response.json().then(function (classes) {
            //Vide la partie dynamique de la page
            dynamic.innerHTML = '';
            //Affiche les classes
            classes.forEach(function (classe) {
                dynamic.innerHTML += "<button id=\"" + classe.id_classe + "\">" + classe.nom_classe + "</button>";
            });
            //Appele la partie technique de la page
            setTimeout(function () {
                technicalPage1();
            }, 100);
        });
    });
}

function technicalPage1() {
    //Ajout les clics sur les classes pour afficher les modules associés
    document.querySelectorAll('.dynamic button').forEach(function (button) {
        button.addEventListener('click', function () {
            displayPage2(button.getAttribute('id'));
        });
    });
}

function displayPage2(id_classe) {
    //Récupère les modules de la classe
    fetch('../scripts/apiModOfClasseForTeacher.php?login=' + id_prof + "&classe=" + id_classe).then(function (response) {
        response.json().then(function (modules) {
            //Affiche le titre de la page
            titre.innerHTML = "Sélection du module";
            //Vide la partie dynamique de la page
            dynamic.innerHTML = '';
            //Affiche les modules
            modules.forEach(function (module) {
                dynamic.innerHTML += "<button id=\"" + module.id_module + "\">" + module.nom_module + "</button>";
            });
            setTimeout(function () {
                //Appele la partie technique de la page
                technicalPage2(id_classe);
            }, 100);
        });
    });
}

function technicalPage2(id_classe) {
    //Affiche le bouton de retour
    backButton.style.display = 'block';
    //Ajoute le fil d'Ariane
    filAriane.innerHTML = "<button>Classe</button> \>";
    filArianeTechnical();
    page = 2;
    //Ajoute les clics sur les modules pour afficher les devoirs associés
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
    //Affiche le bouton de création de devoir
    let nouveauDevoir = document.createElement('button');
    nouveauDevoir.innerHTML = "Nouveau devoir";
    nouveauDevoir.classList.add('newDevoir');
    document.querySelector('.title').appendChild(nouveauDevoir);
    //Ajoute un évènement au clic sur le bouton de création de devoir
    document.querySelector('.title .newDevoir').addEventListener('click', function () {
        document.querySelector('.newDevoirForm').style.display = 'flex';
    });
    //Récupère les devoirs du module
    fetch('../scripts/apiDevoirOfModForTeacher.php?login=' + id_prof + "&module=" + id_module).then(function (response) {
        response.json().then(function (devoirs) {
            //Affiche le titre de la page
            titre.innerHTML = "Sélection du devoir";
            //Vide la partie dynamique de la page
            dynamic.innerHTML = '';
            //Affiche les devoirs
            devoirs.forEach(function (devoir) {
                dynamic.innerHTML += "<button id=\"" + devoir.id_devoir + "\">" + devoir.nom_devoir + "</button>";
            });
            setTimeout(function () {
                //Appele la partie technique de la page
                technicalPage3(id_module);
            }, 100);
        });
    });
}

function technicalPage3(id_module) {
    //Stocke l'id du module dans la classe du titre
    titre.classList.add('mod' + id_module);
    page = 3;
    //Ajoute les clics sur les devoirs pour afficher les notes associées
    document.querySelectorAll('.dynamic button').forEach(function (button) {
        button.addEventListener('click', function () {
            //Met à jour le bouton de retour et le fil d'Ariane
            backButton.setAttribute('id', backButton.getAttribute('id') + id_module);
            filAriane.innerHTML += "<button id=\"goto" + id_module + "\">Devoir</button> \>";
            filArianeTechnical();
            displayPage4(button.getAttribute('id'), button.innerHTML);
        });
    });
}

async function displayPage4(id_devoir, nom_devoir) {
    //Récupère le coefficient du devoir
    coef = await getCoefDevoir(id_devoir);
    //Essaye de supprimer le bouton de création de devoir, s'il existe
    try { document.querySelector('.title .newDevoir').remove(); } catch (e) { }
    //Retire la classe du titre
    titre.classList.remove(titre.classList[0]);
    //Récupère les notes du devoir
    fetch('../scripts/apiNotesOfWork.php?classe=' + backButton.getAttribute('id')[4] + "&devoir=" + id_devoir).then(function (response) {
        response.json().then(function (notes) {
            page = 4;
            //Affiche le titre de la page
            titre.innerHTML = nom_devoir;
            //Crée le champ du coefficient du devoir
            let coefDevoir = document.createElement('p');
            coefDevoir.innerHTML = "Coefficient <input type='number' value='" + coef + "'>";
            document.querySelector('.title').appendChild(coefDevoir);
            //Vide la partie dynamique de la page
            dynamic.innerHTML = "";
            //Affiche les notes dans un tableau
            dynamic.innerHTML += "<div class='notes'><table id='" + id_devoir + "'><thead><tr><th scope=\"col\">N°étudiant</th><th scope=\"col\">Nom</th><th scope=\"col\">Prénom</th><th scope=\"col\">Note</th></tr></thead><tbody><tbody></table></div>";
            notes.forEach(function (note) {
                document.querySelector("table tbody").innerHTML += "<tr><td>" + note.numEtud + "</td><th scope=\"row\">" + note.nom + "</th><td>" + note.prenom + "</td><td><input type='number' value='" + (note.valeur != null ? note.valeur : "") + "' id='usr" + note.id + "'></td></tr>";
            });
            //Prépare un possible message d'erreur
            dynamic.innerHTML += "<p class='erreur'>Aucune valeur n'a été modifiée</p><button class='save'>Enregistrer</button>";
            setTimeout(function () {
                //Appele la partie technique de la page
                technicalPage4(notes);
            }, 100);
        });
    });
}

function technicalPage4(notes) {
    //Ajoute un évènement au clic sur le bouton d'enregistrement
    document.querySelector('.save').addEventListener('click', function () {
        //Récupère les modifications
        getModifs(notes);
        //Si des modifications ont été faites, affiche le popup de confirmation
        if (modifs.length > 0) {
            affichePopup();
        }
        //Sinon, affiche le message d'erreur préparé plus tôt
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
    //Vide le tableau des modifications
    modifs = [];
    //Ajoute un objet au tableau pour la modification du coefficient s'il a été changé
    if (document.querySelector('.title input').value != coef) {
        modifs.push({ type: 1, devoir: document.querySelector('table').getAttribute('id'), old_coef: coef, new_coef: document.querySelector('.title input').value });
    }
    //Ajoute un objet au tableau pour chaque modification de note
    document.querySelectorAll('table input').forEach(function (input, i) {
        let test = notes[i].valeur == null ? "" : notes[i].valeur;
        if (input.value.replaceAll(",", ".") != test) {
            modifs.push({ type: 2, id: notes[i].id, nom: notes[i].nom, prenom: notes[i].prenom, old_valeur: notes[i].valeur, new_valeur: input.value.replaceAll(",", ".") != "" ? input.value.replaceAll(",", ".") : null })
        }
    });
};

function affichePopup() {
    //S'il ne reste plus de modifications, cache le popup
    if (modifs.length == 0) {
        popup.style.display = 'none';
        return;
    }
    //Affiche le popup
    popup.style.display = 'flex';
    //Affiche les modifications dans le popup
    modifs.forEach(function (modif) {
        //Si la modification est un changement de coefficient, affiche le coefficient avant et après
        if (modif.type == 1) {
            document.querySelector('.popup .modifs').innerHTML += "<div class='modification'><p>Coefficient : " + modif.old_coef + " -> " + modif.new_coef + "</p><button class='cancelCoef'><p class='sr-only'>Annuler cette modification</p></button></div>";
        }
        //Si la modification est une modification de note, affiche la note avant et après
        if (modif.type == 2) {
            document.querySelector('.popup .modifs').innerHTML += "<div class='modification'><p>" + modif.nom + " " + modif.prenom + " : " + modif.old_valeur + " -> " + modif.new_valeur + "</p><button class='cancel' id='cancel" + modif.id + "'><p class='sr-only'>Annuler cette modification</p></button></div>";
        }
    });
    //Ajoute un évènement au clic sur le bouton d'annulation du coefficient s'il a été changé
    document.querySelectorAll('.cancelCoef').forEach(function (button) {
        button.addEventListener('click', function () {
            //Remet le coefficient à sa valeur initiale
            document.querySelector('.title input').value = modifs[0].old_coef;
            //Supprime la modification du tableau
            modifs.splice(0, 1);
            //Vide le popup
            document.querySelector('.popup .modifs').innerHTML = '';
            //L'appelle à nouveau pour afficher les modifications restantes
            affichePopup();
        });
    });
    //Ajoute un évènement au clic sur les boutons d'annulation des changes de notes
    document.querySelectorAll('.popup .cancel').forEach(function (button, i) {
        button.addEventListener('click', function () {
            //Remet la note à sa valeur initiale
            document.querySelector('table input#usr' + button.getAttribute('id').replace('cancel', '')).value = modifs[i].old_valeur;
            //Supprime la modification du tableau
            modifs.splice(i, 1);
            //Vide le popup
            document.querySelector('.popup .modifs').innerHTML = '';
            //L'appelle à nouveau pour afficher les modifications restantes
            affichePopup();
        });
    });
}

//Ajoute un évènement au clic sur le bouton d'annulation de toutes les modifications
document.querySelector('.popup .cancelAll').addEventListener('click', function () {
    //Cache le popup
    popup.style.display = 'none';
    //Vide le popup et le tableau des modifications
    document.querySelector('.popup .modifs').innerHTML = '';
    modifs = [];
    //Retire le champs du coefficient pour qu'il n'apparaisse pas en double au rechargement de la page
    document.querySelector('.title>p').remove();
    //Recharge la page
    displayPage4(document.querySelector('table').getAttribute('id'), titre.innerHTML);
});

//Ajoute un évènement au clic sur le bouton de confirmation de toutes les modifications
document.querySelector('.popup .confirm').addEventListener('click', function () {
    //Cache le popup
    popup.style.display = 'none';
    //vide le popup
    document.querySelector('.popup .modifs').innerHTML = '';
    //Envoie les modifications à l'API
    fetch('../scripts/apiUpdateNotes.php?devoir=' + document.querySelector('table').getAttribute('id'), {
        method: 'POST',
        body: JSON.stringify(modifs)
    }).then(function (response) {
        response.json().then(function (result) {
            if (result == 'ok') {
                //Si les modifications ont été enregistrées, retire le champs du coefficient pour qu'il n'apparaisse pas en double au rechargement de la page
                document.querySelector('.title>p').remove();
                //Recharge la page
                displayPage4(document.querySelector('table').getAttribute('id'), titre.innerHTML);
            }
            else {
                //Sinon, affiche un message d'erreur
                alert("Une erreur est survenue lors de l'enregistrement des modifications.");
            }
        });
    });
});

//Fonction du bouton retour
function back() {
    //Redirige vers la page précédent la page actuelle
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
    //Ajoute les évènements aux boutons de la fil d'Ariane
    filAriane.querySelectorAll('button').forEach(function (button) {
        //Redirige vers la page correspondante au bouton
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
    //Récupère le coefficient du devoir via l'API
    const response = await fetch('../scripts/apiCoefDevoir.php?devoir=' + id_devoir);
    const result = await response.json();
    return result;
}

function technicalNewDevoirForm() {
    //Réinitialise le style des champs si des données sont entrées
    document.querySelectorAll('.newDevoirForm input').forEach(function (input) {
        input.addEventListener('input', function () {
            this.style.border = "1px solid #ced4da";
            this.setCustomValidity("");
        });
    });
    //Masque le formulaire si on clique en dehors
    document.querySelector('.newDevoirForm').addEventListener('click', function (e) {
        if (e.target.classList.contains('newDevoirForm')) {
            document.querySelector('.newDevoirForm').style.display = 'none';
        }
    });
    //Ajoute les évènements aux boutons du formulaire
    document.querySelectorAll('.newDevoirForm button').forEach(function (button) {
        button.addEventListener('click', function (e) {
            //Si on clique sur le bouton d'annulation, masque le formulaire
            if (e.target.classList.contains('cancel')) {
                document.querySelector('.newDevoirForm').style.display = 'none';
            }
            else {
                //Sinon, envoie le formulaire si il est valide
                if (document.querySelector('.newDevoirForm input#nom').value != "" && document.querySelector('.newDevoirForm input#coef').value != "") {
                    //Crée un objet avec les données du formulaire
                    const newDevoir = {
                        nom: document.querySelector('.newDevoirForm input#nom').value,
                        coef: document.querySelector('.newDevoirForm input#coef').value,
                        module: titre.classList[0].replace("mod", ""),
                        prof: id_prof
                    };
                    //Envoie l'objet à l'API pour créer le devoir
                    fetch('../scripts/apiNewDevoir.php', {
                        method: 'POST',
                        body: JSON.stringify(newDevoir)
                    }).then(function (response) {
                        response.json().then(function (result) {
                            if (result == 'ok') {
                                //Si le devoir a été créé, masque le formulaire et recharge la page
                                document.querySelector('.newDevoirForm').style.display = 'none';
                                displayPage3(titre.classList[0].replace("mod", ""));
                                titre.classList.remove(titre.classList[0]);
                            }
                            else {
                                //Sinon, affiche un message d'erreur
                                alert("Une erreur est survenue lors de l'enregistrement des modifications.");
                            }
                        });
                    });
                }
                //Sinon, affiche un message d'erreur
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