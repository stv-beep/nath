/* comprovacio de torns per a desabilitar botons */
window.onload = checkLastTorn;
let inic = document.getElementById("sendInici");
let f = document.getElementById("sendFi");
inic.disabled = true;
f.disabled = true;

function checkLastTorn(){
    if (document.getElementsByTagName('td')[1] == undefined){
        inic.disabled = !inic;
    } else if (document.getElementsByTagName('td')[1].innerHTML == ""){//si no s'ha acabat el torn (i no hi ha total)
        f.disabled = !f;
    } else if (document.getElementsByTagName('td')[1].innerText.length > 0) {
        inic.disabled = !inic;
    }
}