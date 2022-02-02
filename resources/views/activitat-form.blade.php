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

    $("#inici-jornada").val(startTime);
    console.log(startTime);
        
        //alert('funco send');
        console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "{{route('jornada.store')}}",
                    data:$('#form-inici').serialize(),
                    success: function( data ) {
                        //console.log(data);
                        //$("#total_cron").val();
                        $("#inici-jornada").val();
                        //alert('enviat');
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
    
    console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "{{route('jornada.store')}}",
                    data:$('#form-final').serialize(),
                    success: function( data ) {
                        //console.log(data);
                        //$("#total_cron").val();
                        $("#final-jornada").val();
                        window.location = "{{route('home')}}";
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
                <div class="forms">
                    <form id="form-inici" class="formJornada" action="{{route('jornada.store')}}" method="post">

                        @csrf        
                        
                       {{--  <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                        
                        <input type="hidden" id="inici-jornada" name="inici-jornada">
                            
                        <button id="sendInici" type="button" class="btn btn-xs btn-outline-success center" onclick="start()"><i class="fas fa-hourglass-start"></i> Començar</button>
                      
                        
                        
                        <!--button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button-->  
                    </form>


                    <!--FORM FI JORNADA-->
                    <form id="form-final" class="formJornada" action="{{route('jornada.update')}}" method="post">

                        @csrf        
                        @method('PATCH')                       
                        {{-- <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                        
  
                        <input type="hidden" id="total_cron" name="total_cron"> 
                          
                        <input type="hidden" id="final-jornada" name="final-jornada">
                            
                      
                        <button id="sendFi" type="button" class="btn btn-xs btn-outline-danger center" onclick="end()"><i class="fas fa-hourglass-end"></i> Parar</button>
                        
                        <a href="{{route('home')}}"><i class="fas fa-history fa-lg formJornada" style="color: #51cf66;"></i></a>
                      {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                    </form>

                    
                    
                    </div>
                    
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
