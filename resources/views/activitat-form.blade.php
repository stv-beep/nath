@extends('layouts.app')
<!--altres-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--SCRIPT ALEIX-->   
<script>
    //https://stackoverflow.com/questions/41632942/how-to-measure-time-elapsed-on-javascript
    //https://stackoverflow.com/questions/1210701/compute-elapsed-time/1210726
    //https://ralzohairi.medium.com/displaying-dynamic-elapsed-time-in-javascript-260fa0e95049
    
    var startTime, endTime, h;

    function start() {
    //startTime = new Date();
    
    startTime = moment().format('YYYY-MM-DD[T]HH:mm:ss');
    console.log(moment().format(startTime));
    /* $("#inici-jornada").val(startTime);
    return startTime; */
    $("#inici-jornada").val(startTime);
    console.log(startTime);
    
        alert('funco send');
        console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "{{route('jornada.store')}}",
                    data:$('#form-inici').serialize(),
                    success: function( data ) {
                        console.log(data);
                        $("#input-treballador").val();
                        //$("#total_cron").val();
                        $("#inici-jornada").val();
                        alert('enviat');
                        window.location = "{{route('home')}}";
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

    /*guardo HORES al input*/
    $("#total_cron").val(h);
        let cron = document.getElementById("total_cron").value;
        if (cron.length < 0){
            console.log("cron = "+cron);
        } else {
            console.log("cronn = "+cron);
        }

    
    $("#final-jornada").val(endTime);


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
/* 
    function enviant(){
    $('#send').click( function() {
        alert('funco send');
        console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "{{route('jornada.store')}}",
                    data:$('#form-temps').serialize(),
                    success: function( data ) {
                        console.log(data);
                        $("#input-treballador").val();
                        $("#total_cron").val();
                        $("#inici-jornada").val();
                        $("#final-jornada").val();
                        //$("#test").load(location.href + " #test");
                        
                    }
                }
            )
        }
        ); 
    }
    */

</script>



@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Jornada') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    

                    <!--FORM INICI JORNADA-->
                    <form id="form-inici" action="{{route('jornada.store')}}" method="post">

                        @csrf        
                        
                        usuari<input type="text" name="treballador" id="treballador" value="{{$user->id}}">
                        <br>
                        inici<input type="text" id="inici-jornada" name="inici-jornada">
                            <br><br>
                        <button type="button" class="btn btn-outline-success" onclick="start()">Començar a comptar</button>
                      
                        <br><br>
                        
                        <!--button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button-->  
                    </form>


                    <!--FORM FI JORNADA-->
                    <form id="form-final" action="{{route('jornada.update')}}" method="post">

                        @csrf        
                        @method('PATCH')                       
                        usuari<input type="text" name="treballador" id="treballador" value="{{$user->id}}">
                        <br>
                        <div id="test">
                        hores<input type="text" id="total_cron" name="total_cron"><br>
                            </div>
                        final<input type="text" id="final-jornada" name="final-jornada">
                            <br><br>
                      
                        <button type="button" class="btn btn-xs btn-outline-danger center" onclick="end()">Parar de comptar</button>
                        <br><br>
                        
                        <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button>  
                    </form>
                     
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
