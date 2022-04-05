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

        let descargaCheck = document.getElementById("Recp5");
        let entradaCheck = document.getElementById("Recp6");
        let calidadCheck = document.getElementById("Recp7");
        let ubicarCheck = document.getElementById("Recp8");
        descargaCheck.disabled = true;
        entradaCheck.disabled = true;
        calidadCheck.disabled = true;
        ubicarCheck.disabled = true;

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
                          if (taskID < 5 || taskID > 8){//si la tasca d'un altre tipo NO esta acabada (recep = 4 tasques)
                            $("#alert-danger-message-final").text('"'+taskName+'" '+tascaNoAcabada);
                            $("#alert-danger").show();
                              
                          } else {//si la tasca NO esta acabada
                            document.getElementById("Recp"+taskID).disabled = false;
                            document.getElementById("Recp"+taskID).classList.toggle('btn-danger');
                          }
      
                        } else {//si la tasca esta acabada i per tant, torna 0
                            descargaCheck.disabled = false;
                            entradaCheck.disabled = false;
                            calidadCheck.disabled = false;
                            ubicarCheck.disabled = false;
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
    function startDescarga() {
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
                        url: "/recepcio/descarga",
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
                                                        
                            $("#task-message-inici").text(msgRecepDescarga);
                            $("#alert-success")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-success").slideUp(1000);
                            });
                            //not showing the "no tasks" msg
                            if($('.msgNoTask')[0] !=undefined){
                                $('.msgNoTask')[0].innerHTML= '';
                            }
                            $("#tableRecep").load(" #tableRecep");
                                /* window.setTimeout(function(){
                                    window.location = "/comandes";
                                }, 1500);
                                 */
                            /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                            descargaCheck.classList.toggle('btn-danger');
                            var entrada = document.getElementById("Recp6").disabled
                            var control = document.getElementById("Recp7").disabled
                            var ubicar = document.getElementById("Recp8").disabled

                            document.getElementById("Recp6").disabled = !entrada;
                            document.getElementById("Recp7").disabled = !control;
                            document.getElementById("Recp8").disabled = !ubicar;
                        }
                    }
                    }
                )
           
        };

    function startEntrada() {
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
                            url: "/recepcio/entrada",
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
                                                            
                                $("#task-message-inici").text(msgRecepEntrada);
                                $("#alert-success")
                                .fadeTo(4000, 1000)
                                .slideUp(1000, function () {
                                    $("#alert-success").slideUp(1000);
                                });
                                //not showing the "no tasks" msg
                                if($('.msgNoTask')[0] !=undefined){
                                    $('.msgNoTask')[0].innerHTML= '';
                                }
                                $("#tableRecep").load(" #tableRecep");
                                    /* window.setTimeout(function(){
                                        window.location = "/comandes";
                                    }, 1500);
                                     */
                                /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                                entradaCheck.classList.toggle('btn-danger');
                                var descarga = document.getElementById("Recp5").disabled
                                var control = document.getElementById("Recp7").disabled
                                var ubicar = document.getElementById("Recp8").disabled

                                document.getElementById("Recp5").disabled = !descarga;
                                document.getElementById("Recp7").disabled = !control;
                                document.getElementById("Recp8").disabled = !ubicar;
                            }
                        }
                        }
                    )
               
            };


function startControlCalidad() {
    translateAlerts();
    var c = document.getElementById('r3').value;
    console.log('sending...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            type: "POST",
            url: "/recepcio/calidad",
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

                    $("#task-message-inici").text(msgRecepControl);
                    $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                    //not showing the "no tasks" msg
                    if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                    }
                    $("#tableRecep").load(" #tableRecep");
                    /* window.setTimeout(function(){
                        window.location = "/comandes";
                    }, 1500);
                     */
                    /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                    calidadCheck.classList.toggle('btn-danger');
                    var descarga = document.getElementById("Recp5").disabled
                    var entrada = document.getElementById("Recp6").disabled
                    var ubicar = document.getElementById("Recp8").disabled

                    document.getElementById("Recp5").disabled = !descarga;
                    document.getElementById("Recp6").disabled = !entrada;
                    document.getElementById("Recp8").disabled = !ubicar;
                }
            }
        }
    )

};


function startUbicar() {
    translateAlerts();
    var c = document.getElementById('r4').value;
    console.log('sending...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            type: "POST",
            url: "/recepcio/ubicar",
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

                    $("#task-message-inici").text(msgRecepUbicar);
                    $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                    //not showing the "no tasks" msg
                    if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                    }
                    $("#tableRecep").load(" #tableRecep");
                    /* window.setTimeout(function(){
                        window.location = "/comandes";
                    }, 1500);
                     */
                    /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                    ubicarCheck.classList.toggle('btn-danger');
                    var descarga = document.getElementById("Recp5").disabled
                    var entrada = document.getElementById("Recp6").disabled
                    var control = document.getElementById("Recp7").disabled

                    document.getElementById("Recp5").disabled = !descarga;
                    document.getElementById("Recp6").disabled = !entrada;
                    document.getElementById("Recp7").disabled = !control;
                }
            }
        }
    )

};
