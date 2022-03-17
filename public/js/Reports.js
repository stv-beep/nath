//https://datatables.net/extensions/fixedheader/examples/options/columnFiltering.html
//https://datatables.net/forums/discussion/43792/column-search-second-header

/* datatable */
$(document).ready(function() {

    $('#alert-warning').hide();//for some reason the warning alert pops up always

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

var msgErrorQuery1, msgNoResults;


function twoDateQuery(){
    translateAlertsQuery();
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    console.log('func');
    var treballador = document.getElementById('workerID').value;
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
                    '<br>Total: '+response[1].toFixed(2)+' '+hours);
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


function modalForCompleteQuery(){
    $('#modalCompleteQuery').modal('show'); 

    $("#closeModal2").on('click',function(){
        $('#modalCompleteQuery').modal('hide');
        });
}

function completeQuery(){
    translateAlertsQuery();
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    console.log('func');
    var treballador = document.getElementById('worker0id').value;
    var data0 = document.getElementById('reportDate0').value;
    $.ajax(
        {
            type: "POST",
            url: "/consulta-completa",
            data: { 
                worker : treballador,
                dia : data0,
             },
            success: function( response ) {
                //console.log('1 '+response[0]);
                if (response[0] === undefined){
                    $("#alert-danger-message-final").text(msgNoResults);
                    $("#alert-warning")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-warning").slideUp(1000);
                    });
                } else {
                let res = response[0];
                if (res.geolocation != null){
                    //console.log('2 '+res.geolocation)
                var geo = res.geolocation.split(' ');
                $('#completeResult').html(
                    
                    '<table class="table table-striped">'+
                    '<tr><th class="thead-dark">ID '+worker+': </th><td>'+res.treballador+'</td></tr>'+
                    '<tr><th>'+worker+': </th><td>'+res.name+'</td></tr>'+
                    '<tr><th class="thead-dark">'+day+': </th><td>'+moment(res.dia,'YYYY/MM/DD').format('DD/MM/YYYY')+'</td></tr>'+
                    '<tr><th>Total: </th><td>'+res.total.toFixed(2)+' h'+'</td></tr>'+
                    '<tr><th class="thead-dark">'+locationString+': </th><td>'+
                    '<a href="https://www.google.com/maps?q='+geo[0]+'+'+geo[1]+'" target="_blank">'
                    +res.geolocation+'</a>'+'</td></tr>'+
                    '</table>'

                    );

                } else{

                    $('#completeResult').html(
                    
                        '<table class="table table-striped">'+
                        '<tr><th class="thead-dark">ID '+worker+': </th><td>'+res.treballador+'</td></tr>'+
                        '<tr><th>'+worker+': </th><td>'+res.name+'</td></tr>'+
                        '<tr><th class="thead-dark">'+day+': </th><td>'+moment(res.dia,'YYYY/MM/DD').format('DD/MM/YYYY')+'</td></tr>'+
                        '<tr><th>Total: </th><td>'+res.total.toFixed(2)+' h'+'</td></tr>'+
                        '</table>'
    
                        );
                }
            }},
            error: function(xhr, textStatus, error){
                $("#alert-danger-message-final").text(msgNoResults);
                    $("#alert-warning")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-warning").slideUp(1000);
                    });
            }
        });


}


/* AUTOCOMPLETE 2*/
$(document).ready(function() {
    src = '/employees-query';//"{{ route('admin.getEmployees') }}";
    $("#worker0").autocomplete({
        appendTo : ".autocompletediv",
        select: function (event, ui) {//trigger when you click on the autocomplete item
            //event.preventDefault();//you can prevent the default event
            //console.log( ui.item.id);//employee id
            //console.log( ui.item.value);//employee name
            $('#worker0').val(ui.item.value)
            $('#worker0id').val(ui.item.id)
        },
        source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,

    });
});

/* AUTOCOMPLETE 1*/
$(document).ready(function() {
    src = '/employees-query';//"{{ route('admin.getEmployees') }}";
    $("#worker").autocomplete({
        appendTo : "#autocomplete1",
        select: function (event, ui) {//trigger when you click on the autocomplete item
            //event.preventDefault();//you can prevent the default event
            //console.log( ui.item.id);//employee id
            //console.log( ui.item.value);//employee name
            $('#worker').val(ui.item.value)
            $('#workerID').val(ui.item.id)
        },
        source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,

    });
});
