/* function enviant(){
    $('#send').click( function() {
        alert('funco send');
        console.log('funco send');
        $.ajax(
                {
                    type: "POST",
                    url: "{{route('wawa')}}",
                    data:$('#form-temps').serialize(),
                    success: function( data ) {
                        console.log(data);
                        $("#input-treballador").val();
                        $("#total_cron").val(h);
                        $("#inici-jornada").val(startTime);
                        $("#final-jornada").val(endTime);
                        
                    }
                }
            )
        }
        );
    } */

       /*  $('#form-temps').submit(function(e)){
            e.preventDefault();

            var inici = $(startTime);
            var fi = $(endTime);
            var tot = $(h);


            $.ajax({
                url: "{{route('activitat-form.store')}}",
                type: "POST",
                data:{
                    inici: inici,
                    fi: fi,
                    tot: tot
                },
                success:function(response){
                    if(response){
                        $('#form-temps')[0].reset();
                    }

                }

            });
        }); */