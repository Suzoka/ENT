// Pour chaque bouton qui représente un module
document.querySelectorAll('button.developMod').forEach(function (button) {
    //Ajoute un évènement au clic
    button.addEventListener('click', function () {
        const data = document.querySelector('.data'+this.getAttribute('id'));
        //Si le module est déjà développé, le réduire
        if (this.classList.contains('developed')) {
            this.classList.remove('developed');
            this.parentNode.style.marginBottom = '0.75rem';
            data.style.height = 0;
        }
        //Sinon, le développer
        else {
            this.classList.add('developed');
            this.parentNode.style.marginBottom = '0.4rem';
            data.style.height = data.scrollHeight+'px';
        }
    });
});