/* GEOLOCATION */
/* the onload is in Jornada.js */
window.onload = ordersOnLoad;

function ordersOnLoad(){
    checkLastTask();
    getLocation();
    setInterval(function(){
      window.location.reload(1);
    }, 1800000);//solucio provisional a que se quedigue la mateixa geolocalitzacio encara que no estigues alli ja
}

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                console.log('geolocation not supported')
            }
        }

        function showPosition(position) {
            const coord = position.coords.latitude +" "+ position.coords.longitude;
            const xy = document.getElementsByClassName('xy');
            //una altra solucio provisional per a guardar les coordenades
            for (var i=0;i<xy.length;i++){
                xy[i].value = coord;
            }
            console.log(coord);
            return coord;
        }

        var Recp5Check = document.getElementById("Recp5");
        var Recp6Check = document.getElementById("Recp6");
        Recp5Check.disabled = true;
        Recp6Check.disabled = true;

        function checkLastTask(){
            translateAlerts();
              $.ajax(
                  {
                      type: "GET",
                      url: "/comandes/check",
                      success: function(response){
                        console.log(response)
                        if (response != 0) {
                          console.log(response)
                          console.log(response.id)
                          console.log(response.tasca)
                          var taskID = response.id;
                          var taskName = response.tasca;
                          if (taskID < 5 || taskID > 8){//si la tasca d'un altre tipo NO esta acabada
                            $("#alert-danger-message-final").text('"'+taskName+'" '+tascaNoAcabada);
                            $("#alert-danger").show();
                              
                          } else {//si la tasca NO esta acabada
                            document.getElementById("Recp"+taskID).disabled = false;
                            document.getElementById("Recp"+taskID).classList.toggle('btn-danger');
                          }
      
                        } else {//si la tasca esta acabada i per tant, torna 0
                            Recp5Check.disabled = false;
                            Recp6Check.disabled = false;
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

    /* funcions de RECEPCIONS */
    function startRecp1() {
        translateAlerts();
        var c = document.getElementById('r1').value;
            console.log('sending...');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                    {
                        type: "POST",
                        url: "/recepcio1",
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
                                                        
                            $("#task-message-inici").text(msgPrepComanda);
                            $("#alert-success")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-success").slideUp(1000);
                            });
                            $("#activitats").load(" #activitats");
                                /* window.setTimeout(function(){
                                    window.location = "/comandes";
                                }, 1500);
                                 */
                            /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                            Recp5Check.classList.toggle('btn-danger');
                            var recp6 = document.getElementById("Recp6").disabled
                            document.getElementById("Recp6").disabled = !recp6;
                        }
                    }
                    }
                )
           
        };

    function startRecp2() {
            translateAlerts();
            var c = document.getElementById('r2').value;
                console.log('sending...');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax(
                        {
                            type: "POST",
                            url: "/recepcio2",
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
                                                            
                                $("#task-message-inici").text(msgPrepComanda);
                                $("#alert-success")
                                .fadeTo(4000, 1000)
                                .slideUp(1000, function () {
                                    $("#alert-success").slideUp(1000);
                                });
                                $("#activitats").load(" #activitats");
                                    /* window.setTimeout(function(){
                                        window.location = "/comandes";
                                    }, 1500);
                                     */
                                /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                                Recp6Check.classList.toggle('btn-danger');
                                var recp5 = document.getElementById("Recp5").disabled
                                document.getElementById("Recp5").disabled = !recp5; 
                            }
                        }
                        }
                    )
               
            };