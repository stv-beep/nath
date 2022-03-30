/* fitxer de traduccions per JS, sobretot d'alerts */

let msgPrepComanda, msgRevComanda, msgExpedComanda, jornadaNoIniciada, noJornada;
let msgIniciJornada, msgFinalJornada, tascaNoAcabada, msgError, msgErrorQuery1, msgNoResults;

function translateAlerts(){   
    
    if (locale == 'ca') {
        msgIniciJornada = "Jornada iniciada amb èxit";
        msgFinalJornada = "Jornada finalitzada amb èxit";
        msgPrepComanda = "Preparació Comanda";
        msgRevComanda = "Revisió Comanda";
        msgExpedComanda = "Expedició Comanda";
        tascaNoAcabada = "pendent de ser acabada";//"Tens una tasca pendent de ser acabada";
        jornadaNoIniciada = "No pots inciar una tasca si no has començat la jornada.";
        msgError = "Sembla que ha hagut un error, per favor, torna-ho a intentar."
        noJornada = "No s'ha trobat cap inici de jornada coincident amb el teu registre.";

        
    } else if (locale == 'es'){
        msgIniciJornada = "Jornada inciada con éxito";
        msgFinalJornada = "Jornada finalizada con éxito";
        msgPrepComanda = "Preparación Pedido";
        msgRevComanda = "Revisión Pedido";
        msgExpedComanda = "Expedición Pedido";
        tascaNoAcabada = "pendiente de acabar";//"Tienes una tarea pendiente de acabar";
        jornadaNoIniciada = "No puedes iniciar una tarea si no has empezado la jornada.";
        msgError = "Ha habido un error, vuelva a intentarlo."
        noJornada = "No se ha encontrado ningún inicio de jornada coincidente con tu registro.";

    } else if (locale == 'en'){
        msgIniciJornada = "Work day started succesfully";
        msgFinalJornada = "Work day finished succesfully";
        msgPrepComanda = "Order preparation";
        msgRevComanda = "Order review";
        msgExpedComanda = "Order expedition";
        tascaNoAcabada = "unfinished"//"You have an unfinished task";
        jornadaNoIniciada = "You can not start a task if you did not started the shift.";
        msgError = "An error occurred, try again."
        noJornada = "Currently there isn't any work day matching with you.";

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