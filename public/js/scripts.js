/* SCRIPT ALEIX  */  

    //https://stackoverflow.com/questions/41632942/how-to-measure-time-elapsed-on-javascript
    //https://stackoverflow.com/questions/1210701/compute-elapsed-time/1210726
    //https://ralzohairi.medium.com/displaying-dynamic-elapsed-time-in-javascript-260fa0e95049

    //https://gomakethings.com/how-to-show-and-hide-elements-with-vanilla-javascript/
     
    /* $(document).ready(function () {

        $("#alert-success").removeClass("hidden");
        $("#alert-danger").removeClass("hidden");

        $("#alert-success").hide();
        $("#alert-danger").hide();
    }); */


   /*  jQuery(function () {

    });  */


    var startTime, endTime, h;

    function start() {
    //startTime = new Date();
    
    startTime = moment().format('YYYY-MM-DD[T]HH:mm:ss');
    console.log(moment().format(startTime));

    //$("#inici-jornada").val(startTime);
    console.log(startTime);
        
        //alert('funco send');
        console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "/home",//"{{route('jornada.store')}}"
                    data:$('#form-inici').serialize(),
                    success: function( data ) {
                        document.getElementById("sendInici").disabled = true;
                        
                        //console.log(data);
                        //$("#total_cron").val();
                        //$("#inici-jornada").val(startTime);
                        //alert('enviat');
                        
                        $("#alert-missatge-inici").text("Jornada iniciada amb èxit");
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
                        $("#alert-danger-missatge-inici").text("Ha hagut un error. Per favor, torna-ho a intentar.");
                        $(".cronometro").hide();
                        $("#alert-danger")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-danger").slideUp(1000);
                        });
                    }
                }
            )
        return startTime;
    };

    function end() {
    //endTime = new Date();
    /* endTime = moment().format('YYYY-MM-DD[T]HH:mm:ss');
    var timeDiff = endTime - startTime; //in ms
    // strip the ms
    timeDiff = timeDiff / 1000;


    // get seconds 
    var segons = Math.round(timeDiff);
    var minuts = segons/60;
    var min = Math.round((minuts + Number.EPSILON) * 100) / 100;
    var hores = minuts/60;
    h = Math.round((hores + Number.EPSILON) * 100) / 100;
    console.log(segons + " segons");
    console.log(min + " minuts");
    console.log(h + " hores")
    console.log(startTime);
    console.log(endTime);

    //guardo HORES al input
    $("#total_cron").val(h);
        let cron = document.getElementById("total_cron").value;
        if (cron.length < 0){
            console.log("cron = "+cron);
        } else {
            console.log("cronn = "+cron);
        }
 */
    
    //$("#final-jornada").val(endTime);
    
    console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "/activitat",//"{{route('jornada.store')}}"
                    data:$('#form-final').serialize(),
                    success: function( response ) {
                        //console.log(response);
                        //$("#total_cron").val();
                        //$("#final-jornada").val();
                        //window.location = "/home";
                        $("#alert-missatge-final").text("Jornada finalitzada amb èxit");
                        $(".cronometro").hide();
                        $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                            window.setTimeout(function(){
                                window.location = "/home";//"{{route('home')}}"
                            }, 1500);
                        
                    },//si no s'ha trobat cap registre amb inici de jornada, retornara error amb alert
                    error: function(xhr, textStatus, error){
                        $("#alert-danger-missatge-final").text("No s'ha trobat cap inici de jornada coincident amb el teu registre.");
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


    function startPrepPedido() {
            console.log('funco send');
            $.ajax(
                    {
                        type: "POST",
                        url: "/pedidos",//"{{route('pedidos.store')}}"
                        data:$('#formPrepPedido').serialize(),
                        success: function( data ) {
                                                        
                            $("#prepPedido-missatge-inici").text("Preparació pedido");
                          /*   init();//mostra el cronometre
                            cronometrar();//inicia el cronometre */
                            $("#alert-success")
                            .fadeTo(4000, 1000)
                            .slideUp(1000, function () {
                                $("#alert-success").slideUp(1000);
                            });
                            $("#activitats").load(" #activitats");
                                /* window.setTimeout(function(){
                                    window.location = "/pedidos";
                                }, 1500);
                                 */
                            /* comprovant si els botons estan disabled o no per a desabilitarlos o no */
                            var revPedido = document.getElementById("sendRevisarPedido").disabled;
                            var expedPedido = document.getElementById("sendExpedicions").disabled;
                            var SAFPedido = document.getElementById("sendSAF").disabled;

                                document.getElementById("sendRevisarPedido").disabled = !revPedido;
                                document.getElementById("sendExpedicions").disabled = !expedPedido;
                                document.getElementById("sendSAF").disabled = !SAFPedido;     
                        }
                    }
                )
           
        };


    function startRevPedido() {
                console.log('funco send');
                $.ajax(
                    {
                            type: "POST",
                            url: "/pedidos/revisio",
                            data:$('#formRevPedido').serialize(),
                            success: function( data ) {
                                
                                $("#prepPedido-missatge-inici").text("Revisió pedido");
                              /*   init();//mostra el cronometre
                                cronometrar();//inicia el cronometre */
                                $("#alert-success")
                                .fadeTo(4000, 1000)
                                .slideUp(1000, function () {
                                    $("#alert-success").slideUp(1000);
                                });
                                $("#activitats").load(" #activitats");
                                    /* window.setTimeout(function(){
                                        window.location = "/pedidos";
                                    }, 1500); */
                            /* comprovant si els botons estan disabled o no per a desabilitarlos o no */
                            var prepPedido = document.getElementById("sendPrepPedido").disabled;
                            var expedPedido = document.getElementById("sendExpedicions").disabled;
                            var SAFPedido = document.getElementById("sendSAF").disabled;

                                document.getElementById("sendPrepPedido").disabled = !prepPedido;
                                document.getElementById("sendExpedicions").disabled = !expedPedido;
                                document.getElementById("sendSAF").disabled = !SAFPedido;
                        }
                    }
                )
    };

    function startExpedPedido() {
        console.log('funco send');
        $.ajax(
            {
                    type: "POST",
                    url: "/pedidos/expedicio",
                    data:$('#formExpedPedido').serialize(),
                    success: function( data ) {
                        
                        $("#prepPedido-missatge-inici").text("Expedició pedido");
                      /*   init();//mostra el cronometre
                        cronometrar();//inicia el cronometre */
                        $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                        $("#activitats").load(" #activitats");
                            /* window.setTimeout(function(){
                                window.location = "/pedidos";
                            }, 1500); */
                    /* comprovant si els botons estan disabled o no per a desabilitarlos o no */
                    var prepPedido = document.getElementById("sendPrepPedido").disabled;
                    var revPedido = document.getElementById("sendRevisarPedido").disabled;
                    var SAFPedido = document.getElementById("sendSAF").disabled;

                        document.getElementById("sendPrepPedido").disabled = !prepPedido;
                        document.getElementById("sendRevisarPedido").disabled = !revPedido;
                        document.getElementById("sendSAF").disabled = !SAFPedido;
                }
            }
        )
    };

    function startSAFPedido() {
        console.log('funco send');
        $.ajax(
            {
                    type: "POST",
                    url: "/pedidos/saf",
                    data:$('#formSAFPedido').serialize(),
                    success: function( data ) {
                        
                        $("#prepPedido-missatge-inici").text("SAF");
                      /*   init();//mostra el cronometre
                        cronometrar();//inicia el cronometre */
                        $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                        $("#activitats").load(" #activitats");
                            /* window.setTimeout(function(){
                                window.location = "/pedidos";
                            }, 1500); */
                    /* comprovant si els botons estan disabled o no per a desabilitarlos o no */
                    var prepPedido = document.getElementById("sendPrepPedido").disabled;
                    var revPedido = document.getElementById("sendRevisarPedido").disabled;
                    var expedPedido = document.getElementById("sendExpedicions").disabled;

                        document.getElementById("sendPrepPedido").disabled = !prepPedido;
                        document.getElementById("sendRevisarPedido").disabled = !revPedido;
                        document.getElementById("sendExpedicions").disabled = !expedPedido;
                }
            }
        )
    };

    
    function stopPedidos(){
        console.log('funco send');
                $.ajax(
                    {
                            type: "POST",
                            url: "/pedidos/stop",
                            data:$('#formStopPedidos').serialize(),
                            success: function( data ) {
                                
                                if (document.getElementsByTagName("td")[1].innerHTML.includes("hourglass")){
                                $("#prepPedido-missatge-inici").text("S'ha parat amb èxit.");
                              /*   init();//mostra el cronometre
                                cronometrar();//inicia el cronometre */
                                $("#alert-success")
                                .fadeTo(4000, 1000)
                                .slideUp(1000, function () {
                                    $("#alert-success").slideUp(1000);
                                });
                                $("#activitats").load(" #activitats");
                                    window.setTimeout(function(){
                                        window.location = "/pedidos";
                                    }, 1500);
                                } else {
                                    $("#alert-danger-missatge-final").text("No hi ha cap tasca per a parar.");
                                    $(".cronometro").hide();
                                    $("#alert-danger")
                                    .fadeTo(4000, 1000)
                                    .slideUp(1000, function () {
                                        $("#alert-danger").slideUp(1000);
                                    });
                                }
                            },//si no s'ha trobat cap registre, retornara error amb alert
                            error: function(xhr, textStatus, error){
                                $("#alert-danger-missatge-final").text("No hi ha cap tasca per a parar.");
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