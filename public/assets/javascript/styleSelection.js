const select = document.getElementById('stylefilter');

select.addEventListener('change', locationChange);

function locationChange(e)
{
    window.location = '/Video/Style/' + e.target.value;
}
