<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activitat del treballador</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href=" asset('css/style.css') ">


<!--altres-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body>

<!--boto de prova-->
<form action="" method="POST">
        <!--  button - Start Timer -->
    	<timer>
    	    <input id="starttime_button" name = "starttime_button" type="button" value="Hora" onclick="setStartTime()" />
    	    <input type="text" id="starttime" name="starttime" value="" />
    	</timer>
    </form>

    <script>
        function setStartTime(){
            document.getElementById('starttime').value = new Date().toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit', second:'2-digit'});
        }
    </script>

    <!--
        ^^
        ||
        ||
    boto de prova-->
    
    <div class="container mt-5">
    <h2>Activitat del treballador</h2>

        <form action="{{route('activitat-form.store')}}" method="post">

            @csrf

            <div class="form-group">
                <label>Treballador</label>
                <input type="text" class="form-control" name="input-treballador" id="input-treballador">
            </div>

            <!--button type="button" id="inici-jornada" class="btn btn-light btn-block" value="<?php echo date('Y-m-d\TH:i', strtotime("+ 1 hour"));?>">Inici Jornada</button>
            <button type="button" id="final-jornada" class="btn btn-light btn-block" value="<?php echo date('Y-m-d\TH:i', strtotime("+ 1 hour"));?>">Fi Jornada</button-->

            <label>Inici Jornada</label>
				<input type="datetime-local" id="inici-jornada" name="inici-jornada" class="btn btn-light btn-block" value="<?php echo date('Y-m-d\TH:i', strtotime("+ 1 hour"));?>">

			<label>Fi Jornada</label>
				<input type="datetime-local" id="final-jornada" name="final-jornada" class="btn btn-light btn-block" value="<?php echo date('Y-m-d\TH:i', strtotime("+ 1 hour"));?>">

            <input type="submit" name="send" value="Enviar" class="btn btn-dark btn-block">
        </form>
    </div>
</body>

</html>