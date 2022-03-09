@extends('layouts.app')

@section('content')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" integrity="sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script src="{{ asset('js/Reports.js') }}" defer></script>

<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Reports') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Total: <span id="total"></span>
                    <p class="h4 text-center" id="titol">{{ __('messages.Working days') }}</p>
                    <table id="reports" class="display">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>{{-- numeracio --}}
                            <th scope="col">{{ __('messages.Worker') }}</th>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($dia as $d)
                        <tr>
                            <td></td>{{-- numeracio --}}
                            <td>{{$d->name}}</td>{{-- worker name --}}
                            <td>{{ date('d/m/Y', strtotime($d->dia)) }}</td>
                            @if ($d->total == null || $d->total == 0)
                            <td><div class="loadersmall"></div></td>
                            @else
                            <td>{{$d->total}}</td>
                            @endif
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>{{-- numeracio --}}
                                <th scope="col">{{ __('messages.Worker') }}</th>
                                <th scope="col">{{ __('messages.Day') }}</th>
                                <th scope="col">Total (min)</th>
                            </tr>
                        </tfoot>
                        {{-- <tfoot>
                            <tr>
                                <th colspan="2" style="text-align:right">Total:</th>
                                <th id="totalfoot"></th>
                            </tr>
                        </tfoot> --}}
                    </table>

                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
