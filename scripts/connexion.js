document.querySelectorAll('button').forEach((button, i) => {
    button.addEventListener('click', () => {
        document.querySelector('.flexbox').style.display = "none";
        document.querySelector('#popup'+i).style.display = "block";
    })
})