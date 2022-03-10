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
        <div class="col-lg-12">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Reports') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div class="btn btn-dark">Total: <span id="total"></span></div>
                    <div class="btn btn-dark">Total: <span id="total2DateQuery"></span></div>
                    <br>
                    <div class="row mb-3">
                        <form id="reportQuery1" action="{{route('admin.query')}}" method="POST">
                            @csrf
                            Consulta:
                            <div class="col-md-6">
                                <label>{{ __('messages.Worker') }}</label>
                                <input id="worker" type="text">
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('messages.Day') }} 1</label>
                                <input id="reportDate1" type="date">
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('messages.Day') }} 2</label>
                                <input id="reportDate2" type="date">
                            </div>
                                <button type="button" class="btn btn-outline-dark" onclick="twoDateQuery()">Consultar</button>
                            </form>

                    </div>
                    <p class="h4 text-center" id="titol">{{ __('messages.Working days') }}</p>
                    <table id="reports" class="display compact hover row-border">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>{{-- numeracio --}}
                            <th scope="col">{{ __('messages.Worker') }}</th>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                            <th scope="col">ID</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($dia as $d)
                        <tr>
                            <td></td>{{-- numeracio --}}
                            <td>{{$d->name}}</td>{{-- worker name --}}
                            <td>{{ date('d/m/Y', strtotime($d->dia)) }}</td>
                            @if ($d->total == null || $d->total == 0)
                            <td>0</td>
                            @else
                            <td>{{$d->total}}</td>
                            <td>{{$d->treballador}}</td>
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
                                <th scope="col">ID</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
