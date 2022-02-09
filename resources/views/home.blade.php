@extends('layouts.app')
<!--altres-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                <div class="options">
                    <a href="{{ route('pedidos.form') }}" class="btn btn-info btn-lg option" >Pedidos</a>
                    <a href="" class="btn btn-info btn-lg option" >Recepcions</a>
                    <a href="" class="btn btn-info btn-lg option" >Reoperacions</a>
                    <a href="" class="btn btn-info btn-lg option" >Inventari</a>
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
