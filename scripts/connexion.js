const url = new URL(document.location.href);

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

if (url.searchParams.get('error')) {
    if (url.searchParams.get('error').split('!')[0] === 'usr') {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup0').style.display = "block";
    }
    if (url.searchParams.get('error').split('!')[0] === 'prof') {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup1').style.display = "block";
    }
}