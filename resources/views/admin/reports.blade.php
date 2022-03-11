@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script src="{{ asset('js/Reports.js') }}" defer></script>
<script src="{{ asset('js/Translate.js') }}" defer></script>

<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-lg">
                <div class="card-header">{{ __('Reports') }} 
                    <a id="icona" href="{{route('home')}}"><i class="fas fa-arrow-alt-circle-left fa-lg" style="color: #51cf66;"></i></a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div class="btn btn-dark noClicable">Total: <span id="total"></span></div>
                    <button type="button" class="btn btn-info" onclick="modalForQuery();">{{ __('messages.Exact query') }}</button>

                    <br>
                    <div class="modal" id="modalQuery">
                        <div class='alert-position hidden alert alert-danger' id='alert-danger' role='alert'>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong id="alert-danger-message-inici"></strong>&nbsp;
                            &nbsp;&nbsp;   
                        </div>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    {{ __('messages.Exact query') }} 
                                    <button id="closeModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body">
                            <form id="reportQuery1" action="{{route('admin.query')}}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Worker') }}</label>
                                <div class="col-md-6"><input id="worker" class="form-control" type="text" autofocus>
                            </div></div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Day') }} 1</label>
                                <div class="col-md-6"><input id="reportDate1" class="form-control" type="date" autofocus>
                            </div></div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Day') }} 2</label>
                                <div class="col-md-6"><input id="reportDate2" class="form-control" type="date" autofocus>
                            </div></div></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" onclick="twoDateQuery()">Consultar</button>
                            </div>
                            </form>
                            <div class="btn btn-dark noClicable"><h4><span id="total2DateQuery"></span></h4></div>

                    </div></div></div>
                    <hr>
                    {{-- <button type="button" id="btn-reload">Reload</button> --}}
                    <p class="h4 text-center" id="titol">{{ __('messages.Working days') }}</p>
                    {{-- DATATABLE --}}
                    <table id="reports" class="display compact hover row-border responsive nowrap" style="width:100%">
                        <thead class="thead-dark">
                        <tr style="text-align: center">
                            <th>#</th>{{-- numeracio --}}
                            <th scope="col">{{ __('messages.Worker') }}</th>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                            <th scope="col">ID</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($dia as $d)
                        <tr style="text-align: center">
                            <td></td>{{-- numeracio --}}
                            <td>{{$d->name}}</td>{{-- worker name --}}
                            <td>{{$d->dia}}{{-- {{ date('d/m/Y', strtotime($d->dia)) }} --}}</td>
                            @if ($d->total == null || $d->total == 0)
                            <td>0</td>
                            @else
                            <td>{{$d->total}}</td>
                            @endif
                            <td>{{$d->treballador}}</td>
                            
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="text-align: center">
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
