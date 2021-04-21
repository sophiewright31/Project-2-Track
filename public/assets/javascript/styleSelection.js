const select = document.getElementById('stylefilter');

select.addEventListener('change', locationChange);

function locationChange(e)
{
    if (e.target.value === "Tous les Styles") {
        window.location = '/';
    } else {
        window.location = '/Home/Style/' + e.target.value;
    }
}
