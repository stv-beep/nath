/* comprovacio de tasques per a desabilitar botons */
window.onload = checkLastTask;

    var prepPedidoCheck = document.getElementById("Pedido1");
    var revPedidoCheck = document.getElementById("Pedido2");
    var expedPedidoCheck = document.getElementById("Pedido3");
    var SAFPedidoCheck = document.getElementById("Pedido4");
    prepPedidoCheck.disabled = true;
    revPedidoCheck.disabled = true;
    expedPedidoCheck.disabled = true;
    SAFPedidoCheck.disabled = true;


    function checkLastTask(){
        $.ajax(
            {
                type: "GET",
                url: "/pedidos/check",
                success: function(response){
                  console.log("tasca: "+response);
                  
                  if (response != 0){//si la tasca NO esta acabada
                    document.getElementById("Pedido"+response).disabled = false;
                    document.getElementById("Pedido"+response).classList.toggle('btn-danger');
                  } else {//si la tasca esta acabada i per tant, torna 0
                    prepPedidoCheck.disabled = false;
                    revPedidoCheck.disabled = false;
                    expedPedidoCheck.disabled = false;
                    SAFPedidoCheck.disabled = false;
                  }
                  

                  /* no era la millor forma pero no m'avergonyeixo */

                  /* if (response == 1){
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
                  } */

                },
                error: function(xhr, textStatus, error){
                  $("#alert-danger-missatge-final").text("Sembla que ha hagut un error. Per favor, recarrega la pàgina.");
                  $("#alert-danger")
                  .fadeTo(4000, 1000)
                  .slideUp(1000, function () {
                      $("#alert-danger").slideUp(1000);
                  });
                }
            });
              
    }

    /* aixo si que no era la millor forma pero no m'avergonyeixo */

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