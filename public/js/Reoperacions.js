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

        let lecturaCheck = document.getElementById("Reop9");
        let embolsarCheck = document.getElementById("Reop10");
        let etiquetarCheck = document.getElementById("Reop11");
        let otrosCheck = document.getElementById("Reop12");
        lecturaCheck.disabled = true;
        embolsarCheck.disabled = true;
        etiquetarCheck.disabled = true;
        otrosCheck.disabled = true;

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
                            if (taskID < 9 || taskID > 12){//si la tasca d'un altre tipo NO esta acabada
                              $("#alert-danger-message-final").text('"'+taskName+'" '+tascaNoAcabada);
                              $("#alert-danger").show();
                                
                            } else {//si la tasca NO esta acabada
                              document.getElementById("Reop"+taskID).disabled = false;
                              document.getElementById("Reop"+taskID).classList.toggle('btn-danger');
                            }
        
                          } else {//si la tasca esta acabada i per tant, torna 0
                            lecturaCheck.disabled = false;
                            embolsarCheck.disabled = false;
                            etiquetarCheck.disabled = false;
                            otrosCheck.disabled = false;
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

    /* funcions de REOPERACIONS */
    function startLectura() {
        translateAlerts();
        var c = document.getElementById('reop1').value;
            console.log('sending...');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                    {
                        type: "POST",
                        url: "/reoperacions/lectura",
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
                                                        
                            $("#task-message-inici").text(msgReopLectura);
                            $("#alert-success")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-success").slideUp(1000);
                            });
                            //not showing the "no tasks" msg
                            if($('.msgNoTask')[0] !=undefined){
                                $('.msgNoTask')[0].innerHTML= '';
                            }
                            $("#tableReop").load(" #tableReop");
                                /* window.setTimeout(function(){
                                    window.location = "/comandes";
                                }, 1500);
                                 */
                            /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                            lecturaCheck.classList.toggle('btn-danger');
                            var embolsar = document.getElementById("Reop10").disabled
                            var etiquetar = document.getElementById("Reop11").disabled
                            var otros = document.getElementById("Reop12").disabled

                            document.getElementById("Reop10").disabled = !embolsar;
                            document.getElementById("Reop11").disabled = !etiquetar;
                            document.getElementById("Reop12").disabled = !otros;
                        }
                    }
                    }
                )
           
        };

function startEmbolso() {
    translateAlerts();
    var c = document.getElementById('reop2').value;
    console.log('sending...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            type: "POST",
            url: "/reoperacions/embolsar",
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

                    $("#task-message-inici").text(msgReopEmbolsar);
                    $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                    //not showing the "no tasks" msg
                    if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                    }
                    $("#tableReop").load(" #tableReop");
                    /* window.setTimeout(function(){
                        window.location = "/comandes";
                    }, 1500);
                     */
                    /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                    embolsarCheck.classList.toggle('btn-danger');
                    var lectura = document.getElementById("Reop9").disabled
                    var etiquetar = document.getElementById("Reop11").disabled
                    var otros = document.getElementById("Reop12").disabled

                    document.getElementById("Reop9").disabled = !lectura;
                    document.getElementById("Reop11").disabled = !etiquetar;
                    document.getElementById("Reop12").disabled = !otros;
                }
            }
        }
    )

};

function startEtiq() {
    translateAlerts();
    var c = document.getElementById('reop3').value;
    console.log('sending...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            type: "POST",
            url: "/reoperacions/etiquetar",
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

                    $("#task-message-inici").text(msgReopEtiquetar);
                    $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                    //not showing the "no tasks" msg
                    if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                    }
                    $("#tableReop").load(" #tableReop");
                    /* window.setTimeout(function(){
                        window.location = "/comandes";
                    }, 1500);
                     */
                    /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                    etiquetarCheck.classList.toggle('btn-danger');
                    var lectura = document.getElementById("Reop9").disabled
                    var embolsar = document.getElementById("Reop10").disabled
                    var otros = document.getElementById("Reop12").disabled

                    document.getElementById("Reop9").disabled = !lectura;
                    document.getElementById("Reop10").disabled = !embolsar;
                    document.getElementById("Reop12").disabled = !otros;
                }
            }
        }
    )

};

function startOtrosReop() {
    translateAlerts();
    var c = document.getElementById('reop4').value;
    console.log('sending...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            type: "POST",
            url: "/reoperacions/otros",
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

                    $("#task-message-inici").text(msgReopOtros);
                    $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                    //not showing the "no tasks" msg
                    if($('.msgNoTask')[0] !=undefined){
                        $('.msgNoTask')[0].innerHTML= '';
                    }
                    $("#tableReop").load(" #tableReop");
                    /* window.setTimeout(function(){
                        window.location = "/comandes";
                    }, 1500);
                     */
                    /* comprovant si els botons estan disabled o no per a deshabilitarlos o no */
                    otrosCheck.classList.toggle('btn-danger');
                    var lectura = document.getElementById("Reop9").disabled
                    var embolsar = document.getElementById("Reop10").disabled
                    var etiquetar = document.getElementById("Reop11").disabled

                    document.getElementById("Reop9").disabled = !lectura;
                    document.getElementById("Reop10").disabled = !embolsar;
                    document.getElementById("Reop11").disabled = !etiquetar;
                }
            }
        }
    )

};