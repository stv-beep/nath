<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inici de sessió</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href=" asset('css/style.css') ">
</head>

<body>
    <h2>Inici de sessió</h2>
    <div class="container mt-5">

        <form action="" method="post">

            @csrf

            <div class="form-group">
                <label>Codi treballador</label>
                <input type="text" class="form-control" name="codi-treballador" id="codi-treballador">
            </div>
            <input type="submit" name="send" value="Inicia" class="btn btn-dark btn-block">
        </form>
    </div>
</body>

</html>