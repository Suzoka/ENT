document.querySelector('form select').addEventListener('change', function () {
    if (this.value != '1') {
        document.querySelector('#numEtudiant').disabled=true;
    } else {
        document.querySelector('#numEtudiant').disabled=false;
    }
});