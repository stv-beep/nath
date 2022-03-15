/* GEOLOCATION */
/* window.onload = getLocation;
    
the onload is in Jornada.js   
} */

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                console.log('geolocation not supported')
            }
        }

        function showPosition(position) {
            const coord = position.coords.latitude +" "+ position.coords.longitude;
            document.getElementById('x').value = coord;
            console.log(coord);
            return coord;
        }

       

/* SCRIPTS D'ENVIAMENT DE PETICIONS AJAX  */  
     
    /* message variables */
    let msgIniciJornada, msgFinalJornada, msgPrepComanda, msgRevComanda, msgExpedComanda, tascaNoAcabada, 
    jornadaNoIniciada, msgError, noJornada;

    /* funcions de jornada */
    function start() {
        translateAlerts();
        var xy = document.getElementById('x').value;
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        console.log('sending...');
        $.ajax(
                {
                    type: "POST",
                    url: "/jornada",//"{{route('jornada.store')}}"
                    data:{x : xy},
                    success: function( data ) {
                        document.getElementById("sendInici").disabled = true;                        
                        $("#alert-message-inici").text(msgIniciJornada);
                        init();//mostra el cronometre
                        cronometrar();//inicia el cronometre
                        $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                            window.setTimeout(function(){
                                window.location = "/home";//"{{route('home')}}"
                            }, 1500);
                    },
                    error: function(xhr, textStatus, error){
                        $("#alert-danger-message-inici").text(msgError);
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

    function end() {
        translateAlerts();
        console.log('sending...');
        $.ajax(
                {
                    type: "POST",
                    url: "/jornada",//"{{route('jornada.store')}}"
                    data:$('#form-final').serialize(),
                    success: function( response ) {

                        if (response == false) {//unfinished task
                            $("#alert-danger-message-final").text(tascaNoAcabada);
                            $(".cronometro").hide();
                            $("#alert-danger")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-danger").slideUp(1000);
                            });
                        } else {
                        
                            $("#alert-message-final").text(msgFinalJornada);
                            $(".cronometro").hide();
                            $("#alert-success")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-success").slideUp(1000);
                            });
                                window.setTimeout(function(){
                                    window.location = "/home";//"{{route('home')}}"
                                }, 1500);
                        }
                    },//si no s'ha trobat cap registre amb inici de jornada, retornara error amb alert
                    error: function(xhr, textStatus, error){
                        $("#alert-danger-message-final").text(noJornada);
                        $(".cronometro").hide();
                        $("#alert-danger")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-danger").slideUp(1000);
                        });
                    }
                    
                }
                
        )        
       
    }

    //window.onload = init;
    function init(){
        /* document.querySelector(".start").addEventListener("click",cronometrar);
        document.querySelector(".stop").addEventListener("click",parar);
        document.querySelector(".reiniciar").addEventListener("click",reiniciar); */
        h = 0;
        m = 0;
        s = 0;
        document.getElementById("hms").innerHTML="00:00:00";
    }         
    function cronometrar(){
        escriure();
        id = setInterval(escriure,1000);
       /*  document.querySelector(".start").removeEventListener("click",cronometrar); */
    }
    function escriure(){
        var hAux, mAux, sAux;
        s++;
        if (s>59){m++;s=0;}
        if (m>59){h++;m=0;}
        if (h>24){h=0;}
    
        if (s<10){sAux="0"+s;}else{sAux=s;}
        if (m<10){mAux="0"+m;}else{mAux=m;}
        if (h<10){hAux="0"+h;}else{hAux=h;}
    
        document.getElementById("hms").innerHTML = hAux + ":" + mAux + ":" + sAux; 
    }

    /* funcions de COMANDES */
    function startPrepComanda() {
        translateAlerts();
            console.log('sending...');
            $.ajax(
                    {
                        type: "POST",
                        url: "/comandes",//"{{route('comandes.store')}}"
                        data:$('#formPrepComanda').serialize(),
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
                console.log('sending...');
                $.ajax(
                    {
                            type: "POST",
                            url: "/comandes/revisio",
                            data:$('#formRevComanda').serialize(),
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
        console.log('sending...');
        $.ajax(
            {
                    type: "POST",
                    url: "/comandes/expedicio",
                    data:$('#formExpedComanda').serialize(),
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
        console.log('sending...');
        $.ajax(
            {
                    type: "POST",
                    url: "/comandes/saf",
                    data:$('#formSAFComanda').serialize(),
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