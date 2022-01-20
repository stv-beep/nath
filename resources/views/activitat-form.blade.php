<!DOCTYPE html>
<!--html lang=" str_replace('_', '-', app()->getLocale()) "-->

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
    <h2>Activitat del treballador</h2>
    <div class="container mt-5">

        <!-- Success message -->
        @if(Session::has('success'))
        <div class="alert alert-success">
            Session::get('success')
        </div>
        @endif

        <form action="/guardar" method="POST">

            @csrf

            <div class="form-group">
                <label>Treballador</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>

            <!--div class="form-group">
                <label>Inici Jornada</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>

            <div class="form-group">
                <label>Fi jornada</label>
                <input type="email" class="form-control" name="email" id="email">
            </div-->

            <input type="submit" name="send" value="Enviar" class="btn btn-dark btn-block">
        </form>
    </div>
</body>

</html>