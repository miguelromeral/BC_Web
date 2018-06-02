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
        echo "<option value=\"".$equipo."\">".$equipo."</option>";
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

function getUsuarioFromEquipoSesion($sesion, $equipo){
    foreach ($sesion as $n => $v)
   {
        if (strcmp($v, $equipo) == 0){
            if (strcmp($n, "Miguel") == 0 || strcmp($n, "Javi") == 0 || strcmp($n, "Chechu") == 0){
                return $n;
            }
        }
   }
   return null;
}

function registrarPartido($conn, $ed, $tipo, $ul, $uv, $el, $ev, $gl, $gv, $tal, $tav, $trl, $trv, $pr, $pen, $ganp){
    $query = "SELECT count(*) as cuenta from partido;";
    $result = mysqli_query($conn, $query);
    $idp = mysqli_fetch_assoc($result)["cuenta"] + 1;
    
    $query = "SELECT count(*) as cuenta from partido where edicion = $ed;";
    $result = mysqli_query($conn, $query);
    $num_ed = mysqli_fetch_assoc($result)["cuenta"] + 1;
    
    $queryInsert = "INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES "
            . "($idp, $ed, '$tipo', $num_ed, $pr, $pen, $ganp);";
    echo $queryInsert."<br>";
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO marcador (partido, equipo, usuario, local, goles, ta, tr) VALUES "
            . "($idp, $el, $ul, 1, $gl, $tal, $trl);";
    echo $queryInsert."<br>";
    mysqli_query($conn, $queryInsert); 
    $queryInsert = "INSERT INTO marcador (partido, equipo, usuario, local, goles, ta, tr) VALUES "
            . "($idp, $ev, $uv, 0, $gv, $tav, $trv);";
    echo $queryInsert."<br>";
    mysqli_query($conn, $queryInsert);
    
    return true;
}

function registrarUsuarios($conn){
    $queryInsert = "INSERT INTO usuario (id, nombre) VALUES (1, 'Miguel');";
    echo $queryInsert."<br>";
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO usuario (id, nombre) VALUES (2, 'Javi');";
    echo $queryInsert."<br>";
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO usuario (id, nombre) VALUES (3, 'Chechu');";
    echo $queryInsert."<br>";
    mysqli_query($conn, $queryInsert);
    
    return true;
}

function registrarEdicion($conn, $edicion){
    $fecha = date("Y-m-d H:i:s");
    $hora = date("H");
    $mins = date("i");
    $queryInsert = "INSERT INTO edicion (id, fecha, hora, mins) VALUES ($edicion, '$fecha', $hora, $mins);";
    echo $queryInsert;
    mysqli_query($conn, $queryInsert);
    return true;
}