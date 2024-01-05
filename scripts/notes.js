document.querySelectorAll('button.developMod').forEach(function (button) {
    button.addEventListener('click', function () {
        const data = document.querySelector('.data'+this.getAttribute('id'));
        if (this.classList.contains('developed')) {
            this.classList.remove('developed');
            this.parentNode.style.marginBottom = '0.75rem';
            data.style.height = 0;
        }
        else {
            this.classList.add('developed');
            this.parentNode.style.marginBottom = '0.4rem';
            data.style.height = data.scrollHeight+'px';
        }
    });
});