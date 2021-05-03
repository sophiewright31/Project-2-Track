const powerButtons = document.getElementsByClassName('power-button');

for (let i = 0; i < powerButtons.length; i++) {
    powerButtons[i].addEventListener('click',e => {
        let powerNumber = document.getElementById('power-' + e.target.dataset.id);
        e.preventDefault();
        fetch('/Home/powerUpById/' + e.target.dataset.id, {method: 'POST'})
            .then(data => data.json())
            .then(data => powerNumber.innerHTML = data)
    })
}