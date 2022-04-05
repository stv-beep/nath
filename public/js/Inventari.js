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

        let compactarCheck = document.getElementById("Inv13");
        let inventariarCheck = document.getElementById("Inv14");
        compactarCheck.disabled = true;
        inventariarCheck.disabled = true;

        function checkLastTask(){
            translateAlerts();
              $.ajax(
                  {
                      type: "GET",
                      url: "/comandes/check",
                      success: function(response){
                        if (response != 0) {
                            console.log(response)
                            console.log(response.id)
                            console.log(response.tasca)
                            var taskID = response.id;
                            var taskName = response.tasca;
                            if (taskID < 13 || taskID > 14){//si la tasca d'un altre tipo NO esta acabada
                              $("#alert-danger-message-final").text('"'+taskName+'" '+tascaNoAcabada);
                              $("#alert-danger").show();
                                
                            } else {//si la tasca NO esta acabada
                              document.getElementById("Inv"+taskID).disabled = false;
                              document.getElementById("Inv"+taskID).classList.toggle('btn-danger');
                            }
        
                          } else {//si la tasca esta acabada i per tant, torna 0
                            compactarCheck.disabled = false;
                            inventariarCheck.disabled = false;
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

    /* funcions de INVENTARI */
    function startCompactar() {
        translateAlerts();
        var c = document.getElementById('inv1').value;
            console.log('sending...');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                    {
                        type: "POST",
                        url: "/inventari/compactar",
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
                                                        
                            $("#task-message-inici").text(msgInvCompactar);
                            $("#alert-success")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-success").slideUp(1000);
                            });
                            //not showing the "no tasks" msg
                            if($('.msgNoTask')[0] !=undefined){
                                $('.msgNoTask')[0].innerHTML= '';
                            }
                            $("#tableInv").load(" #tableInv");
                                /* window.setTimeout(function(){
                                    window.location = "/comandes";
                                }, 1500);
                                 */
                            /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                            compactarCheck.classList.toggle('btn-danger');
                            var inventariar = document.getElementById("Inv14").disabled
                            document.getElementById("Inv14").disabled = !inventariar;
                        }
                    }
                    }
                )
           
        };

function startInventariar() {
    translateAlerts();
    var c = document.getElementById('inv2').value;
    console.log('sending...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            type: "POST",
            url: "/inventari/inventariar",
            data:
            {
                x: c,
                info: navigator.platform + ', ' + navigator.userAgent
            },
            /* $('#formPrepComanda').serialize(), */
            success: function (response) {

                if (response == false) {
                    $("#alert-danger-message-final").text(jornadaNoIniciada);
                    $(".cronometro").hide();
                    $("#alert-danger")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-danger").slideUp(1000);
                        });
                } else {

                    $("#task-message-inici").text(msgInventariar);
                    $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                    //not showing the "no tasks" msg
                    if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                    }
                    $("#tableInv").load(" #tableInv");
                    /* window.setTimeout(function(){
                        window.location = "/comandes";
                    }, 1500);
                     */
                    /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                    inventariarCheck.classList.toggle('btn-danger');
                    var compactar = document.getElementById("Inv13").disabled
                    document.getElementById("Inv13").disabled = !compactar;
                }
            }
        }
    )

};