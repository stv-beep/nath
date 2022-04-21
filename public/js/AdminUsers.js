/* $(window).load(function() {
    $(".fa fa-spinner fa-pulse fa-3x fa-fw").fadeOut("slow");
    $('#users').html('<center> <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></center>')
}); */

window.onload = function () {
    var contenedor = document.getElementById('ContenedorSpinnerCrear');
    contenedor.style.visibility = 'hidden';
    contenedor.style.opacity = '0';
}

  
$(document).ready( function () {
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


/* var table = $('#users').DataTable( {
    responsive: true,
    "lengthMenu": [[5, 10, 25, 50, 100, 250, 500, 1000, -1], [5, 10, 25, 50, 100, 250, 500, 1000, "All"]],
    "paging": true,
    select: true,
    stateSave: true,
    'processing': true,
    'language': {
        'loadingRecords': '&nbsp;',
        'processing': '<div class="loading"></div>'
    },
    zeroRecords: "<div class='loading'></div>",
    "pagingType": "full_numbers",
    ///"order": [[ 1, "desc" ]],//ordeno per dia
    orderCellsTop: true,
    fixedHeader: {
        header: true,
        footer: true
    },
   
    serverSide: false,//era aixo el problema? no podia estar en TRUE?
    ajax: {
        dataType: 'json',
        url: '/userslist',
        type: 'GET',
        dataSrc: "",
    },
    columns: [
        {
          data: "id",
        },
        {
          data: "name",
        },
        {
          data: "DNI",
        },
        {
          data: "id_odoo_nath",
        },
        {
          data: "id_odoo_tuctuc",
        },
        {
          data: "magatzem",
        },
        {
          data: "administrador",
        },
    ],
    "columnDefs": [ {
        "targets": 8,
        "data": "editar",
        "render": function ( data, type, row, meta ) {
          return '<a onclick="modalEditUser("'+users.id+')" class="fas fa-user-edit center" id="icons-underline"></a>';
        }
      } ]
       
} ); */


/* modal */
function modalEditUser(user){
    document.getElementById('name').innerHTML = user.name;
    var id = user.id;
    //console.log(id)
    $('#modalEditUser').modal('show'); 
    $.ajax(
        {
            type: "GET",
            url: "/usuarios/edit/"+id,
            data: { id: id },
            success: function( response ) {
                console.log(response)
                $("#identificador").val(response.id)
                $("#id_nath").val(response.id_odoo_nath)
                $("#id_tuctuc").val(response.id_odoo_tuctuc)
                $("#admin").val(response.administrador)
                $("#magatzem").val(response.magatzem)
                
                //checking if 1 or 0 for radio check
                var magatzemRadios = document.getElementsByName('magatzem');
                    for (i = 0; i < magatzemRadios.length; i++) {
                        if (magatzemRadios[i].value == response.magatzem) {
                            //console.log(response.magatzem)
                            //console.log(magatzemRadios[i].value)
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
                console.dir()
                
                //var jsonData = JSON.parse(response);
                //console.log(jsonData)
                /* $("#id_nath").val(jsonData['id_odoo_nath']);
                $("#id_tuctuc").val(jsonData['id_odoo_tuctuc']);
                $("#admin").val(jsonData['administrador']);
                $("#magatzem").val(jsonData['magatzem']); */
                
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

            setInterval(function(){
                window.location.reload();
                $("#alert-message-edit-user").text("Edit");
                $("#alert-success")
                .fadeTo(4000, 1000)
                .slideUp(1000, function () {
                    $("#alert-success").slideUp(1000);
                });
              }, 1000)
            
            
            //var table = $('#users').DataTable();
            //$("#users").load(" #users");
            //table.ajax.reload();
            //$('#modalEditUser').modal('hide');
            });  
        

        $("#closeModal").on('click',function(){
            $('#modalEditUser').modal('hide');
            });
        
}

//https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio
//update
function updateUser(id){
    var id = document.getElementById('identificador').value;
    //console.log(id)
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    var id_nath = document.getElementById('id_nath').value;
    var id_tuctuc = document.getElementById('id_tuctuc').value;
    //var administrador = document.getElementById('admin').value;
    var magatzemSI = document.getElementById('magatzemSI');//.value;
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


    $.ajax(
        {
            type: "PUT",
            url: "/updateUser/"+id,
            data: { 
                id_nath : id_nath,
                id_tuctuc : id_tuctuc,
                admin : administrador,
                magatzem : magatzem
             },
            success: function( response ) {
                console.log(response)
               
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