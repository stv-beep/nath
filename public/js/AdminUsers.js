window.onload = function () {
    var container = document.getElementById('ContenedorSpinnerCrear');
    container.style.visibility = 'hidden';
    container.style.opacity = '0';
}

  
$(document).ready( function () {

    $('#alert-success-create').hide();
    $('#alert-danger-create').hide();
    $('#alert-success-delete').hide();
    


    $('#users tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="searchDT" type="text" placeholder="Buscar '+title+'" />' );
    } );
    
    var table = $('#users').DataTable({
        "lengthMenu": [[5, 10, 25, 50, 100, 250, 500, 1000, -1], [5, 10, 25, 50, 100, 250, 500, 1000, "All"]],
        "paging": true,
        select: true,
        stateSave: true,
        'processing': true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="loading"></div>',
            "zeroRecords": "No se encontraron coincidencias",
        },
        orderCellsTop: true,
        fixedHeader: {
            header: true,
            //footer: true
        },
        zeroRecords: "<div class='loading'></div>",
        "pagingType": "full_numbers",
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    });
    $('#users tfoot tr').appendTo('#users thead');//append search to table head
} );


/* modal */
function modalEditUser(user){
    translateAlerts()
    document.getElementById('nameUser').innerHTML = user.name;
    document.getElementById('id').innerHTML = '&nbsp;&nbsp;&nbsp;'+user.id;
    var id = user.id;
    //console.log(id)
    $('#modalEditUser').modal('show'); 
    $.ajax(
        {
            type: "GET",
            url: "/usuarios/edit/"+id,
            data: { id: id },
            success: function( response ) {
                $("#identificador").val(response.id)
                $("#id_nath").val(response.id_odoo_nath)
                $("#id_tuctuc").val(response.id_odoo_tuctuc)
                $("#admin").val(response.administrador)
                $("#magatzem").val(response.magatzem)
                $("#dni").val(response.DNI)
                
                //checking if 1 or 0 for radio check
                var magatzemRadios = document.getElementsByName('magatzem');
                    for (i = 0; i < magatzemRadios.length; i++) {
                        if (magatzemRadios[i].value == response.magatzem) {
                            //console.log(response.magatzem)
                            magatzemRadios[i].checked = true;
                        }
                }
                //checking if 1 or 0 for radio check
                var adminRadios = document.getElementsByName('admin');
                    //per alguna rao si ho faig com al de magatzem, em funciona en local pero no en producció, 
                    //així que faig servir el següent:
                    adminRadios[1].checked = true;
					//console.log(response.administrador)
                    for (i = 0; i < adminRadios.length; i++) {
                        if (adminRadios[i].value == response.administrador) {
                            adminRadios[i].checked = true;
                        }
                }
            },
            error: function(xhr, textStatus, error){
                $("#alert-danger-message-final").text(msgError);
                    $("#alert-warning")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-warning").slideUp(1000);
                    });
            }
        });
        
        $("#updateUser").on('click',function(){
            updateUser($("#identificador").value);
            });  
        

        $("#closeModal").on('click',function(){
            $('#modalEditUser').modal('hide');
            });
        $("#closeModalUpdate").on('click',function(){
            $('#modalEditUser').modal('hide');
            });
        
}

//update
function updateUser(id){
    translateAlerts()
    var id = document.getElementById('identificador').value;
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    var id_nath = document.getElementById('id_nath').value;
    var id_tuctuc = document.getElementById('id_tuctuc').value;
    var magatzemSI = document.getElementById('magatzemSI');
    var magatzemNO = document.getElementById('magatzemNO');
    var magatzem = '';
    var administrador = '';
    var adminSI = document.getElementById('adminSI');
    var adminNO = document.getElementById('adminNO');

    if (adminSI.checked){
        administrador = 1;
    } else if (adminNO.checked){
        administrador = 0;
    }

    if (magatzemSI.checked){
        magatzem = 1;
    } else if (magatzemNO.checked){
        magatzem = 0;
    }

    var dniUser = document.getElementById('dni').value;

    $.ajax(
        {
            type: "PUT",
            url: "/updateUser/"+id,
            data: { 
                id_nath : id_nath,
                id_tuctuc : id_tuctuc,
                admin : administrador,
                magatzem : magatzem,
                dni : dniUser
             },
            success: function( response ) {
                $("#alert-message-edit-user").text(msgUserEdited);
                $("#alert-success")
                .fadeTo(4000, 1000)
                .slideUp(1000, function () {
                    $("#alert-success").slideUp(1000);
                });
                setInterval(function(){
                    window.location.reload();
                  }, 700)
            },
            error: function(xhr, textStatus, error){
                $("#alert-danger-message-final").text(msgError);
                    $("#alert-warning")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-warning").slideUp(1000);
                    });
            }
        });
}


/* create user */
function modalCreateUser(){
    translateAlerts()
    $('#modalCreateUser').modal('show'); 

    $("#createUserBtn").on('click',function(){

        $.ajax(
            {
                type: "POST",
                url: "/createUser",
                data: $('#createUser').serialize(),
                success: function( response ) {
                   
                    if (response == 'OK'){
                        
                        $("#alert-message-create-user").text(msgUserCreated);
                        $("#alert-success-create")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success-create").slideUp(1000);
                        });

                    //$('#modalCreateUser').modal('hide');
                    setInterval(function(){
                    window.location.reload();
                      }, 500)
                    } else {
                        
                        $("#alert-danger-message-create").text(msgError);
                        $("#alert-danger-create")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-danger-create").slideUp(1000);
                        });
                    }
   
                },
                error: function(xhr, textStatus, error){
                    $("#alert-danger-message-create").text(msgUserCreateError);
                        $("#alert-danger-create")
                        .fadeTo(4000, 1000)
                        .slideUp(2000, function () {
                            $("#alert-danger-create").slideUp(2000);
                        });
                }
            });
    });


    $("#closeModalCreate").on('click',function(){
        $('#modalCreateUser').modal('hide');
        });
    $("#closeModalC").on('click',function(){
        $('#modalCreateUser').modal('hide');
        });
}

/* delete user */
function modalDeleteUser(user) {
    translateAlerts()
    document.getElementById('name-user').innerHTML = user.name;
    var id = user.id;
    $('#modalDeleteUser').modal('show'); 

    $("#deleteUserBtn").on('click',function(){
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax(
            {
                type: "DELETE",
                url: "/deleteUser/"+id,
                data: {id: id},
                success: function( response ) {
                    if (response == 'OK'){
                        $("#alert-success-delete").show
                        $("#alert-message-delete-user").text(msgUserDeleted);
                        $("#alert-success-delete")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-success-delete").slideUp(1000);
                        });

                        //$('#modalDeleteUser').modal('hide');
                        setInterval(function(){
                            window.location.reload();
                        }, 500)
                    } else if (response == 'no admins'){
                        $("#alert-danger-message-delete").text(msgUserDeleteAdminError);
                        $("#alert-danger-delete")
                        .fadeTo(4000, 1000)
                        .slideUp(2000, function () {
                            $("#alert-danger-delete").slideUp(2000);
                        });
                    }
                },
                error: function(xhr, textStatus, error){
                    $("#alert-danger-message-delete").text(msgError);
                        $("#alert-danger-delete")
                        .fadeTo(4000, 1000)
                        .slideUp(1000, function () {
                            $("#alert-danger-delete").slideUp(1000);
                        });
                }
            });
    });

    $("#closeModalDelete").on('click',function(){
        $('#modalDeleteUser').modal('hide');
        });
    $("#closeModalD").on('click',function(){
        $('#modalDeleteUser').modal('hide');
        });
    
}
