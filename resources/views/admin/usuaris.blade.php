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

{{-- spinner loader --}}
<div id="ContenedorSpinnerCrear" class="ContenedorSpinnerCrear">
    <div id="SpinnerCrear" class="SpinnerCrear"></div>
</div>

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
                    <button type="button" class="btn btn-info btns-reporting-btn" onclick="modalCreateUser();">{{ __('messages.Create user') }}</button>

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
                                    <span id="nameUser">{{ __('messages.Edit') }} </span>
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
                                    <label class="col-md-4 col-form-label text-md-end">ID: </label>
                                    <label id="id" class="col-md-4 col-form-label"></label>
                                    <div class="col-md-6">
                                </div></div>
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
                                </div></div>

                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">DNI</label>
                                    <div class="col-md-6"><input id="dni" name="dni" class="form-control" 
                                        type="text" autofocus>
                                </div></div>

                                <div class="modal-footer">
                                    <button id="closeModalUpdate" type="button" class="btn btn-outline-secondary">{{ __('messages.Cancel') }}</button>
                                    <button id="updateUser" type="button" class="btn btn-outline-dark" {{-- onclick="updateUser()" --}}>{{ __('messages.Edit') }}</button>
                                </div>
                                </form>
                    
                            </div>
                        </div>
                    </div>


                    {{-- MODAL DELETE --}}
                    <div class="modal" id="modalDeleteUser">
                        {{-- alert edit user success --}}
                        <div class='alert-position hidden alert alert-success' id='alert-success-delete' role='alert'>
                            <strong id="alert-message-delete-user"></strong>&nbsp;
                        </div>
                        <div class='alert-position hidden alert alert-danger' id='alert-danger-delete' role='alert'>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong id="alert-danger-message-delete"></strong>&nbsp;
                            &nbsp;&nbsp;   
                        </div>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span id="nameUser">{{ __('messages.Delete') }} </span>
                                    <button id="closeModalD" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                </div>
                            <div class="modal-body">
                                <p>{{ __('messages.msg Delete user') }}<strong id="name-user"></strong>?</p>
                                <p>{{ __('messages.msg Delete user 2') }}</p>

                                <div class="modal-footer">
                                    <button id="closeModalDelete" type="button" class="btn btn-outline-secondary">{{ __('messages.Cancel') }}</button>
                                    <button id="deleteUserBtn" type="button" class="btn btn-outline-danger">{{ __('messages.Delete') }}</button>
                                </div>
                            </div>
                        </div>
                    </div></div>


                    {{-- MODAL CREATE USER --}}
                    <div class="modal" id="modalCreateUser">
                        {{-- alert create user success --}}
                        <div class='alert-position hidden alert alert-success' id='alert-success-create' role='alert'>
                            <strong id="alert-message-create-user"></strong>&nbsp;
                        </div>
                        <div class='alert-position hidden alert alert-danger' id='alert-danger-create' role='alert'>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong id="alert-danger-message-create"></strong>&nbsp;
                            &nbsp;&nbsp;   
                        </div>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span>{{ __('messages.Create user') }} </span>
                                    <button id="closeModalCreate" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                </div>
                            <div class="modal-body">
                                <form id="createUser" method="POST" {{-- action="{{ route('create.user') }}" --}}>
                                @csrf
                                
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('messages.Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="username" class="col-md-4 col-form-label text-md-end">{{ __("messages.Username") }}</label>
        
                                    <div class="col-md-6">
                                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                        <small id="usernameHelp" class="form-text text-muted">{{ __("messages.UserCode") }}</small>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="dni" class="col-md-4 col-form-label text-md-end">{{ __('DNI') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="dni" type="dni" class="form-control @error('dni') is-invalid @enderror" name="dni" value="{{ old('dni') }}" required>
                                        @error('dni')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="magatzem" class="col-md-4 col-form-label text-md-end">{{ __('messages.Warehouse') }}</label>
                                        <div class="col-md-6">
                                            <input type="radio" id="magatzem" name="magatzem" value="1">
                                            <label for="magatzem">{{ __('messages.Yes') }}</label><br>
                                            <input type="radio" id="magatzem" name="magatzem" value="0">
                                            <label for="magatzem">No</label><br>
                                        </div>
                                </div></div>

                                <div class="row mb-3">
                                    <label for="id_odoo_nath" class="col-md-4 col-form-label text-md-end">ID Odoo Nath</label>
        
                                    <div class="col-md-6">
                                        <input id="id_nathCreate" name="id_nathCreate" class="form-control" 
                                        type="text" autofocus>
                                        <small class="form-text text-muted">{{ __("messages.Not required") }}</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="id_odoo_tuctuc" class="col-md-4 col-form-label text-md-end">ID Odoo Nath</label>
        
                                    <div class="col-md-6">
                                        <input id="id_tuctucCreate" name="id_tuctucCreate" class="form-control" 
                                        type="text" autofocus>
                                        <small class="form-text text-muted">{{ __("messages.Not required") }}</small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button id="closeModalC" type="button" class="btn btn-outline-secondary">{{ __('messages.Cancel') }}</button>
                                    {{-- <button type="submit" class="btn btn-primary">{{ __('messages.Create') }}</button> --}}
                                    <button id="createUserBtn" type="button" class="btn btn-outline-dark">{{ __('messages.Create') }}</button>
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
                            <th scope="col"></th>
                            <th scope="col">{{ __('messages.Admin') }}</th>
                            <th scope="col">DNI</th>
                            
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
                            <td>
                                <abbr title="{{ __('messages.Edit') }}"><a onclick="modalEditUser({{$u}})" class="fas fa-user-edit center" 
                                    id="icons-underline"></a></abbr>
                                <abbr title="{{ __('messages.Delete') }}"><a onclick="modalDeleteUser({{$u}})" class="fas fa-user-slash center" 
                                    id="icons-underline"></a></abbr>
                            </td>
                            @if($u->administrador == 1)
                            <td>{{ __('messages.Yes') }}</td>
                            @else
                            <td>No</td>
                            @endif
                            <td>{{$u->DNI}}</td>

                            
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
                                <th scope="col"></th>
                                <th scope="col">{{ __('messages.Admin') }}</th>
                                <th scope="col">DNI</th>
                                
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
