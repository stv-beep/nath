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
    startTime = new Date();
    return startTime;
    };

    function end() {
    endTime = new Date();
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

    return endTime;
    }

    function enviant(){
    $('#send').click( function() {
        alert('funco send');
        console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "{{route('home.store')}}",
                    data:$('#form-temps').serialize(),
                    success: function( data ) {
                        console.log(data);
                        $("#input-treballador").val();
                        $("#total_cron").val(h);
                        /* $("#inici-jornada").val(startTime);
                        $("#final-jornada").val(endTime); */
                        
                    }
                }
            )
        }
        );
    }

</script>



@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Benvingut {{$user->name}}!
                    <br>
                    ID: {{$user->id}}
                </div>
                <a href="{{ url('/home/activitat') }}" class="btn btn-xs btn-info pull-right">Validar activitat a dia 
                    <?php                  
                        $date = date('Y-m-d H:i:s');
                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
                        echo $newDate;
                    ?>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
