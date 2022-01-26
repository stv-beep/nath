<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activitat del treballador</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">


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
                        url: "{{route('activitat-form.store')}}",
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



        

       /*  $('#form-temps').submit(function(e)){
            e.preventDefault();

            var inici = $(startTime);
            var fi = $(endTime);
            var tot = $(h);


            $.ajax({
                url: "{{route('activitat-form.store')}}",
                type: "POST",
                data:{
                    inici: inici,
                    fi: fi,
                    tot: tot
                },
                success:function(response){
                    if(response){
                        $('#form-temps')[0].reset();
                    }

                }

            });
        }); */

       

        </script>
</head>



<body>
    
    <div class="container mt-5">
    <h2>Activitat</h2>

    

        <form id="form-temps" action="{{route('activitat-form.store')}}" method="post">

            @csrf

            <div class="form-group">
                <label>Treballador</label>
                <input type="text" class="form-control" name="input-treballador" id="input-treballador">
            </div>


            <button type="button" class="btn btn-dark btn-block" onclick="start()">Començar a comptar</button>

            <button type="button" class="btn btn-light btn-block" onclick="end()">Parar de comptar</button>

            <input type="hidden" id="total_cron">
            <input type="hidden" id="inici-jornada">
            <input type="hidden" id="final-jornada">

            <!--label>Inici Jornada</label>
				<input type="datetime-local" id="inici-jornada" name="inici-jornada" class="btn btn-light btn-block" value="<?php echo date('Y-m-d\TH:i', strtotime("+ 1 hour"));?>">

			<label>Fi Jornada</label>
				<input type="datetime-local" id="final-jornada" name="final-jornada" class="btn btn-light btn-block" value="<?php echo date('Y-m-d\TH:i', strtotime("+ 1 hour"));?>"-->

            <!--input type="button" id="send" value="Enviar" class="btn btn-dark btn-block" onclick="enviant();"-->
            
        </form>
        <button type="button" id="send" value="Enviar" class="btn btn-dark btn-block" onclick="enviant();">Enviar</button>
    </div>
</body>

</html>