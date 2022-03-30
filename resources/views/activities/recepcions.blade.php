@extends('layouts.app')
<!--altres-->

@section('title', 'Comandes')

@section('content')
<script src="{{ asset('js/Recepcions.js') }}" defer></script>

<div class='alert-position hidden alert alert-success' id='alert-success' role='alert'>
   <strong id="task-message-inici"></strong>&nbsp;
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
    </div>

    {{-- condicio per a mostrar tasques si ets de magatzem o no --}}
    @if ($user->magatzem == false)
    <div class='alert-position alert alert-warning' role='alert'>
        <i class="fas fa-exclamation-triangle"></i>
        <strong>{{ __('messages.You don\'t have any stock task.') }}</strong>&nbsp;
        <a id="icona" href="{{route('home')}}"><i class="fas fa-arrow-alt-circle-left fa-lg" style="color: #51cf66;"></i></a>   
    </div>
    @else
    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">{{-- {{ __('messages.Orders from') }} --}}Recepcions de {{$user->name}}
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
                                <form id="formRecp5" action="{{route('recepcions.store')}}" method="post">
    
                                @csrf             
                                <input class="xy" id="r1" type="hidden">                   
                                <button id="Recp5" type="button" class="opcio btn btn-lg btn-success ped" onclick="startRecp1();">{{ __('messages.Reception1') }}</button>
                                
                                {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button>  --}} 
                                </form>
                            </div>
                            <div class="item2">
                                <form id="formRecp6" action="{{route('recepcions2.store')}}" method="post">
    
                                    @csrf
                                    <input class="xy" id="r2" type="hidden">        
                                    <button id="Recp6" type="button" class="opcio btn btn-lg btn-success ped" onclick="startRecp2();">{{ __('messages.Reception2') }}</button>
                                    
                                    
                                  {{-- <button type="submit" id="send" value="Enviar" class="btn btn-success btn-block">Enviar</button> --}}
                                </form>
                            </div>
                            
                          
                         
                            
                        
                    </div>
                    <hr>
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr class="text-center">
                            <th scope="col">{{ __('messages.Task') }}</th>
                            <th scope="col">Total (min)</th>
                            <th scope="col">{{ __('messages.Task start') }}</th>
                            <th scope="col">{{ __('messages.Task end') }}</th>

                        </tr>
                        </thead>
                            {{-- inner join solucionat --}}
                            @foreach ($tasques as $t)
                            <tr class="text-center">
                                @php($tasca = $t->tasca)
                                <td>{{ __("messages.$tasca") }}</td>
                                    @if ($t->total === null || $t->total == 0.00)
                                    <td>{{-- &nbsp;&nbsp; --}}<i class="fas fa-hourglass-half fa-spin"></i></td>
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
