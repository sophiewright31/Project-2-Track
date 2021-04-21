const select = document.getElementById('stylefilter');

select.addEventListener('change', locationChange);

function locationChange(e)
{
    window.location = '/Home/Style/' + e.target.value;
}
