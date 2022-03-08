@extends('layouts.app')

@section('content')
        
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
                    {{-- <br>
                    Connected from: 
                    {{$host = gethostname()}}
                    {{$hostip = gethostbyname($host)}} --}}

                </div>
                <a href="{{ route('jornada.form') }}" class="btn btn-primary center btn-lg">
                    <i class="far fa-calendar-alt"></i>&nbsp; {{ __('messages.Working day') }} 
                    <?php                  
                        $date = date('Y-m-d H:i:s');
                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
                        echo $newDate;
                    ?>
                </a>
                <br><br>
                @if ($user->magatzem == true)
                        {{-- MENU --}}
                <div class="grid-container-home">
                    <a href="{{ route('comandes.form') }}" class="item1 btn btn-info btn-lg">{{ __('messages.Orders') }}</a>
                    <a href="" class="item2 btn btn-info btn-lg disabled">{{ __('messages.Receptions') }}</a>
                    <a href="" class="item3 btn btn-info btn-lg disabled">{{ __('messages.Reoperations') }}</a>
                    <a href="" class="item4 btn btn-info btn-lg disabled">{{ __('messages.Inventory') }}</a>
                </div>                        
                    @endif
                

                {{-- TAULES --}}
                <p class="h4 text-center" id="titol">{{ __('messages.Shifts') }}</p>
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                        </tr>
                        </thead>
                        @foreach ($activitat as $a)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($a->jornada)) }}</td>
                            @if ($a->total == null || $a->total == 0)
                            <td><div class="loadersmall"></div>{{-- &nbsp;&nbsp;<i class="fas fa-solid fa-circle-notch fa-spin"></i> --}}</td>
                            @else
                            <td>{{$a->total}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                    
                    <p class="h4 text-center" id="titol">{{ __('messages.Working days') }}</p>
                    <table id="jornades" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                        </tr>
                        </thead>
                        @foreach ($dia as $d)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($d->dia)) }}</td>
                            @if ($d->total == null || $d->total == 0)
                            <td><div class="loadersmall"></div></td>
                            @else
                            <td>{{$d->total}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </table>

                    @if ($user->magatzem == true)
                    {{-- tasques --}}
                    <p class="h4 text-center" id="titol">{{ __('messages.Tasks') }}</p>
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{ __('messages.Task') }}</th>
                            <th scope="col">Total (min)</th>
                            <th scope="col">{{ __('messages.Task start') }}</th>
                            <th scope="col">{{ __('messages.Task end') }}</th>

                        </tr>
                        </thead>
                        {{-- inner join solucionat --}}
                        @foreach ($tasques as $t)
                        <tr>
                            @php($tasca = $t->tasca)
                                <td>{{ __("messages.$tasca") }}</td>
                                    @if ($t->total == null || $t->total == 0)
                                    <td><div class="loadersmall"></div></td>
                                    @else
                                    <td><b>{{$t->total}}</b></td>
                                    @endif
                            <td>{{ date('d/m/Y H:i', strtotime($t->iniciTasca)) }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($t->fiTasca)) }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection
