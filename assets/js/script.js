let themeBtn = document.getElementById('themeBtn');
let themeTxt = document.getElementById('themeTxt');
let themeImg = document.getElementById('themeImg');

themeBtn.onclick = function () {
    document.body.classList.toggle('dark-mode');

    if (document.body.classList.contains('dark-mode')) {

        themeTxt.innerHTML = 'Light';
        themeImg.src = '../assets/icons/light-mode.png';
    } else {

        themeTxt.innerHTML = 'Dark';
        themeImg.src = '../assets/icons/dark-mode.png';
    }
}