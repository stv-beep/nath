<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Inici de sessió</title>
        <meta name="description" content="formulari">
        <meta name="author" content="Aleix A">
        <!--link rel="stylesheet" href="/css/app.css"-->
        <link rel="stylesheet" type="text/css" href=" asset('css/style.css') ">
    </head>
<body>
<div class="container">
<div class="col-sm-8">

<h2>Identificació del treballador</h2>

<form action="/guardar" method="POST">

  <div class="form-group row">
    <label for="inputID" class="col-sm-2 col-form-label">Identificació</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputID" placeholder="Codi">
    </div>
  </div>
 
  <!--div class="form-group row">
    <label for="inputmodelo" class="col-sm-2 col-form-label">Modelo</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputmodelo" placeholder="Modelo">
    </div>
  </div>

  <div class="form-group row">
    <label for="inputPuertas" class="col-sm-2 col-form-label">Puertas</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputPuertas" placeholder="Puertas">
    </div>
  </div>

  <div class="form-group row">
    <label for="inputLuces" class="col-sm-2 col-form-label">Luces</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputLuces" placeholder="Luces">
    </div>
  </div>
  
  
  <div class="form-group row">
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" name="direccion_asistida" type="checkbox" id="lbl_da">
        <label class="col-sm-2 form-check-label" for="lbl_da">
          direccion asistida
        </label>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" name="abs" type="checkbox" id="lbl_abs">
        <label class="col-sm-2 form-check-label" for="lbl_abs">
          ABS
        </label>
      </div>
    </div>
  </div-->

  <div class="form-group row">
    <div class="col-sm-10">
      <button type="submit" class="btn btn-primary">Inicia</button>
    </div>
  </div>
</form>


</div>
</div>
<script src="./resources/js/app.js"></script>
</body>
</html>