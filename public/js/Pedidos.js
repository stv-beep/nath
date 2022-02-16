/* comprovacio de tasques per a desabilitar botons */
window.onload = checkLastTask;
function checkLastTask(){
        console.log("unga");
        var primeraTascaLlista = document.getElementsByTagName("tr")[1];
        var idTasca = document.getElementById(primeraTascaLlista);
        //var totalTasca = document.getElementsByTagName("td")[2];
        var totalTasca = document.getElementsByTagName("td")[1].innerHTML;//busco la columna de TOTAL de l'ultima tasca de la taula
        if ((totalTasca.includes("hourglass"))){//<i class=\"fas fa-hourglass-half fa-spin center\"></i>
            console.log("no esta acabat");
            document.getElementById("sendPrepPedido").disabled = true;
            document.getElementById("sendRevisarPedido").disabled = true;
            document.getElementById("sendExpedicions").disabled = true;
            document.getElementById("sendSAF").disabled = true;
        } else {
            console.log("ja esta acabat");
            document.getElementById("sendPrepPedido").disabled = false;
            document.getElementById("sendRevisarPedido").disabled = false;
            document.getElementById("sendExpedicions").disabled = false;
            document.getElementById("sendSAF").disabled = false;
        }
                
    }