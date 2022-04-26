/* fitxer de traduccions per JS, sobretot d'alerts */

let msgPrepComanda, msgRevComanda, msgExpedComanda, jornadaNoIniciada, noJornada;
let msgIniciJornada, msgFinalJornada, tascaNoAcabada, msgError, msgErrorQuery1, msgNoResults;
let msgRecepDescarga, msgRecepEntrada, msgRecepControl, msgRecepUbicar;
let msgReopLectura, msgReopEmbolsar, msgReopEtiquetar, msgReopOtros;
let msgInvCompactar, msgInventariar;
let msgUserEdited;

function translateAlerts(){   
    
    if (locale == 'ca') {
        msgIniciJornada = "Jornada iniciada amb èxit";
        msgFinalJornada = "Jornada finalitzada amb èxit";
        msgPrepComanda = "Preparació Comanda";
        msgRevComanda = "Revisió Comanda";
        msgExpedComanda = "Expedició Comanda";
        //recepcions
        msgRecepDescarga = "Descàrrega camió";
        msgRecepEntrada = "Lectura entrada";
        msgRecepControl = "Control de qualitat";
        msgRecepUbicar = "Ubicar producte";
        //reoperaciones
        msgReopLectura = "Lectura producte"
        msgReopEmbolsar = "Embolsar"
        msgReopEtiquetar = "Etiquetar"
        msgReopOtros = "Atres (Reoperacions)"
        //inventario
        msgInvCompactar = "Compactar"
        msgInventariar = "Inventariar"
        tascaNoAcabada = "pendent de ser acabada";//"Tens una tasca pendent de ser acabada";
        jornadaNoIniciada = "No pots inciar una tasca si no has començat la jornada.";
        msgError = "Sembla que ha hagut un error, per favor, torna-ho a intentar."
        noJornada = "No s'ha trobat cap inici de jornada coincident amb el teu registre.";
        //admin users
        msgUserEdited = "Usuari editat.";

        
    } else if (locale == 'es'){
        msgIniciJornada = "Jornada inciada con éxito";
        msgFinalJornada = "Jornada finalizada con éxito";
        msgPrepComanda = "Preparación Pedido";
        msgRevComanda = "Revisión Pedido";
        msgExpedComanda = "Expedición Pedido";
        //recepciones
        msgRecepDescarga = "Descarga camión";
        msgRecepEntrada = "Lectura entrada";
        msgRecepControl = "Control de calidad";
        msgRecepUbicar = "Ubicar producto";
        //reoperaciones
        msgReopLectura = "Lectura producto"
        msgReopEmbolsar = "Embolsar"
        msgReopEtiquetar = "Etiquetar"
        msgReopOtros = "Otros (Reoperaciones)"
        //inventario
        msgInvCompactar = "Compactar"
        msgInventariar = "Inventariar"
        tascaNoAcabada = "pendiente de acabar";//"Tienes una tarea pendiente de acabar";
        jornadaNoIniciada = "No puedes iniciar una tarea si no has empezado la jornada.";
        msgError = "Ha habido un error, vuelva a intentarlo."
        noJornada = "No se ha encontrado ningún inicio de jornada coincidente con tu registro.";
        //admin users
        msgUserEdited = "Usuario editado.";

    } else if (locale == 'en'){
        msgIniciJornada = "Work day started succesfully";
        msgFinalJornada = "Work day finished succesfully";
        msgPrepComanda = "Order preparation";
        msgRevComanda = "Order review";
        msgExpedComanda = "Order expedition";
        //receptions
        msgRecepDescarga = "Unload truck";
        msgRecepEntrada = "Entry reading";
        msgRecepControl = "Quality control";
        msgRecepUbicar = "Locate product";
        //reoperaciones
        msgReopLectura = "Product reading"
        msgReopEmbolsar = "To bag"
        msgReopEtiquetar = "To tag"
        msgReopOtros = "Others (Reoperations)"
        //inventario
        msgInvCompactar = "Compact"
        msgInventariar = "Inventory"
        tascaNoAcabada = "unfinished"//"You have an unfinished task";
        jornadaNoIniciada = "You can not start a task if you did not started the shift.";
        msgError = "An error occurred, try again."
        noJornada = "Currently there isn't any work day matching with you.";
        //admin users
        msgUserEdited = "User edited.";

    }

}

function translateAlertsQuery(){
    if (locale == 'ca'){
        msgNoResults = "No s'han trobat resultats."
        msgErrorQuery1 = 'Sembla que hi ha un error a la consulta'
        worker = 'Treballador'
        hours = 'hores'
        locationString = 'Localització'
        day = 'Dia'
        deviceInfo = "Info del dispositiu"
        shift = "Torn"
        shiftStart = "Inici torn"
        shiftEnd = "Fi torn"
        notSaved = "No guardada"
        task = "Tasca"
        taskStart = "Inici tasca"
        taskEnd = "Fi tasca"
    } else if (locale == 'es'){
        msgNoResults = "No se han encontrado resultados."
        msgErrorQuery1 = 'Parece que hay un error en la consulta'
        worker = 'Empleado'
        hours = 'horas'
        day = 'Día'
        locationString = 'Localización'
        deviceInfo = "Info del dispositivo"
        shift = "Turno"
        shiftStart = "Inicio turno"
        shiftEnd = "Fin turno"
        notSaved = "No guardada"
        task = "Tarea"
        taskStart = "Inicio tarea"
        taskEnd = "Fin tarea"
    } else if (locale == 'en'){
        msgNoResults = "No results found."
        msgErrorQuery1 = 'It seems there is an error in the query'
        worker = 'Employee'
        hours = 'hours'
        locationString = 'Location'
        day = 'Day'
        deviceInfo = "Device info"
        shift = "Shift"
        shiftStart = "Shift start"
        shiftEnd = "Shift end"
        notSaved = "Not saved"
        task = "Task"
        taskStart = "Start"
        taskEnd = "End"
    }
}