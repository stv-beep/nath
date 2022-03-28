/* comprovacio de torns per a desabilitar botons */
window.onload = onLoad;
    
function onLoad(){
    checkLastTorn();
    getLocation();   
}

let msgIniciJornada, msgFinalJornada, tascaNoAcabada, msgError;

    /* console.log('appCodeName: '+navigator.appCodeName)
    console.log('appVersion: '+navigator.appVersion)
    console.log('platform: '+navigator.platform)
    console.log('userAgent: '+navigator.userAgent) */

/* funcions de jornada */
    function start() {
        translateAlerts();
        var cj = document.getElementById('cj').value;
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        console.log('sending...');
        $.ajax(
                {
                    type: "POST",
                    url: "/jornada",//"{{route('jornada.store')}}"
                    data:{
                        x : cj,
                        info: navigator.platform+', '+navigator.userAgent                    
                    },
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
                    type: "PATCH",
                    url: "/jornada",//"{{route('jornada.store')}}"
                    data:$('#form-final').serialize(),
                    success: function( response ) {
                        //console.log(response[0])
                        //console.log(response[1][0].tasca)
                        if (response[0] == false) {//unfinished task
                            $("#alert-danger-message-final").text(tascaNoAcabada+' = '+response[1][0].tasca);
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



/* check last workshift */

const inic = document.getElementById("sendInici");
const f = document.getElementById("sendFi");
inic.disabled = true;
f.disabled = true;

function checkLastTorn(){
  translateAlerts();
    $.ajax(
        {
            type: "GET",
            url: "/jornada/check",
            success: function(response){
              //true = torn NO acabat
              //false = torn acabat
              if (response == true) {
                f.disabled = !response;
                document.getElementById("sendInici").classList.toggle('btn-outline-success');
              } else {
                inic.disabled = response;
                document.getElementById("sendFi").classList.toggle('btn-outline-danger');
              }

            },
            error: function(xhr, textStatus, error){
              $("#alert-danger-message-final").text(msgError);
              $("#alert-danger")
              .fadeTo(4000, 1000)
              .slideUp(1000, function () {
                  $("#alert-danger").slideUp(2500);
              });
          }
        });
                
}







/* function checkLastTorn(){
    if (document.getElementsByTagName('td')[1] == undefined){
        inic.disabled = !inic;
    } else if (document.getElementsByTagName('td')[1].innerHTML == ""){//si no s'ha acabat el torn (i no hi ha total)
        f.disabled = !f;
    } else if (document.getElementsByTagName('td')[1].innerText.length > 0) {
        inic.disabled = !inic;
    }
} */