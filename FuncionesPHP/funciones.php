<?php
function listaEquipos ($jugador){
    echo "<p> ".$jugador.": ";
    $query = "SELECT nombre from equipo;";
    global $conn;
    $result = mysqli_query($conn, $query);
    echo "<select name=\"equipo_".$jugador."\">";
    echo "<option value=\"null\">Seleccione un equipo</option>";
    while($row = mysqli_fetch_assoc($result))
    {
        $equipo = $row["nombre"];
        echo "<option value=\".".$equipo."\">".$equipo."</option>";
    }
    echo "</select>";
    echo "</p>";
}

function conectarse(){
    $host = 'localhost';
    $database = 'bc';
    $user = 'root';
    $password = '';
    $conn = new mysqli($host,$user,$password);
    $conn->select_db($database);
    return $conn;
}

function registrarEquipos($conn, $nombre, $imagen){
    if (strlen($nombre) <= 20 && strlen($nombre) > 0){
        $query = "SELECT count(*) as cuenta from equipo where nombre = \"".$nombre."\";";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row["cuenta"] == 0){
            $query = "SELECT count(*) as cuenta FROM equipo;";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $idn = $row["cuenta"] + 1;
            $queryInsert = "INSERT INTO equipo (id, nombre, imagen) VALUES ('".$idn."', '".$nombre."', '".$imagen."');";

            $resultInsert = mysqli_query($conn, $queryInsert); 
            if($resultInsert){
               return true;
            }else{
               return false;
            }
        }else{
            return false;
        }
    }else{
        return false;
    }
}