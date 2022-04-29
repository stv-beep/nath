/* comprovacio de tasques per a desabilitar botons */
window.onload = ordersOnLoad;

function ordersOnLoad(){
    checkLastTask();
    getLocation();
    setInterval(function(){
      window.location.reload(1);
    }, 1800000);//solucio provisional a que se quedigue la mateixa geolocalitzacio encara que no estigues alli ja
}

    var prepPedidoCheck = document.getElementById("Order1");
    var revPedidoCheck = document.getElementById("Order2");
    var expedPedidoCheck = document.getElementById("Order3");
    var SAFPedidoCheck = document.getElementById("Order4");
    prepPedidoCheck.disabled = true;
    revPedidoCheck.disabled = true;
    expedPedidoCheck.disabled = true;
    SAFPedidoCheck.disabled = true;


    function checkLastTask(){
      translateAlerts();
        $.ajax(
            {
                type: "GET",
                url: "/comandes/check",
                success: function(response){

                  if (response != 0) {
                    var taskID = response.id;
                    var taskName = response.tasca;
                    if (taskID > 4){//si la tasca d'un altre tipo NO esta acabada
                      $("#alert-danger-message-final").text('"'+taskName+'" '+tascaNoAcabada);
                      $("#alert-danger").show();

                    } else {//si la tasca NO esta acabada
                        document.getElementById("Order"+taskID).disabled = false;
                        document.getElementById("Order"+taskID).classList.toggle('btn-danger');
                    }

                  } else {//si la tasca esta acabada i per tant, torna 0
                    prepPedidoCheck.disabled = false;
                    revPedidoCheck.disabled = false;
                    expedPedidoCheck.disabled = false;
                    SAFPedidoCheck.disabled = false;
                  }
                },
                error: function(xhr, textStatus, error){
                  $("#alert-danger-message-final").text(msgError);
                  $("#alert-danger")
                  .fadeTo(4000, 1000)
                  .slideUp(1000, function () {
                      $("#alert-danger").slideUp(1000);
                  });
                }
            });     
    }

/* SCRIPTS AJAX REQUESTS  */  

    /* funcions de COMANDES */
    function startPrepComanda() {
      translateAlerts();
      var c = document.getElementById('o1').value;//on es guardaran les coordenades
          
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax(
                  {
                      type: "POST",
                      url: "/comandes",//"{{route('comandes.store')}}"
                      data:
                      {x: c,
                      info: navigator.platform+', '+navigator.userAgent},
                      /* $('#formPrepComanda').serialize(), */
                      success: function( response ) {
                          if (response == false){
                              
                              $("#alert-danger-message-final").text(jornadaNoIniciada);
                                      $(".cronometro").hide();
                                      $("#alert-danger")
                                      .fadeTo(4000, 1000)
                                      .slideUp(1000, function () {
                                          $("#alert-danger").slideUp(1000);
                                      });
                          } else {
                          $("#task-message-inici").text(msgPrepComanda);//response[0].tasca
                          $("#alert-success")
                          .fadeTo(4000, 1000)
                          .slideUp(1000, function () {
                              $("#alert-success").slideUp(1000);
                          });
                          //not showing the "no tasks" msg
                            if($('.msgNoTask')[0] !=undefined){
                            $('.msgNoTask')[0].innerHTML= '';
                            }
                            $("#tableComandes").load(" #tableComandes");
                            
                          /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                          prepPedidoCheck.classList.toggle('btn-danger');
                          var revPedido = document.getElementById("Order2").disabled;
                          var expedPedido = document.getElementById("Order3").disabled;
                          var SAFPedido = document.getElementById("Order4").disabled;

                              document.getElementById("Order2").disabled = !revPedido;
                              document.getElementById("Order3").disabled = !expedPedido;
                              document.getElementById("Order4").disabled = !SAFPedido;     
                      }
                  }
                  }
              )
         
      };


  function startRevComanda() {
      translateAlerts();
      var c = document.getElementById('o2').value;
              
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax(
                  {
                          type: "POST",
                          url: "/comandes/revisio",
                          data:{x: c,
                              info: navigator.platform+', '+navigator.userAgent},
                          //$('#formRevComanda').serialize(),
                          success: function( response ) {

                              if (response == false){
                                  $("#alert-danger-message-final").text(jornadaNoIniciada);
                                          $(".cronometro").hide();
                                          $("#alert-danger")
                                          .fadeTo(4000, 1000)
                                          .slideUp(1000, function () {
                                              $("#alert-danger").slideUp(1000);
                                          });
                              } else {
                              
                              $("#task-message-inici").text(msgRevComanda);
                              $("#alert-success")
                              .fadeTo(4000, 1000)
                              .slideUp(1000, function () {
                                  $("#alert-success").slideUp(1000);
                              });
                              //not showing the "no tasks" msg
                            if($('.msgNoTask')[0] !=undefined){
                                $('.msgNoTask')[0].innerHTML= '';
                            }
                            $("#tableComandes").load(" #tableComandes");
                            
                          /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                          revPedidoCheck.classList.toggle('btn-danger');
                          var prepPedido = document.getElementById("Order1").disabled;
                          var expedPedido = document.getElementById("Order3").disabled;
                          var SAFPedido = document.getElementById("Order4").disabled;

                              document.getElementById("Order1").disabled = !prepPedido;
                              document.getElementById("Order3").disabled = !expedPedido;
                              document.getElementById("Order4").disabled = !SAFPedido;
                      }
                  }
                  }
              )
  };

  function startExpedComanda() {
      translateAlerts();
      var c = document.getElementById('o3').value;
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax(
          {
                  type: "POST",
                  url: "/comandes/expedicio",
                  data:{x: c,
                      info: navigator.platform+', '+navigator.userAgent},
                  //$('#formExpedComanda').serialize(),
                  success: function( response ) {
                      if (response == false){
                          $("#alert-danger-message-final").text(jornadaNoIniciada);
                                  $(".cronometro").hide();
                                  $("#alert-danger")
                                  .fadeTo(4000, 1000)
                                  .slideUp(1000, function () {
                                      $("#alert-danger").slideUp(1000);
                                  });
                      } else {
                      
                      $("#task-message-inici").text(msgExpedComanda);
                      $("#alert-success")
                      .fadeTo(4000, 1000)
                      .slideUp(1000, function () {
                          $("#alert-success").slideUp(1000);
                      });
                      //not showing the "no tasks" msg
                        if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                        }
                        $("#tableComandes").load(" #tableComandes");
                        
                  /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                  expedPedidoCheck.classList.toggle('btn-danger');
                  var prepPedido = document.getElementById("Order1").disabled;
                  var revPedido = document.getElementById("Order2").disabled;
                  var SAFPedido = document.getElementById("Order4").disabled;

                      document.getElementById("Order1").disabled = !prepPedido;
                      document.getElementById("Order2").disabled = !revPedido;
                      document.getElementById("Order4").disabled = !SAFPedido;
                  }
              }
          }
      )
  };

  function startSAFComanda() {
      translateAlerts();
      var c = document.getElementById('o4').value;
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax(
          {
                  type: "POST",
                  url: "/comandes/saf",
                  data:{x: c,
                      info: navigator.platform+', '+navigator.userAgent},
                  //$('#formSAFComanda').serialize(),
                  success: function( response ) {
                      if (response == false){
                          $("#alert-danger-message-final").text(jornadaNoIniciada);
                                  $(".cronometro").hide();
                                  $("#alert-danger")
                                  .fadeTo(4000, 1000)
                                  .slideUp(1000, function () {
                                      $("#alert-danger").slideUp(1000);
                                  });
                      } else {
                      $("#task-message-inici").text("SAF");
                      $("#alert-success")
                      .fadeTo(4000, 1000)
                      .slideUp(1000, function () {
                          $("#alert-success").slideUp(1000);
                      });
                      //not showing the "no tasks" msg
                      if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                        }
                        $("#tableComandes").load(" #tableComandes");
                        
                      /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                      SAFPedidoCheck.classList.toggle('btn-danger');
                      var prepPedido = document.getElementById("Order1").disabled;
                      var revPedido = document.getElementById("Order2").disabled;
                      var expedPedido = document.getElementById("Order3").disabled;

                          document.getElementById("Order1").disabled = !prepPedido;
                          document.getElementById("Order2").disabled = !revPedido;
                          document.getElementById("Order3").disabled = !expedPedido;
                      }
              }
          }
      )
  };
