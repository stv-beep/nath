@extends('layouts.app')

@section('title', 'Activitat')
@section('content')
<script src="{{ asset('js/Jornades.js') }}" defer></script>

<div class='alert-position hidden alert alert-success' id='alert-success' role='alert'>
   <strong id="alert-missatge-inici"></strong>&nbsp;
   <strong id="alert-missatge-final"></strong>&nbsp;
   <i class="fas fa-hourglass-half fa-spin"></i>
   <div class="cronometro">
    <div id="hms"></div>
</div>
        
    </div>
    
    <div class='alert-position hidden alert alert-danger' id='alert-danger' role='alert'>
        <strong id="alert-danger-missatge-inici"></strong>&nbsp;
        <strong id="alert-danger-missatge-final"></strong>&nbsp;
        <i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;   
    </div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Jornada') }} de {{$user->name}}</div>

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
                            
                        <button id="sendInici" type="button" class="btn btn-lg btn-outline-success center" onclick="start()"><i class="fas fa-hourglass-start"></i> Iniciar</button>
 
                        <!--button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button-->  
                    </form>


                    <!--FORM FI JORNADA-->
                    <form id="form-final" class="formJornada" action="{{route('jornada.update')}}" method="post">

                        @csrf        
                        @method('PATCH')
                      
                        <button id="sendFi" type="button" class="btn btn-lg btn-outline-danger center" onclick="end()"><i class="fas fa-hourglass-end"></i> Stop</button>
                        
                        
                      {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                    </form>
                    <a href="{{route('home')}}"><i class="fas fa-history fa-lg formJornada" style="color: #51cf66;"></i></a>
                    
                    
                    </div>
                </div>
                <p class="h4 text-center" id="titol">Torns</p>
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Dia</th>
                            <th scope="col">Hores</th>
                        </tr>
                        </thead>
                        @foreach ($tornTreb as $a)
                        <tr>
                            <td>{{$a->jornada}}</td>
                            <td>{{$a->total}}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
