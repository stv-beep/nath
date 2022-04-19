@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/fixedheader/3.2.2/js/dataTables.fixedHeader.min.js"></script>
<script src="{{ asset('js/AdminUsers.js') }}" defer></script>
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


                    {{-- MODAL EDIT --}}
                    <div class="modal" id="modalEditUser">
                        {{-- alert edit user success --}}
                        <div class='alert-position hidden alert alert-success' id='alert-success' role='alert'>
                            <strong id="alert-message-edit-user"></strong>&nbsp;
                        </div>
                        <div class='alert-position hidden alert alert-danger' id='alert-danger' role='alert'>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong id="alert-danger-message-inici"></strong>&nbsp;
                            &nbsp;&nbsp;   
                        </div>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 id="name">{{ __('messages.Edit') }} </h6><h6 id="id"></h6>
                                    <button id="closeModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                </div>
                            <div class="modal-body">
                                <form id="editUser" {{-- action="{{route('user.update',$user)}}" --}} method="POST">
                                @csrf
                                @method('put')
                                <input type="hidden" id="identificador">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">ID Odoo Nath</label>
                                    <div class="col-md-6"><input id="id_nath" name="id_nath" class="form-control" 
                                        type="text" autofocus>
                                </div></div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">ID Odoo TucTuc</label>
                                    <div class="col-md-6"><input id="id_tuctuc" name="id_tuctuc" class="form-control" 
                                        type="text" autofocus>
                                </div></div>
                                {{-- <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Admin') }}</label>
                                    <div class="col-md-6"><input id="admin" name="admin" class="form-control" 
                                        type="text" autofocus>
                                </div></div> --}}

                                <div class="row mb-3">
                                    <label for="magatzem" class="col-md-4 col-form-label text-md-end">{{ __('messages.Warehouse') }}</label>
                                        <div class="col-md-6">
                                            <input type="radio" id="magatzemSI" name="magatzem" value="1">
                                            <label for="magatzem">{{ __('messages.Yes') }}</label><br>
                                            <input type="radio" id="magatzemNO" name="magatzem" value="0">
                                            <label for="magatzem">No</label><br>
                                        </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="admin" class="col-md-4 col-form-label text-md-end">{{ __('messages.Admin') }}</label>
                                        <div class="col-md-6">
                                            <input type="radio" id="adminSI" name="admin" value="1">
                                            <label for="magatzem">{{ __('messages.Yes') }}</label><br>
                                            <input type="radio" id="adminNO" name="admin" value="0">
                                            <label for="magatzem">No</label><br>
                                        </div>
                                </div>

                                {{-- <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">{{ __('messages.Warehouse') }}</label>
                                    <div class="col-md-6"><input id="magatzem" name="magatzem" class="form-control" 
                                        type="text" autofocus>
                                </div></div> --}}</div>

                                <div class="modal-footer">
                                    <button id="updateUser" type="button" class="btn btn-outline-dark" {{-- onclick="updateUser()" --}}>{{ __('messages.Edit') }}</button>
                                </div>
                                </form>
                    
                            </div>
                        </div>
                    </div>

                    


                    {{-- DATATABLE --}}
                    <p class="h4 text-center" id="titol">{{ __('messages.Users') }}</p>
                    <table id="users" class="display compact hover row-border responsive nowrap" style="width:100%">
                        <thead class="thead-dark">
                        <tr style="text-align: center">
                            <th>ID</th>
                            <th scope="col">{{ __('messages.Worker') }}</th>
                            <th scope="col">ID Odoo Nath</th>
                            <th scope="col">ID Odoo TucTuc</th>
                            <th scope="col">{{ __('messages.Warehouse') }}</th>
                            <th scope="col">{{ __('messages.Admin') }}</th>
                            <th scope="col">DNI</th>
                            <th scope="col">{{ __('messages.Edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $u)
                        <tr style="text-align: center">
                            <td>{{$u->id}}</td>
                            <td>{{$u->name}}</td>
                            <td>{{$u->id_odoo_nath}}</td>
                            <td>{{$u->id_odoo_tuctuc}}</td>
                            @if($u->magatzem == 1)
                            <td>{{ __('messages.Yes') }}</td>
                            @else
                            <td>No</td>
                            @endif
                            @if($u->administrador == 1)
                            <td>{{ __('messages.Yes') }}</td>
                            @else
                            <td>No</td>
                            @endif
                            <td>{{$u->DNI}}</td>
                            <td><a onclick="modalEditUser({{$u}})" class="fas fa-user-edit center" id="icons-underline"></a></td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th scope="col">{{ __('messages.Worker') }}</th>
                                <th scope="col">ID Odoo Nath</th>
                                <th scope="col">ID Odoo TucTuc</th>
                                <th scope="col">{{ __('messages.Warehouse') }}</th>
                                <th scope="col">{{ __('messages.Admin') }}</th>
                                <th scope="col">DNI</th>
                                <th scope="col">{{ __('messages.Edit') }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- <p class="h4 text-center" id="titol">{{ __('messages.Users') }}</p>
                    <table id="users" class="display compact hover row-border responsive nowrap" style="width:100%">
                        <thead class="thead-dark">
                        <tr style="text-align: center">
                            <th>ID</th>
                            <th scope="col">{{ __('messages.Worker') }}</th>
                            <th scope="col">DNI</th>
                            <th scope="col">ID Odoo Nath</th>
                            <th scope="col">ID Odoo TucTuc</th>
                            <th scope="col">{{ __('messages.Warehouse') }}</th>
                            <th scope="col">{{ __('messages.Admin') }}</th>
                            <th scope="col">{{ __('messages.Edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr style="text-align: center">
                            
                        </tr>
 
                        </tbody>
                    </table> --}}
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection
