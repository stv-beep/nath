/* comprovacio de tasques per a desabilitar botons */
window.onload = checkLastTask;

    let prepPedidoCheck = document.getElementById("sendPrepPedido");
    let revPedidoCheck = document.getElementById("sendRevisarPedido");
    let expedPedidoCheck = document.getElementById("sendExpedicions");
    let SAFPedidoCheck = document.getElementById("sendSAF");
    prepPedidoCheck.disabled = true;
    revPedidoCheck.disabled = true;
    expedPedidoCheck.disabled = true;
    SAFPedidoCheck.disabled = true;


    function checkLastTask(){
        $.ajax(
            {
                type: "GET",
                url: "/pedidos/test",
                success: function(response){
                  //alert(response); //response es la id de la tasca
                  console.log("tasca: "+response);

                  if (response == 1){
                    prepPedidoCheck.disabled = !prepPedidoCheck;
                    prepPedidoCheck.classList.toggle('btn-danger');
                  } else if (response == 2){
                    revPedidoCheck.disabled = !revPedidoCheck;
                    revPedidoCheck.classList.toggle('btn-danger');
                  } else if (response == 3){
                    expedPedidoCheck.disabled = !expedPedidoCheck;
                    expedPedidoCheck.classList.toggle('btn-danger');
                  } else if (response == 4){
                    SAFPedidoCheck.disabled = !SAFPedidoCheck;
                    SAFPedidoCheck.classList.toggle('btn-danger');
                  } else {
                    prepPedidoCheck.disabled = !prepPedidoCheck;
                    revPedidoCheck.disabled = !revPedidoCheck;
                    expedPedidoCheck.disabled = !expedPedidoCheck;
                    SAFPedidoCheck.disabled = !SAFPedidoCheck;
                  }

                }
            });
       
        

                    
    };

/* function checkLastTask(){
    if (document.getElementsByTagName('td').length == 0){
        prepPedidoCheck.disabled = !prepPedidoCheck;
        revPedidoCheck.disabled = !revPedidoCheck;
        expedPedidoCheck.disabled = !expedPedidoCheck;
        SAFPedidoCheck.disabled = !SAFPedidoCheck;
    } else if (!document.getElementsByTagName("td")[1].innerHTML.includes("hourglass")){
        //<i class=\"fas fa-hourglass-half fa-spin center\"></i>
        console.log("ja esta acabat");
        prepPedidoCheck.disabled = !prepPedidoCheck;
        revPedidoCheck.disabled = !revPedidoCheck;
        expedPedidoCheck.disabled = !expedPedidoCheck;
        SAFPedidoCheck.disabled = !SAFPedidoCheck;
    }
} */