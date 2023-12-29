document.querySelectorAll('.card button').forEach((button, i) => {
    button.addEventListener('click', () => {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup'+i).style.display = "block";
    });
});

document.querySelectorAll('.backButton').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.formulaire').forEach(popup => {
            popup.style.display = "none";
        });
        document.querySelector('.flexbox').style.display = "flex";
    });
});