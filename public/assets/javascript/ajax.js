const powerButtons = document.getElementsByClassName('power-button');
const test = document.getElementById('box-test');

let clickPerSecond = 0;
let start = null;
let currentTime = null;
let maxAuthorized =8;

for (let i = 0; i < powerButtons.length; i++) {
    powerButtons[i].addEventListener('click',e => {
        let powerNumber = document.getElementById('power-' + e.target.dataset.id);
        e.preventDefault();
        currentTime = new Date().getTime();
        if (clickPerSecond === 0) {
            start = new Date().getTime();
        }

        if (currentTime - start > 1000 && clickPerSecond > maxAuthorized) {
            alert('🖱️🔥 Vous allez un peu trop vite, faites une pause ! 🖱🔥️');
            start = new Date().getTime();
            clickPerSecond = 0;
        }

        if (currentTime - start > 1000 && clickPerSecond < maxAuthorized) {
            start = new Date().getTime();
            clickPerSecond = 0;
        }

        clickPerSecond += 1;
        fetch('/Home/powerUpById/' + e.target.dataset.id, {method: 'POST'})
            .then(data => data.json())
            .then(data => displayChanges(data, powerNumber))
    })
}



function displayChanges(data, powerNumber) {

    powerNumber.innerHTML = data.powerSong;
    let testBadge = false;
    for (let i = 0; i < data.badges.length; i++) {
        const p = document.getElementById('badge-name');
        const row = document.getElementById('badge-dynamique');
        if (data.badges[i] !== '') {
            p.innerHTML = data.badges[i];
            testBadge = true;
        }
        if (testBadge) {
            row.classList.toggle('badgeHidden');
        }
    }
}
