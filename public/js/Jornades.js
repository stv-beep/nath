/* comprovacio de torns per a desabilitar botons */
window.onload = checkLastTorn;
var inic = document.getElementById("sendInici").disabled = true;
var f = document.getElementById("sendFi").disabled = true;

function checkLastTorn(){
    if (document.getElementsByTagName('td')[1] == undefined){
        document.getElementById("sendInici").disabled = !inic;
    } else if (document.getElementsByTagName('td')[1].innerHTML == ""){//si no s'ha acabat el torn (i no hi ha total)
        document.getElementById("sendFi").disabled = !f;
    } else if (document.getElementsByTagName('td')[1].innerText.length > 0) {
        document.getElementById("sendInici").disabled = !inic;
    }
}