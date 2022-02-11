@extends('layouts.app')
<!--altres-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@section('title', 'Pedidos')
@section('content')

<div class='alert-position hidden alert alert-success' id='alert-success' role='alert'>
   <strong id="prepPedido-missatge-inici"></strong>&nbsp;
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
                <div class="card-header">{{ __('Pedidos') }} {{$user->id}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                 
                    <div class="forms">
                        <form id="formPrepPedido" class="formJornada" action="{{route('pedidos.store')}}" method="post">
    
                            @csrf        
                            
                           {{--  <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                            
                           {{-- <input type="hidden" id="inici-jornada" name="inici-jornada"> --}}
                                
                            <button id="sendPrepPedido" type="button" class="btn btn-xs btn-outline-success center" onclick="startPrepPedido();">Preparació</button>
                            
                            {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}  
                        </form>
    
    
                        
                        <form id="form-final" class="formJornada" action="" method="post">
    
                            @csrf        
                            @method('PATCH')                       
                            {{-- <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                            
      
                            
                              
                            {{-- <input type="hidden" id="final-jornada" name="final-jornada"> --}}
                                
                          
                            <button id="sendRevisarPedido" type="button" class="btn btn-xs btn-outline-danger center" onclick="">Revisió</button>
                            
                            
                          {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                        </form>

                        <form id="form-final" class="formJornada" action="" method="post">
    
                            @csrf        
                            @method('PATCH')                       
                            {{-- <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                            
      
                            
                              
                            {{-- <input type="hidden" id="final-jornada" name="final-jornada"> --}}
                                
                          
                            <button id="sendExpedicions" type="button" class="btn btn-xs btn-outline-danger center" onclick="end()">Expedicions</button>
                            
                            
                          {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                        </form>
                        <form id="form-final" class="formJornada" action="" method="post">
    
                            @csrf        
                            @method('PATCH')                       
                            {{-- <input type="hidden" name="treballador" id="treballador" value="{{$user->id}}"> --}}
                            
      
                            
                              
                            {{-- <input type="hidden" id="final-jornada" name="final-jornada"> --}}
                                
                          
                            <button id="sendSAF" type="button" class="btn btn-xs btn-outline-danger center" onclick="end()">SAF</button>
                            
                            
                          {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                        </form>
                        
                        
                        
                        </div>
                    </div>
                    
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Tasca</th>
                            <th scope="col">Dia</th>
                            <th scope="col">Total</th>
                            <th scope="col">Inici tasca</th>
                            <th scope="col">Fi tasca</th>

                        </tr>
                        </thead>
                        @foreach ($pedidos as $t)
                        <tr>
                            <td>{{$t->tasca}}</td>
                            <td>{{$t->dia}}</td>
                            <td>{{$t->total}}</td>
                            <td>{{$t->iniciTasca}}</td>
                            <td>{{$t->fiTasca}}</td>

                        </tr>
                        @endforeach
                    </table>
                </div> 
                </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
