//https://datatables.net/extensions/fixedheader/examples/options/columnFiltering.html
//https://datatables.net/forums/discussion/43792/column-search-second-header

/* datatable */
$(document).ready(function() {

    $('#alert-warning').hide();//for some reason the warning alert pops up always
    $('#alert-modal3').hide();
    $('#alert-modal4').hide();

    $('#reports tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="searchDT" type="text" placeholder="Buscar '+title+'" />' );
    } );
    var table = $('#reports').DataTable( {
        dom: 'lBfrtip', //https://datatables.net/examples/basic_init/dom.html
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
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
            {
              data: "geolocation",
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

    $('#reports tfoot tr').appendTo('#reports thead');//append search to table head
});





//https://datatables.net/examples/advanced_init/footer_callback.html
//http://live.datatables.net/segeriwe/368/edit



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

function modalShiftQuery(){
    $('#modalWorkShiftQuery').modal('show');
    document.getElementById("shiftTable").innerHTML  = '';//cleaning the modal table to not redisplay the same info again

    $("#closeModal3").on('click',function(){
        $('#modalWorkShiftQuery').modal('hide');
        });
}

function modalTaskQuery(){
    $('#modalTaskQuery').modal('show');
    $("#closeModal4").on('click',function(){
        $('#modalTaskQuery').modal('hide');
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
                if (response[0] === undefined || response[0].total == null){//no response or no result
                    $("#alert-danger-message-final").text(msgNoResults);
                    $("#alert-warning")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-warning").slideUp(1000);
                    });
                } else {//result
                let res = response[0];
                if (res.geolocation != null){
                    console.log(res)
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
                    '<tr><th>'+deviceInfo+': </th><td>'+res.info+'</td></tr>'+
                    '</table>'

                    );

                } else{

                    $('#completeResult').html(
                    
                        '<table class="table table-striped">'+
                        '<tr><th class="thead-dark">ID '+worker+': </th><td>'+res.treballador+'</td></tr>'+
                        '<tr><th>'+worker+': </th><td>'+res.name+'</td></tr>'+
                        '<tr><th class="thead-dark">'+day+': </th><td>'+moment(res.dia,'YYYY/MM/DD').format('DD/MM/YYYY')+'</td></tr>'+
                        '<tr><th>Total: </th><td>'+res.total.toFixed(2)+' h'+'</td></tr>'+
                        '<tr><th>'+deviceInfo+': </th><td>'+res.info+'</td></tr>'+
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
    $("#worker0").autocomplete({
        appendTo : ".autocompletediv",
        select: function (event, ui) {//trigger when you click on the autocomplete item
            //event.preventDefault();//you can prevent the default event
            $('#worker0').val(ui.item.value)
            $('#worker0id').val(ui.item.id)
        },
        source: function(request, response) {
            $.ajax({
                url: '/employees-query',
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
            $('#worker').val(ui.item.value)//employee name
            $('#workerID').val(ui.item.id)//employee id
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

/* AUTOCOMPLETE 3*/
$(document).ready(function() {
    src = '/employees-query';
    $("#worker3").autocomplete({
        appendTo : "#autocomplete3",
        select: function (event, ui) {//trigger when you click on the autocomplete item
            //event.preventDefault();//you can prevent the default event
            $('#worker3').val(ui.item.value)
            $('#worker3id').val(ui.item.id)
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

/* AUTOCOMPLETE 4*/
$(document).ready(function() {
    src = '/employees-query';
    $("#worker4").autocomplete({
        appendTo : "#autocomplete4",
        select: function (event, ui) {//trigger when you click on the autocomplete item
            //event.preventDefault();//you can prevent the default event
            $('#worker4').val(ui.item.value)
            $('#worker4id').val(ui.item.id)
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

function workShiftQuery(){
    var html_data = '';//cleaning the query result
    translateAlertsQuery();
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    var treballador = document.getElementById('worker3id').value;
    var data = document.getElementById('reportDate3').value;
    $.ajax(
        {
            type: "POST",
            url: "/consulta-turno",
            data: { 
                worker : treballador,
                dia : data,
            },
            success: function( response ) {
                if (response === undefined || response.length<1){//no response or no results
                    $("#alert-danger-message-warning").text(msgNoResults);
                    $("#alert-modal3")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-modal3").slideUp(1000);
                    });
                } else {//results
                    document.getElementById("shiftTable").innerHTML = html_data;//cleaning the query result
                for(var i=0;i<response.length;i++){   
                        console.log(response[i].iniciTorn);
                }
                    var iconTimer = '<i class="fas fa-hourglass-half fa-spin"></i>';
                        for(var i=0;i<response.length;i++){

                            if (response[i].geolocation != null){//if geolocation saved
                                var geo = response[i].geolocation.split(' ');
                                if (response[i].fiTorn == null) {
                                    response[i].fiTorn = iconTimer;
                                    response[i].total = iconTimer;
                                }

                           html_data += 
                           '<tr><th class="ocultable1">'+shift+'</th><th class="ocultable1">'+worker+'</th><th class="ocultable1">'+day+'</th><th>'+shiftStart+'</th><th>'+shiftEnd+'</th>'+
                           '<th>Total (h)</th><th>'+locationString+'</th><th class="ocultable1">'+deviceInfo+'</th></tr>'+
                           '<tr><td class="ocultable1">'+(i+1)+'</td><td class="ocultable1">'+response[i].name+'</td><td class="ocultable1">'+
                           moment(response[i].jornada,'YYYY/MM/DD').format('DD/MM/YYYY')+'</td>'+
                           '<td>'+moment(response[i].iniciTorn,'YYYY/MM/DD hh:mm').format('DD/MM/YYYY HH:mm')+
                           '</td><td>'+moment(response[i].fiTorn,'YYYY/MM/DD hh:mm').format('DD/MM/YYYY HH:mm')+'</td><td>'+response[i].total+'</td>'+
                           '<td><a href="https://www.google.com/maps?q='+geo[0]+'+'+geo[1]+'" target="_blank">'+
                           response[i].geolocation+'</a></td><td class="ocultable1">'+response[i].info+'</td></tr>'
                            } else {//no geolocation

                                if (response[i].fiTorn == null) {
                                    response[i].fiTorn = iconTimer;
                                    response[i].total = iconTimer;
                                }
                           html_data += 
                           '<tr><th class="ocultable1">'+shift+'</th><th class="ocultable1">'+worker+'</th><th class="ocultable1">'+day+'</th><th>'+shiftStart+'</th><th>'+shiftEnd+'</th>'+
                           '<th>Total (h)</th><th class="ocultable1">'+locationString+'</th><th>'+deviceInfo+'</th></tr>'+
                           '<tr><td class="ocultable1">'+(i+1)+'</td><td class="ocultable1">'+response[i].name+'</td><td class="ocultable1">'+
                           moment(response[i].jornada,'YYYY/MM/DD').format('DD/MM/YYYY')+'</td>'+
                           '<td>'+moment(response[i].iniciTorn,'YYYY/MM/DD hh:mm').format('DD/MM/YYYY HH:mm')+
                           '</td><td>'+moment(response[i].fiTorn,'YYYY/MM/DD hh:mm').format('DD/MM/YYYY HH:mm')+'</td><td>'+response[i].total+'</td>'+
                           '<td class="ocultable1">'+notSaved+'</td><td>'+response[i].info+'</td></tr>'
                           }
                                
                        }

                        document.getElementById("shiftTable").innerHTML = html_data;

            }},
            error: function(xhr, textStatus, error){
                $("#alert-danger-message-warning").text(msgNoResults);
                    $("#alert-modal3")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-modal3").slideUp(1000);
                    });
            }
        });


}


/* task query */

function taskQuery(){
    var html_data = '';//cleaning the query result
    translateAlertsQuery();
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    var treballador = document.getElementById('worker4id').value;
    var data = document.getElementById('reportDate4').value;
    $.ajax(
        {
            type: "POST",
            url: "/consulta-activitat",
            data: { 
                worker : treballador,
                dia : data,
            },
            success: function( response ) {
                if (response === undefined || response.length<1){//no response or no results
                    $("#alert-danger-message-task").text(msgNoResults);
                    $("#alert-modal4")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-modal4").slideUp(1000);
                    });
                } else {//results
                    document.getElementById("taskTableQuery").innerHTML = html_data;//cleaning the query result
                    console.log(response)
                    var iconTimer = '<i class="fas fa-hourglass-half fa-spin"></i>';
                    for(var i=0;i<response.length;i++){

                        if (response[i].geolocation != null){//if geolocation saved
                            var geo = response[i].geolocation.split(' ');
                            if (response[i].total == null || response[i].total == 0) {
                                response[i].fiTasca = iconTimer;
                                response[i].total = iconTimer;
                            }

                       html_data += 
                       '<tr><th>Nº</th><th>'+task+'</th><th>'+taskStart+'</th><th>'+taskEnd+'</th>'+
                       '<th>Total (min)</th><th class="ocultable1">'+locationString+'</th><th class="ocultable1">'+deviceInfo+'</th></tr>'+

                       '<tr><td>'+(i+1)+'</td><td>'+response[i].tasca+'</td><td>'+moment(response[i].iniciTasca).format('DD/MM/YYYY HH:mm')+'</td>'+
                       '<td>'+moment(response[i].fiTasca).format('DD/MM/YYYY HH:mm')+'</td><td>'+response[i].total+'</td>'+
                       '<td class="ocultable1"><a href="https://www.google.com/maps?q='+geo[0]+'+'+geo[1]+'" target="_blank">'+
                       response[i].geolocation+'</a></td><td class="ocultable1">'+response[i].info+'</td></tr>'
                        } else {//no geolocation

                            if (response[i].total == null || response[i].total == 0) {//if task unfinished
                                response[i].fiTasca = iconTimer;
                                response[i].total = iconTimer;
                            }
                       html_data += 
                       '<tr><th>Nº</th><th>'+task+'</th><th>'+taskStart+'</th><th>'+taskEnd+'</th>'+
                       '<th>Total (min)</th><th class="ocultable1">'+locationString+'</th><th class="ocultable1">'+deviceInfo+'</th></tr>'+

                       '<tr><td>'+(i+1)+'</td><td>'+response[i].tasca+'</td><td>'+moment(response[i].iniciTasca).format('DD/MM/YYYY HH:mm')+'</td>'+
                       '<td>'+moment(response[i].fiTasca).format('DD/MM/YYYY HH:mm')+'</td><td>'+response[i].total+'</td>'+
                       '<td class="ocultable1">'+notSaved+'</td><td class="ocultable1">'+response[i].info+'</td></tr>'
                       }
                            
                    }

                        document.getElementById("taskTableQuery").innerHTML = html_data;

            }},
            error: function(xhr, textStatus, error){
                $("#alert-danger-message-task").text(msgErrorQuery1);
                    $("#alert-modal4")
                    .fadeTo(4000, 1000)
                    .slideUp(1000, function () {
                        $("#alert-modal4").slideUp(1000);
                    });
            }
        });


}