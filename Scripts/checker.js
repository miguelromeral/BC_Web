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
    if (comprobarIntegridadPartido()) {
        if (confirm("¿Está seguro de que quiere finalizar el partido?")) {
            return true;
        }
        return false;
    }else{
        return false;
    }
}

// Fuente: http://www.dyn-web.com/tutorials/forms/radio/get-selected.php
function getRadioVal(form, name) {
    var val;
    // get list of radio buttons with specified name
    var radios = form.elements[name];
    
    // loop through list of radio buttons
    for (var i=0, len=radios.length; i<len; i++) {
        if ( radios[i].checked ) { // radio checked?
            val = radios[i].value; // if so, hold its value in val
            break; // and break out of for loop
        }
    }
    return val; // return value of checked radio or undefined if none checked
}

function comprobarIntegridadPartido(){
    var gl = document.getElementsByName("gl")[0].value;
    var gv = document.getElementsByName("gv")[0].value;
    var pr = document.getElementsByName("pr")[0].checked;
    var pen = document.getElementsByName("pen")[0].checked;
    var tipo = document.getElementsByName("tipo")[0].value;
    var ganp = getRadioVal( document.getElementById('fpartido'), 'ganp' );
    
    if (tipo === "Final") {
        if (gl === gv){
            if(pr){
                if(pen){
                    if (typeof ganp === 'undefined'){
                        alert("Debe haber un ganador de penaltis");
                        return false;
                    }
                }else{
                    alert("No puede acabar el partido sin penaltis.");
                    return false;
                }
            }else{
                alert("No puede acabar el partido sin prórroga.");
                return false;
            }
        }
    }else{
        if (pr){
            alert("No puede haber prórroga en un partido de Fase de Grupos");
            return false;
        }
        if (pen){
            alert("No puede haber penaltis en un partido de Fase de Grupos");
            return false;
        }
        if (typeof ganp !== 'undefined'){
            alert("No puede haber ganador de penaltis en un partido de Fase de Grupos");
            return false;
        }
    }
    return true;
}

function limpiarGanp(){
   var ele = document.getElementsByName("ganp");
   for(var i=0;i<ele.length;i++)
      ele[i].checked = false;
     return true;
}


function ver_stats(obj){
    var selectBox = obj;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var pan1 = document.getElementById("stats_partidos");
    var pan2 = document.getElementById("stats_clasificaciones");
    var pan3 = document.getElementById("stats_competicion");
    var pan4 = document.getElementById("stats_jugadores");
    var pan5 = document.getElementById("stats_equipos");

    pan1.style.display = "none";
    pan2.style.display = "none";
    pan3.style.display = "none";
    pan4.style.display = "none";
    pan5.style.display = "none";

    if(selected === 'partidos'){
        pan1.style.display = "block";
    }
    else if(selected === 'clasificaciones'){
        pan2.style.display = "block";
    }
    else if(selected === 'competicion'){
        pan3.style.display = "block";
    }
    else if(selected === 'jugadores'){
        pan4.style.display = "block";
    }
    else if(selected === 'equipos'){
        pan5.style.display = "block";
    }   
}


function ver_stats_clasificacion(obj, ned){
    var selectBox = obj;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    
    var paneles = [];
    
    var i;
    var base = "stats_clasificaciones_";
    for (i = 1; i <= ned; i++) {
        var nombre = base.concat(i);
        var panel = document.getElementById(nombre);
        panel.style.display = 'none';
        paneles.push(panel);
    }
    
    if(selected !== 'null'){
        var ind = parseInt(selected) - 1;
        paneles[ind].style.display = 'block';
    }
    
}