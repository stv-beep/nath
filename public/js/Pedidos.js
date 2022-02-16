/* comprovacio de tasques per a desabilitar botons */
window.onload = checkLastTask;

    var prepPedidoCheck = document.getElementById("sendPrepPedido").disabled = true;
    var revPedidoCheck = document.getElementById("sendRevisarPedido").disabled = true;
    var expedPedidoCheck = document.getElementById("sendExpedicions").disabled = true;
    var SAFPedidoCheck = document.getElementById("sendSAF").disabled = true;
function checkLastTask(){
    if (document.getElementsByTagName('td').length == 0){
        document.getElementById("sendPrepPedido").disabled = !prepPedidoCheck;
        document.getElementById("sendRevisarPedido").disabled = !revPedidoCheck;
        document.getElementById("sendExpedicions").disabled = !expedPedidoCheck;
        document.getElementById("sendSAF").disabled = !SAFPedidoCheck;
    } else if (!document.getElementsByTagName("td")[1].innerHTML.includes("hourglass")){
        //<i class=\"fas fa-hourglass-half fa-spin center\"></i>
        console.log("ja esta acabat");
        document.getElementById("sendPrepPedido").disabled = !prepPedidoCheck;
        document.getElementById("sendRevisarPedido").disabled = !revPedidoCheck;
        document.getElementById("sendExpedicions").disabled = !expedPedidoCheck;
        document.getElementById("sendSAF").disabled = !SAFPedidoCheck;
    }
}