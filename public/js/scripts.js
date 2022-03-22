/* GEOLOCATION */
/* the onload is in Jornada.js */

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

/* SCRIPTS AJAX REQUESTS  */  
     
    /* message variables */
    let msgPrepComanda, msgRevComanda, msgExpedComanda, jornadaNoIniciada, noJornada;

    /* funcions de COMANDES */
    function startPrepComanda() {
        translateAlerts();
        var c = document.getElementById('o1').value;
            console.log('sending...');
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
                console.log('sending...');
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
                                $("#activitats").load(" #activitats");
                                    /* window.setTimeout(function(){
                                        window.location = "/comandes";
                                    }, 1500); */
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
        console.log('sending...');
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
                        $("#activitats").load(" #activitats");
                            /* window.setTimeout(function(){
                                window.location = "/comandes";
                            }, 1500); */
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
        console.log('sending...');
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
                        $("#activitats").load(" #activitats");
                            /* window.setTimeout(function(){
                                window.location = "/comandes";
                            }, 1500); */
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

    /**
     * funcio que NO S'ESTA UTILITZANT ara mateix 
     * ja que les tasques es paren amb el mateix boto,
     * de moment la deixo per si de cas es necessita en un futur
     * @return [type]
     */
    function stopComandes(){
        console.log('sending...');
                $.ajax(
                    {
                            type: "POST",
                            url: "/comandes/stop",
                            data:$('#formStopComandes').serialize(),
                            success: function( data ) {
                                
                                if (document.getElementsByTagName("td")[1].innerHTML.includes("circle-notch")){
                                $("#task-message-inici").text("S'ha parat amb èxit.");
                                $("#alert-success")
                                .fadeTo(4000, 1000)
                                .slideUp(1000, function () {
                                    $("#alert-success").slideUp(1000);
                                });
                                $("#activitats").load(" #activitats");
                                    window.setTimeout(function(){
                                        window.location = "/comandes";
                                    }, 1500);
                                } else {
                                    $("#alert-danger-message-final").text("No hi ha cap tasca per a parar.");
                                    $(".cronometro").hide();
                                    $("#alert-danger")
                                    .fadeTo(4000, 1000)
                                    .slideUp(1000, function () {
                                        $("#alert-danger").slideUp(1000);
                                    });
                                }
                            },//si no s'ha trobat cap registre, retornara error amb alert
                            error: function(xhr, textStatus, error){
                                $("#alert-danger-message-final").text("No hi ha cap tasca per a parar.");
                                $(".cronometro").hide();
                                $("#alert-danger")
                                .fadeTo(4000, 1000)
                                .slideUp(1000, function () {
                                    $("#alert-danger").slideUp(1000);
                                });
                            }
                    }
                )
    };         