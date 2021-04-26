const select = document.getElementById('stylefilter');

select.addEventListener('change', locationChange);

function locationChange(e)
{
    if (e.target.selectedOptions[0].getAttribute('name') === "Allstyle") {
        window.location = '/';
    } else {
        window.location = '/Home/Style/' + e.target.value;
    }
}
