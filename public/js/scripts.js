/* SCRIPT ALEIX  */  

    //https://stackoverflow.com/questions/41632942/how-to-measure-time-elapsed-on-javascript
    //https://stackoverflow.com/questions/1210701/compute-elapsed-time/1210726
    //https://ralzohairi.medium.com/displaying-dynamic-elapsed-time-in-javascript-260fa0e95049
    
    var startTime, endTime, h;

    function start() {
    //startTime = new Date();
    
    startTime = moment().format('YYYY-MM-DD[T]HH:mm:ss');
    console.log(moment().format(startTime));

    $("#inici-jornada").val(startTime);
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
                        $("#inici-jornada").val();
                        //alert('enviat');
                        /* $(".altert-success").fadeIn(1000).delay(2000);
 
                        $(".altert-success").fadeOut(1000); */
 
                        window.location = "/home";//"{{route('home')}}"
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
                    url: "/home",//"{{route('jornada.store')}}"
                    data:$('#form-final').serialize(),
                    success: function( data ) {
                        //console.log(data);
                        //$("#total_cron").val();
                        $("#final-jornada").val();
                        window.location = "/home";//"{{route('home')}}"
                    }
                }
        )

    return endTime;
    }

/* 
    $(document).ready(function(){
	$("#total_cron").keyup(function(){
		var cron = $(this).val();
		$("#test").html(cron);
	    })
    });
 */