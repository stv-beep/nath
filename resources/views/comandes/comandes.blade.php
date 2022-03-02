@extends('layouts.app')
<!--altres-->

@section('title', 'Comandes')

@section('content')
<script src="{{ asset('js/Comandes.js') }}" defer></script>

<div class='alert-position hidden alert alert-success' id='alert-success' role='alert'>
   <strong id="prepPedido-missatge-inici"></strong>&nbsp;
   <strong id="alert-missatge-final"></strong>&nbsp;
   <i class="fas fa-hourglass-half fa-spin"></i>
   <div class="cronometro">
    <div id="hms"></div>
</div>
        
    </div>
    
    <div class='alert-position hidden alert alert-danger' id='alert-danger' role='alert'>
        <i class="fas fa-exclamation-triangle"></i>
        <strong id="alert-danger-missatge-inici"></strong>&nbsp;
        <strong id="alert-danger-missatge-final"></strong>&nbsp;   
    </div>

    {{-- condicio per a mostrar tasques si ets de magatzem o no --}}
    @if ($user->magatzem == false)
    <div class='alert-position alert alert-danger' role='alert'>
        <i class="fas fa-exclamation-triangle"></i>
        <strong>No tens tasques de magatzem.</strong>&nbsp;
        <a id="icona" href="{{route('home')}}"><i class="fas fa-arrow-alt-circle-left fa-lg" style="color: #51cf66;"></i></a>   
    </div>
    @else
    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Comandes') }} de {{$user->name}}
                    <a id="icona" href="{{route('home')}}"><i class="fas fa-arrow-alt-circle-left fa-lg" style="color: #51cf66;"></i></a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                 
                    <div class="grid-container">
                        
                            <div class="item1">
                                <form id="formPrepPedido" action="{{route('pedidos.store')}}" method="post">
    
                                @csrf                                
                                <button id="Pedido1" type="button" class="opcio btn btn-lg btn-success ped" onclick="startPrepPedido();">Preparació</button>
                                
                                {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button>  --}} 
                                </form>
                            </div>
                            <div class="item2">
                                <form id="formRevPedido" action="{{route('revPedidos.store')}}" method="post">
    
                                    @csrf        
                                    <button id="Pedido2" type="button" class="opcio btn btn-lg btn-success ped" onclick="startRevPedido();">Revisió</button>
                                    
                                    
                                  {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>
                            <div class="item3">
                                <form id="formExpedPedido" action="{{route('expedPedidos.store')}}" method="post">
                                    @csrf        
                                    <button id="Pedido3" type="button" class="opcio btn btn-lg btn-success ped" onclick="startExpedPedido();">Expedicions</button>
                                  {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>  
                            <div class="item4">
                                <form id="formSAFPedido" action="{{route('safPedidos.store')}}" method="post">
                                    @csrf
                                    <button id="Pedido4" type="button" class="opcio btn btn-lg btn-success ped" onclick="startSAFPedido();">SAF</button>
                                    {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>
                            
                            {{-- <div class="item5">
                                <form id="formStopPedidos" action="{{route('stop.pedidos')}}" method="post">
    
                                    @csrf                          
                                    <button id="sendStopPedidos" type="button" class="opcio btn btn-lg btn-danger" onclick="stopPedidos();">Stop</button>
                                </form>
                            </div> --}}
                          </div>
                          
                          <div>
                            
                        </div>
                    </div>
                    
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Tasca</th>
                            <th scope="col">Total (min)</th>
                            <th scope="col">Inici tasca</th>
                            <th scope="col">Fi tasca</th>

                        </tr>
                        </thead>
                            {{-- inner join solucionat --}}
                            @foreach ($tasques as $t)
                            <tr>
                                <td>{{$t->tasca}}</td>
                                    @if ($t->total == null || $t->total == 0)
                                    <td>&nbsp;&nbsp;<i class="fas fa-solid fa-circle-notch fa-spin"></i></td>
                                    @else
                                    <td><b>{{$t->total}}</b></td>
                                    @endif
                                    <td>{{ date('d/m/Y H:i:s', strtotime($t->iniciTasca)) }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($t->fiTasca)) }}</td>
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
@endif {{-- if magatzem --}}
@endsection
