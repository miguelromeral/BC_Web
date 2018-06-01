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

