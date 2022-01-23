<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activitat del treballador</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href=" asset('css/style.css') ">
</head>

<body>
    
    <div class="container mt-5">
    <h2>Activitat del treballador</h2>

        <form action="{{route('activitat-form.store')}}" method="post">

            @csrf

            <div class="form-group">
                <label>Treballador</label>
                <input type="text" class="form-control" name="input-treballador" id="input-treballador">
            </div>

            <label>Inici Jornada</label>
				<input type="datetime-local" id="inici-jornada" name="inici-jornada" class="btn btn-light btn-block"/>

			<label>Fi Jornada</label>
				<input type="datetime-local" id="final-jornada" name="final-jornada" class="btn btn-light btn-block"/>

            <!--button id="inici-jornada" class="btn btn-light btn-block">Inici Jornada</button>
            <button id="final-jornada" class="btn btn-light btn-block">Fi Jornada</button-->

            <input type="submit" name="send" value="Enviar" class="btn btn-dark btn-block">
        </form>
    </div>
</body>

</html>