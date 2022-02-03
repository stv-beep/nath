@extends('layouts.app')
<!--altres-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@section('content')

<div class='alert-position hidden alert alert-success' id='alert-inici' role='alert'>
    S'ha <strong>començat</strong> a comptar satifactòriament&nbsp;
    <i class="fas fa-hourglass-half fa-spin"></i>
    </div>

    <div class='alert-position hidden alert alert-success' id='alert-final' role='alert'>
    S'ha <strong>parat</strong> de comptar satifactòriament&nbsp;
    <i class="fas fa-hourglass-half fa-spin"></i>
    </div>
   {{--  <div class='alert-position hidden alert alert-danger' id='alert-danger' role='alert'>
    <strong id='alert-missatge'>Ha hagut un error.</strong> No s'ha començat a comptar satifactòriament. Torna-ho a intentar.
    </div> --}}

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
                        
                        <a href="{{route('home')}}"><i class="fas fa-history fa-lg formJornada" style="color: #51cf66;"></i></a>
                      {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                    </form>

                    
                    
                    </div>
                    
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
