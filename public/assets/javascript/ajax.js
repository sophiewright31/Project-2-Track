const powerButtons = document.getElementsByClassName('power-button');


for (let i = 0; i < powerButtons.length; i++) {
    powerButtons[i].addEventListener('click',e => {
        let powerNumber = document.getElementById('power-' + e.target.dataset.id);
        e.preventDefault();
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
