@extends('layouts.app')
<!--altres-->

@section('title', 'Pedidos')

@section('content')
<script src="{{ asset('js/Pedidos.js') }}" defer></script>

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
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Pedidos') }} de {{$user->name}}</div>

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
                                <button id="sendPrepPedido" type="button" class="opcio btn btn-lg btn-success ped" onclick="startPrepPedido();">Preparació</button>
                                
                                {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button>  --}} 
                                </form>
                            </div>
                            <div class="item2">
                                <form id="formRevPedido" action="{{route('revPedidos.store')}}" method="post">
    
                                    @csrf        
                                    <button id="sendRevisarPedido" type="button" class="opcio btn btn-lg btn-success ped" onclick="startRevPedido();">Revisió</button>
                                    
                                    
                                  {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>
                            <div class="item3">
                                <form id="formExpedPedido" action="{{route('expedPedidos.store')}}" method="post">
                                    @csrf        
                                    <button id="sendExpedicions" type="button" class="opcio btn btn-lg btn-success ped" onclick="startExpedPedido();">Expedicions</button>
                                  {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>  
                            <div class="item4">
                                <form id="formSAFPedido" action="{{route('safPedidos.store')}}" method="post">
                                    @csrf
                                    <button id="sendSAF" type="button" class="opcio btn btn-lg btn-success ped" onclick="startSAFPedido();">SAF</button>
                                    {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>
                            <div class="item5">
                                <form id="formStopPedidos" action="{{route('stop.pedidos')}}" method="post">
    
                                    @csrf                          
                                    <button id="sendStopPedidos" type="button" class="opcio btn btn-lg btn-danger" onclick="stopPedidos();">Stop</button>
                                  {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>
                          </div>
                          <div>
                        
                        </div>
                    </div>
                    
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Tasca</th>
                            <th scope="col">Total</th>
                            <th scope="col">Inici tasca</th>
                            <th scope="col">Fi tasca</th>

                        </tr>
                        </thead>
                        {{-- degut a certs problemes amb els inner joins utilitzats per a aconseguir el nom de les tasques
                            m'he vist obligat a mostrar el nom per mitja de condicions --}}
                        @foreach ($pedidos as $t)
                        <tr id="{{$t->id}}">
                            @if ($t->tasca == 1 )
                                <td>{{$tasques[0]->tasca}}</td>
                             @elseif ($t->tasca == 2)
                                <td>{{$tasques[1]->tasca}}</td>
                             @elseif ($t->tasca == 3)
                                <td>{{$tasques[2]->tasca}}</td>
                             @elseif ($t->tasca == 4)
                                <td>{{$tasques[3]->tasca}}</td>
                            @endif                          
                            
                            @if ($t->total == null || $t->total == 0)
                                <td><i class="fas fa-hourglass-half fa-spin"></i></td>
                            @else
                                <td><b>{{$t->total}}</b></td>
                            @endif
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
