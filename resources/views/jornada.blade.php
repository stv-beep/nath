@extends('layouts.app')

@section('title', 'Activitat')
@section('content')
<script src="{{ asset('js/Jornada.js') }}" defer></script>

<div class='alert-position hidden alert alert-success' id='alert-success' role='alert'>
   <strong id="alert-message-inici"></strong>&nbsp;
   <strong id="alert-message-final"></strong>&nbsp;
   <i class="fas fa-hourglass-half fa-spin"></i>
   <div class="cronometro">
    <div id="hms"></div>
</div>
        
    </div>
    
    <div class='alert-position hidden alert alert-danger' id='alert-danger' role='alert'>
        <i class="fas fa-exclamation-triangle"></i>
        <strong id="alert-danger-message-inici"></strong>&nbsp;
        <strong id="alert-danger-message-final"></strong>&nbsp;
        &nbsp;&nbsp;   
    </div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('messages.Working day of') }} {{$user->name}} 
                    <a id="icona" href="{{route('home')}}"><i class="far fa-arrow-alt-circle-left fa-lg" style="color: #51cf66;"></i></a>
                </div>   

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
                            
                        <input class="xy" id="cj" type="hidden"> {{-- coordenades jornada --}}
                        <button id="sendInici" type="button" class="btn btn-lg btn-success center" onclick="start()"><i class="fas fa-hourglass-start"></i> {{ __('messages.Start') }}</button>
 
                        {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}  
                    </form>


                    <!--FORM FI JORNADA-->
                    <form id="form-final" class="formJornada" action="{{route('jornada.update')}}" method="post">

                        @csrf        
                        @method('PATCH')
                        <input class="xy" type="hidden"> 
                        <button id="sendFi" type="button" class="btn btn-lg btn-danger center" onclick="end()"><i class="fas fa-hourglass-end"></i> Stop</button>
                        
                        
                      {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                    </form>
                    
                    
                    </div>
                </div>
                <hr>
                <p class="h4 text-center" id="titol">{{ __('messages.Shifts') }}</p>
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                        </tr>
                        </thead>
                        @foreach ($tornTreb as $a)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($a->jornada)) }}</td>
                            @if ($a->total == null || $a->total == 0)
                            <td><div class="loadersmall"></div></td>
                            @else
                            <td>{{$a->total}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
