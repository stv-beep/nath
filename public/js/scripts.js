/* SCRIPT ALEIX  */  

    //https://stackoverflow.com/questions/41632942/how-to-measure-time-elapsed-on-javascript
    //https://stackoverflow.com/questions/1210701/compute-elapsed-time/1210726
    //https://ralzohairi.medium.com/displaying-dynamic-elapsed-time-in-javascript-260fa0e95049

    //https://gomakethings.com/how-to-show-and-hide-elements-with-vanilla-javascript/
     
    $(document).ready(function () {

        $("#alert-success").removeClass("hidden");
        $("#alert-danger").removeClass("hidden");

        $("#alert-success").hide();
        $("#alert-danger").hide();
    });


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
                        
                        //console.log(data);
                        //$("#total_cron").val();
                        //$("#inici-jornada").val(startTime);
                        //alert('enviat');

                        //window.location = "/home";//"{{route('home')}}"
                        window.setTimeout(function(){
                            window.location = "/home";
                        }, 2000);
                        $("#alert-missatge-inici").text("Jornada iniciada amb èxit");
                        $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });

                    },
                    error: function(xhr, textStatus, error){
                        $("#alert-danger-missatge-inici").text("Ha hagut un error. Per favor, torna-ho a intentar.");
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
    endTime = moment().format('YYYY-MM-DD[T]HH:mm:ss');
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
                        $("#alert-success")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success").slideUp(1000);
                        });
                            window.setTimeout(function(){
                                window.location = "/home";//"{{route('home')}}"
                            }, 2000);
                        
                    },//si no s'ha trobat cap registre amb inici de jornada, retornara error amb alert
                    error: function(xhr, textStatus, error){
                        $("#alert-danger-missatge-final").text("No s'ha trobat cap inici de jornada coincident amb tu.");
                        $("#alert-danger")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-danger").slideUp(1000);
                        });
                    }
                    
                }
                
        )        
       
    }

/* 
    $(document).ready(function(){
	$("#total_cron").keyup(function(){
		var cron = $(this).val();
		$("#test").html(cron);
	    })
    });
 */
