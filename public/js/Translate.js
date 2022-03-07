/* fitxer de traduccions per JS, sobretot d'alerts */

function translateAlerts(){   
    
    if (locale == 'ca') {
        msgIniciJornada = "Jornada iniciada amb èxit";
        msgFinalJornada = "Jornada finalitzada amb èxit";
        msgPrepComanda = "Preparació Comanda";
        msgRevComanda = "Revisió Comanda";
        msgExpedComanda = "Expedició Comanda";
        tascaNoAcabada = "Tens una tasca pendent de ser acabada.";
        jornadaNoIniciada = "No pots inciar una tasca si no has començat la jornada.";
        msgError = "Sembla que ha hagut un error, per favor, torna-ho a intentar."
        noJornada = "No s'ha trobat cap inici de jornada coincident amb el teu registre.";
        
    } else if (locale == 'es'){
        msgIniciJornada = "Jornada inciada con éxito";
        msgFinalJornada = "Jornada finalizada con éxito";
        msgPrepComanda = "Preparación Pedido";
        msgRevComanda = "Revisión Pedido";
        msgExpedComanda = "Expedición Pedido";
        tascaNoAcabada = "Tienes una tarea pendiente de acabar.";
        jornadaNoIniciada = "No puedes iniciar una tarea si no has empezado la jornada.";
        msgError = "Ha habido un error, vuelva a intentar-lo."
        noJornada = "No se ha encontrado ningún inicio de jornada coincidente con tu registro.";

    } else if (locale == 'en'){
        msgIniciJornada = "Work day started succesfully";
        msgFinalJornada = "Work day finished succesfully";
        msgPrepComanda = "Order preparation";
        msgRevComanda = "Order review";
        msgExpedComanda = "Order expedition";
        tascaNoAcabada = "You have an unfinished task.";
        jornadaNoIniciada = "You can not start a task if you did not started the shift.";
        msgError = "An error occurred, try again."
        noJornada = "Currently there isn't any work day matching with you.";

    }

}