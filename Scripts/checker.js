function validar_eleccion_equipos(){
    var em = document.getElementsByName("equipo_Miguel")[0].value;
    var ej = document.getElementsByName("equipo_Javi")[0].value;
    var ec = document.getElementsByName("equipo_Chechu")[0].value;
    
    if (em == "null" || ej == "null" || ec == "null"){
        alert("Debe seleccionar un equipo para cada jugador.");
        return false;
    }else{
        if (em == ej || em == ec || ej == ec){
            alert("Cada jugador debe tener un equipo DIFERENTE.");
            return false;
        }else
            return true;
    }
}  

function validar_equipos_partido(){
    var l = document.getElementsByName("local")[0].value;
    var v = document.getElementsByName("visitante")[0].value;
    var t = document.getElementsByName("tipo")[0].value;
    
    if (l == "null" || v == "null"){
        alert("Debe seleccionar un equipo para cada jugador.");
        return false;
    }
    if (t == "null"){
        alert("Seleccione un tipo de partido.");
        return false;
    }
    if (l == v){
        alert("No se puede seleccionar el mismo equipo.");
        return false;
    }
}  

function confirmar_final(){
    if (confirm("¿Está seguro de que quiere finalizar el partido?")) {
        return true;
    }
    return false;
}

function comprobarIntegridadPartido(){
    var gl = document.getElementsByName("gl")[0].value;
    var gv = document.getElementsByName("gv")[0].value;
}
