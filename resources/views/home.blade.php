@extends('layouts.app')

@section('content')
        
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Benvingut {{$user->name}}
                   {{-- <br>
                    ID: {{$user->id}}
                    <br>
                    Codi/nom d'usuari: {{$user->username}} --}}
                </div>
                <a href="{{ route('jornada.form') }}" class="h-75 btn btn-primary center">
                    <i class="far fa-calendar-check"></i> Jornada del dia 
                    <?php                  
                        $date = date('Y-m-d H:i:s');
                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
                        echo $newDate;
                    ?>
                </a>
                <br><br>
                {{-- MENU --}}
                <div class="grid-container-home">
                    <a href="{{ route('pedidos.form') }}" class="item1 btn btn-dark btn-lg" >Pedidos</a>
                    <a href="" class="item2 btn btn-dark btn-lg" >Recepcions</a>
                    <a href="" class="item3 btn btn-dark btn-lg" >Reoperacions</a>
                    <a href="" class="item4 btn btn-dark btn-lg" >Inventari</a>
                </div>

                {{-- TAULES --}}
                <p class="h4 text-center" id="titol">Torns</p>
                    <table id="activitats" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Dia</th>
                            <th scope="col">Hores</th>
                        </tr>
                        </thead>
                        @foreach ($activitat as $a)
                        <tr>
                            <td>{{$a->jornada}}</td>
                            <td>{{$a->total}}</td>
                        </tr>
                        @endforeach
                    </table>
                    
                    <p class="h4 text-center" id="titol">Jornades</p>
                    <br>
                    <table id="jornades" class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Dia</th>
                            <th scope="col">Hores</th>
                        </tr>
                        </thead>
                        @foreach ($dia as $d)
                        <tr>
                            <td>{{$d->dia}}</td>
                            <td>{{$d->total}}</td>
                        </tr>
                        @endforeach
                    </table>
            </div>
        </div>
    </div>
</div>
@endsection
