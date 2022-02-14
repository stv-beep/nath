@extends('layouts.app')

@section('title', 'Activitat')
@section('content')

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
                        
                       {{-- <input type="hidden" id="inici-jornada" name="inici-jornada"> --}}
                            
                        <button id="sendInici" type="button" class="btn btn-xs btn-outline-success center" onclick="start()"><i class="fas fa-hourglass-start"></i> Començar</button>
                      
                        
                        
                        <!--button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button-->  
                    </form>


                    <!--FORM FI JORNADA-->
                    <form id="form-final" class="formJornada" action="{{route('jornada.update')}}" method="post">

                        @csrf        
                        @method('PATCH')                       
                        {{-- <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                        
  
                        <input type="hidden" id="total_cron" name="total_cron"> 
                          
                        {{-- <input type="hidden" id="final-jornada" name="final-jornada"> --}}
                            
                      
                        <button id="sendFi" type="button" class="btn btn-xs btn-outline-danger center" onclick="end()"><i class="fas fa-hourglass-end"></i> Acabar</button>
                        
                        
                      {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                    </form>
                    <a href="{{route('home')}}"><i class="fas fa-history fa-lg formJornada" style="color: #51cf66;"></i></a>
                    
                    
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
