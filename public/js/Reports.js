$(document).ready( function () {
    $('#reports tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
    } );
    var table = $('#reports').DataTable({
        "lengthMenu": [[10, 25, 50, 100, 300, -1], [10, 25, 50, 100, 300, "All"]],
        "paging": true,
        stateSave: true,
        responsive: true,
        "pagingType": "full_numbers",
        "order": [[ 2, "desc" ]],//ordeno per dia
        drawCallback: function () {
            var sum = $('#reports').DataTable().column(3).data().sum();
            $('#total').html(sum);            
            $('#totalfoot').html(sum);
        },
        /* busqueda columna */
        initComplete: function () {
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
    //#
    table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
} );
//https://datatables.net/examples/advanced_init/footer_callback.html
//http://live.datatables.net/segeriwe/368/edit
