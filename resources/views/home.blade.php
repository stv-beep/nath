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
                    Benvingut {{$user->name}}!
                    <br>
                    ID: {{$user->id}}
                    <br>
                    Codi/nom d'usuari: {{$user->username}}
                </div>
                <a href="{{ route('jornada.form') }}" class="btn btn-xs btn-info pull-right">Jornada del dia 
                    <?php                  
                        $date = date('Y-m-d H:i:s');
                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
                        echo $newDate;
                    ?>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
