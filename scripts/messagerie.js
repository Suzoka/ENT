// document.querySelector('form textarea').addEventListener('input', function () {
//     this.style.height = '2.5rem';
//     this.style.height = (this.scrollHeight+0.45) + 'px';
// });

autosize(document.querySelectorAll('form textarea'));

const messageBox = document.querySelector('.messages');
messageBox.scrollTop = messageBox.scrollHeight;