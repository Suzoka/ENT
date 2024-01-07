const popup = document.querySelector('.newProjetForm');
const buttonOpenPopup = document.querySelector('button.newProjet');
const buttonClosePopup = document.querySelector('button.cancel');
const buttonValidatePopup = document.querySelector('button.confirmNewProjet');

buttonOpenPopup.addEventListener('click', function () {
    popup.style.display = 'flex';
});

buttonClosePopup.addEventListener('click', function () {
    popup.style.display = 'none';
});

popup.addEventListener('click', function (event) {
    if (event.target == popup) {
        popup.style.display = 'none';
    }
});