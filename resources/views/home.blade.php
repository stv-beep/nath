@extends('layouts.app')

@section('content')
<script src="{{ asset('js/Main.js') }}" defer></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Dashboard') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('messages.Welcome') }} {{$user->name}} 
                    @if($user->administrador == true)
                    | Administrador
                    @endif
                    {{-- <br>
                    Connected from: 
                    {{$host = gethostname()}}
                    {{$hostip = gethostbyname($host)}} --}}
                    

                    {{-- proves --}}
                    {{--  {{$_SERVER['REMOTE_HOST']}} --}}{{-- hostname --}}
                    {{-- {{$_SERVER['REMOTE_ADDR']}} --}}{{-- ip? --}}
                    
                    {{-- {{gethostbyaddr($_SERVER['REMOTE_ADDR'])}} --}}

                    {{-- {{$clientIP = $_SERVER['HTTP_CLIENT_IP'] 
                    ?? $_SERVER["HTTP_CF_CONNECTING_IP"] # when behind cloudflare
                    ?? $_SERVER['HTTP_X_FORWARDED'] 
                    ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
                    ?? $_SERVER['HTTP_FORWARDED'] 
                    ?? $_SERVER['HTTP_FORWARDED_FOR'] 
                    ?? $_SERVER['REMOTE_ADDR'] 
                    }} --}}


                </div>
                <a href="{{ route('jornada.form') }}" class="btn btn-primary center btn-lg">
                    <i class="far fa-calendar-alt"></i>&nbsp; {{ __('messages.Working day') }} 
                    <?php                  
                        $date = date('Y-m-d H:i:s');
                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
                        echo $newDate;
                    ?>
                </a>
                <br><hr><br>
                @if ($user->magatzem == true)
                        {{-- MENU --}}
                <div class="grid-container-home">
                    <a href="{{ route('comandes.form') }}" class="item1 btn btn-info btn-lg">{{ __('messages.Orders') }}</a>
                    <a href="{{ route('recepcions.form') }}" class="item2 btn btn-info btn-lg disabled">{{ __('messages.Receptions') }}</a>
                    <a href="{{ route('reoperacions.form') }}" class="item3 btn btn-info btn-lg disabled">{{ __('messages.Reoperations') }}</a>
                    <a href="" class="item4 btn btn-info btn-lg disabled">{{ __('messages.Inventory') }}</a>
                </div><hr>                     
                    @endif
                
                {{-- TAULES --}}
                <p class="h4 text-center" id="titol">{{ __('messages.Shifts') }}</p>
                        @if ($activitat == '[]'){{-- cap tasca --}}
                            <p class="text-center">{{ __('messages.You have no shifts') }}</p>
                        @else
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr class="text-center">
                            <th scope="col">{{ __('messages.Shift start') }}</th>
                            <th scope="col">{{ __('messages.Shift end') }}</th>
                            <th scope="col">Total (h)</th>
                        </tr>
                        </thead>
                        @foreach ($activitat as $a)
                        <tr class="text-center">
                            <td>{{ date('d/m/Y H:i', strtotime($a->iniciTorn)) }}</td>
                            @if ($a->fiTorn === null)
                            <td><i class="fas fa-hourglass-half fa-spin"></i></td>
                            @else
                            <td>{{ date('d/m/Y H:i', strtotime($a->fiTorn)) }}</td>
                            @endif
                            @if ($a->total === null){{--  || $a->total == 0 --}}
                            <td><i class="fas fa-hourglass-half fa-spin"></i></td>
                            {{-- &nbsp;&nbsp;<div class="loadersmall"></div> --}}{{-- &nbsp;&nbsp;<i class="fas fa-solid fa-circle-notch fa-spin"></i> --}}
                            @else
                            <td>{{$a->total}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </table>@endif {{-- cap turno --}}
                    
                    <p class="h4 text-center" id="titol">{{ __('messages.Working days') }}</p>
                        @if ($dia == '[]'){{-- cap tasca --}}
                            <p class="text-center">{{ __('messages.You have no work day') }}</p>
                        @else
                    <table id="jornades" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr class="text-center">
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (h)</th>
                        </tr>
                        </thead>
                        @foreach ($dia as $d)
                        <tr class="text-center">
                            <td>{{ date('d/m/Y', strtotime($d->dia)) }}</td>
                            @if ($d->total === null){{--  || $d->total == 0 --}}
                            <td><i class="fas fa-hourglass-half fa-spin"></i>{{-- <div class="loadersmall"></div> --}}</td>
                            @else
                            <td>{{$d->total}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </table>@endif{{-- cap jornada --}}

                    @if ($user->magatzem == true)
                    {{-- tasques --}}
                    <p class="h4 text-center" id="titol">{{ __('messages.Tasks') }}</p>
                        @if ($tasques == '[]'){{-- cap tasca --}}
                            <p class="text-center">{{ __('messages.You have no tasks') }}</p>
                        @else
                    <table id="activitats2" class="table table-striped table-hover">
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
                                    <td><i class="fas fa-hourglass-half fa-spin"></i>{{-- <div class="loadersmall"></div> --}}</td>
                                    @else
                                    <td><b>{{$t->total}}</b></td>
                                    @endif
                            <td>{{ date('d/m/Y H:i', strtotime($t->iniciTasca)) }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($t->fiTasca)) }}</td>
                        </tr>
                        @endforeach
                    </table>@endif
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection
