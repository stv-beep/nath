//https://datatables.net/extensions/fixedheader/examples/options/columnFiltering.html
//https://datatables.net/forums/discussion/43792/column-search-second-header

/* datatable */
$(document).ready(function() {
    $('#reports tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="searchDT" type="text" placeholder="Buscar '+title+'" />' );
    } );
    var table = $('#reports').DataTable( {
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
        "order": [[ 1, "desc" ]],//ordeno per dia
        orderCellsTop: true,
        fixedHeader: {
            header: true,
            footer: true
        },
        /* scrollX: true,
        scrollY: '80%', */
        /* "drawCallback": function (settings) { 
            // Here the response
            var response = settings.json;
            console.log(response);
        }, */
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
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update total
            $('#total').html($( api.column( 2 ).footer() ).html(
                pageTotal.toFixed(2)+' h'
            ));  
        },
        serverSide: false,//era aixo el problema? no podia estar en TRUE?
        ajax: {
            dataType: 'json',
            url: '/reporting',
            type: 'GET',
            dataSrc: "",
        },
        columns: [
            {
              data: "name",
            },
            {
              data: "dia",
            },
            {
              data: "total",
            },
            {
              data: "treballador",
            },
        ],
           
    } );

    //new $.fn.dataTable.FixedHeader( table );

    /* table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw(); */

    $('#btn-reload').on('click', function(){
        $('#icon-reload').toggleClass("down");
        table.ajax.reload();
    });

});





//https://datatables.net/examples/advanced_init/footer_callback.html
//http://live.datatables.net/segeriwe/368/edit

var msgErrorQuery1;


function twoDateQuery(){
    translateAlertsQuery();
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
                console.log(response);
                $('#total2DateQuery').html(
                    worker+': '+ response[0]+
                    '<br>Total: '+response[1]+' '+hours);
            },
            error: function(xhr, textStatus, error){
                $("#alert-danger-message-inici").text(msgErrorQuery1);
                $("#alert-danger")
                .fadeTo(4000, 1000)
                .slideUp(1000, function () {
                    $("#alert-danger").slideUp(1000);
                });
            }
        });

}



/* modal */
function modalForQuery(){
    $('#modalQuery').modal('show'); 

    $("#closeModal").on('click',function(){
        $('#modalQuery').modal('hide');
        });
}
