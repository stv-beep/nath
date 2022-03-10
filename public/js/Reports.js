$(document).ready(function() {
    $('#reports tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="searchDT" type="text" placeholder="Buscar '+title+'" />' );
    } );
    var table = $('#reports').DataTable( {
        responsive: true,
        "lengthMenu": [[10, 25, 50, 100, 300, 500, -1], [10, 25, 50, 100, 300, 500, "All"]],
        "paging": true,
        select: true,
        stateSave: true,
        responsive: true,
        "pagingType": "full_numbers",
        "order": [[ 2, "desc" ]],//ordeno per dia
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
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update total
            $('#total').html($( api.column( 3 ).footer() ).html(
                pageTotal.toFixed(2)+' hores'/*  +' ('+ total.toFixed(2) +' total)' */
            ));  
        }
        
    } );
    table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
} );


//https://datatables.net/examples/advanced_init/footer_callback.html
//http://live.datatables.net/segeriwe/368/edit



function twoDateQuery(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    console.log('func');
    var treballador = document.getElementById('worker').value;
    var data1 = document.getElementById('reportDate1').value;
    var data2 = document.getElementById('reportDate2').value;
    $.ajax(
        {
            type: "POST",
            url: "/consulta",
            data: { 
                worker : treballador,
                dia1 : data1,
                dia2 : data2,
             },
            success: function( response ) {
                console.log('ajax');
                console.log(response);
                //alert("suma= "+response);
                $('#total2DateQuery').html(response+' hores');
            }
        });

}