const id_prof = document.querySelector('.panel').getAttribute('id').replace("prof", "")
const titre = document.querySelector('.panel h1');
const boutons = document.querySelector('.boutons');
const backButton = document.querySelector('button.back');
let page;
displayPage1();

backButton.addEventListener('click', back);

function displayPage1() {
    backButton.style.display = 'none';
    page = 1;
    titre.innerHTML = "Sélection de la classe";
    fetch('../scripts/apiClassesOfTeacher.php?login=' + id_prof).then(function (response) {
        response.json().then(function (classes) {
            boutons.innerHTML = '';
            classes.forEach(function (classe) {
                boutons.innerHTML += "<button id=\"" + classe.id_classe + "\">" + classe.nom_classe + "</button>";
            });
            setTimeout(function () {
                technicalPage1();
            }, 100);
        });
    });
}

function technicalPage1() {
    document.querySelectorAll('.boutons button').forEach(function (button) {
        button.addEventListener('click', function () {
            displayPage2(button.getAttribute('id'));
        });
    });
}

function displayPage2(id_classe) {
    fetch('../scripts/apiModOfClasseForTeacher.php?login=' + id_prof + "&classe=" + id_classe).then(function (response) {
        response.json().then(function (modules) {
            titre.innerHTML = "Sélection du module";
            boutons.innerHTML = '';
            modules.forEach(function (module) {
                boutons.innerHTML += "<button id=\"" + module.id_module + "\">" + module.nom_module + "</button>";
            });
            setTimeout(function(){
                technicalPage2(id_classe);
            }, 100);
        });
    });
}

function technicalPage2(id_classe) {
    backButton.style.display = 'block';
    page = 2;
    document.querySelectorAll('.boutons button').forEach(function (button) {
        button.addEventListener('click', function () {
            backButton.setAttribute('id', "from"+id_classe);
            displayPage3(button.getAttribute('id'));
        });
    });
}

function displayPage3(id_module) {
    fetch('../scripts/apiDevoirOfModForTeacher.php?login=' + id_prof + "&module=" + id_module).then(function (response) {
        response.json().then(function (devoirs) {
            titre.innerHTML = "Sélection du devoir";
            boutons.innerHTML = '';
            devoirs.forEach(function (devoir) {
                boutons.innerHTML += "<button id=\"" + devoir.id_devoir + "\">" + devoir.nom_devoir + "</button>";
            });
            setTimeout(function(){
                technicalPage3(id_module);
            }, 100);
        });
    });
}

function technicalPage3(id_module) {
    page = 3;
    document.querySelectorAll('.boutons button').forEach(function (button) {
        button.addEventListener('click', function () {
            backButton.setAttribute('id', backButton.getAttribute('id')+id_module);
            displayPage4(button.getAttribute('id'));
        });
    });
}

function displayPage4(id_devoir) {
    fetch('../scripts/apiNotesOfWork.php?login=' + id_prof + "&devoir=" + id_devoir).then(function (response) {
        response.json().then(function (notes) {
        });
    });
}

function back() {
    switch (page) {
        case 1:
        default:
            break;
        case 2:
            displayPage1();
            break;
        case 3:
            displayPage2(backButton.getAttribute('id')[backButton.getAttribute('id').length-1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            break;
        case 4:
            displayPage3(backButton.getAttribute('id')[backButton.getAttribute('id').length-1]);
            backButton.setAttribute('id', backButton.getAttribute('id').slice(0, -1));
            break;
    }
}