@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/fixedheader/3.2.2/js/dataTables.fixedHeader.min.js"></script>
<script src="{{ asset('js/Reports.js') }}" defer></script>
<script src="{{ asset('js/Translate.js') }}" defer></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

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
                    <button id="btn-reload" type="button" class="btn btn-dark">{{ __('messages.Reload table') }}  <abbr title="Reload table"><i id="icon-reload" class="fas fa-sync-alt"></i></abbr></button>
                    <button type="button" class="btn btn-info" onclick="modalForQuery();">{{ __('messages.Exact query') }}</button>
                    <button type="button" class="btn btn-info" onclick="modalForCompleteQuery();">{{ __('messages.Complete query') }}</button>
                    <button type="button" class="btn btn-info" onclick="modalShiftQuery();">{{ __('messages.Shift query') }}</button>
                    <br>
                    {{-- MODAL 1 --}}
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
                                <div class="col-md-6" id="autocomplete1"><input id="worker" class="form-control" type="text" autofocus>
                                    <input id="workerID" class="typeahead form-control" type="hidden" autocomplete="on">
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

                    {{-- MODAL 2 --}}
                    <div class="modal" id="modalCompleteQuery">
                        <div class='alert-position hidden alert alert-warning' id='alert-warning' role='alert'>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong id="alert-danger-message-final"></strong>&nbsp;
                            &nbsp;&nbsp;   
                        </div>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    {{ __('messages.Complete query') }} 
                                    <button id="closeModal2" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body">
                            <form id="reportQuery1" action="{{route('admin.complete.query')}}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Worker') }}</label>
                                <div class="col-md-6 autocompletediv"><input id="worker0" class="typeahead form-control" type="text" autocomplete="on" autofocus>
                                    <input id="worker0id" class="typeahead form-control" type="hidden" autocomplete="on">
                            </div></div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Day') }}</label>
                                <div class="col-md-6"><input id="reportDate0" class="form-control" type="date" autofocus>
                            </div></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" onclick="completeQuery()">Consultar</button>
                            </div>
                            </form>
                            <div id="completeResult"><h4><span></span></h4></div>
                    </div></div></div>

                    {{-- MODAL 3 --}}
                    <div class="modal" id="modalWorkShiftQuery">
                        <div class='alert-position hidden alert alert-warning' id='alert-modal3' role='alert'>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong id="alert-danger-message-warning"></strong>&nbsp;
                            &nbsp;&nbsp;   
                        </div>
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    {{ __('messages.Shift query') }}
                                    <button id="closeModal3" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body">
                            <form id="reportQuery1" action="{{route('admin.complete.query')}}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Worker') }}</label>
                                <div id="autocomplete3" class="col-md-6"><input id="worker3" class="typeahead form-control" type="text" autocomplete="on" autofocus>
                                    <input id="worker3id" class="typeahead form-control" type="hidden" autocomplete="on">
                            </div></div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Day') }}</label>
                                <div class="col-md-6"><input id="reportDate3" class="form-control" type="date" autofocus>
                            </div></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" onclick="workShiftQuery()">Consultar</button>
                            </div>
                            </form>
                            <table class="table table-striped" id="shiftTable"></table>
                    </div></div></div>

                    <hr>
                    <p class="h4 text-center" id="titol">{{ __('messages.Working days') }}</p>
                    {{-- DATATABLE --}}
                    <table id="reports" class="display compact hover row-border responsive nowrap" style="width:100%">
                        <thead class="thead-dark">
                        <tr style="text-align: center">
                            {{-- <th>#</th> --}}
                            <th scope="col">{{ __('messages.Worker') }}</th>
                            <th scope="col">{{ __('messages.Day') }}</th>
                            <th scope="col">Total (min)</th>
                            <th scope="col">ID {{ __('messages.Worker') }}</th>
                            <th scope="col">Geolocalización</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <tr style="text-align: center">
                            {{-- <td></td> --}}
                            {{-- <td></td>
                            <td></td>
                            <td></td>
                            <td></td> --}}
                            
                        </tr>
                        
                        </tbody>
                        <tfoot id="foot">
                            <tr style="text-align: center">

                                <th scope="col">{{ __('messages.Worker') }}</th>
                                <th scope="col">{{ __('messages.Day') }}</th>
                                <th scope="col">Total (min)</th>
                                <th scope="col">ID {{ __('messages.Worker') }}</th>
                                <th scope="col">{{ __('messages.Geolocation') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

{{-- <script type="text/javascript">
    var path = "{{ route('admin.getEmployees') }}";
    $('input.typeahead').typeahead({
        source:  function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        }
    });
</script> --}}
@endsection
