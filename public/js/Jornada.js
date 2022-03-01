/* comprovacio de torns per a desabilitar botons */
window.onload = checkLastTorn;
const inic = document.getElementById("sendInici");
const f = document.getElementById("sendFi");
inic.disabled = true;
f.disabled = true;

function checkLastTorn(){
    $.ajax(
        {
            type: "GET",
            url: "/jornada/check",
            success: function(response){
              //alert(response); //response es la id de la tasca
              console.log("torn: "+response);
              //true = torn NO acabat
              //false = torn acabat
              if (response == true) {
                f.disabled = !response;
                document.getElementById("sendInici").classList.toggle('btn-outline-success');
              } else {
                inic.disabled = response;
                document.getElementById("sendFi").classList.toggle('btn-outline-danger');
              }

            },
            error: function(xhr, textStatus, error){
              $("#alert-danger-missatge-final").text("Sembla que ha hagut un error. Per favor, recarrega la pàgina.");
              $("#alert-danger")
              .fadeTo(4000, 1000)
              .slideUp(1000, function () {
                  $("#alert-danger").slideUp(2500);
              });
          }
        });
                
}







/* function checkLastTorn(){
    if (document.getElementsByTagName('td')[1] == undefined){
        inic.disabled = !inic;
    } else if (document.getElementsByTagName('td')[1].innerHTML == ""){//si no s'ha acabat el torn (i no hi ha total)
        f.disabled = !f;
    } else if (document.getElementsByTagName('td')[1].innerText.length > 0) {
        inic.disabled = !inic;
    }
} */