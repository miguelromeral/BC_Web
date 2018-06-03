<?php
function listaEquipos ($jugador){
    echo "<p> ".$jugador.": ";
    $query = "SELECT nombre from equipo order by nombre;";
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
    if($conn->select_db($database)){
        return $conn;
    }else{
        crearEsquema($conn);
        $conn = new mysqli($host,$user,$password);
        $conn->select_db($database);
        return $conn;
    }
}

function registrarEquipos($conn, $nombre, $imagen){
    if (strlen($nombre) <= 30 && strlen($nombre) > 0){
        $query = "SELECT count(*) as cuenta from equipo where nombre = \"".$nombre."\";";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row["cuenta"] == 0){
            $query = "SELECT count(*) as cuenta FROM equipo;";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $idn = $row["cuenta"] + 1;
            $queryInsert = "INSERT INTO equipo (id, nombre, imagen) VALUES ('".$idn."', '".$nombre."', '".$imagen."');";
            guardarEnLogEquipo("INSERT INTO equipo (id, nombre, imagen) VALUES ('".$idn."', '".$nombre."', '??');");
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

function getEquipoFromUsuarioSesion($sesion, $id){
    $usuario = getUsuarioFromID($conn, $id);
    foreach ($sesion as $n => $v)
   {
        if (strcmp($n, $usuario) == 0){
            return $n;
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
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO marcador (partido, equipo, usuario, local, goles, ta, tr) VALUES "
            . "($idp, $el, $ul, 1, $gl, $tal, $trl);";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert); 
    $queryInsert = "INSERT INTO marcador (partido, equipo, usuario, local, goles, ta, tr) VALUES "
            . "($idp, $ev, $uv, 0, $gv, $tav, $trv);";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    
    return true;
}

function registrarUsuarios($conn){
    $queryInsert = "INSERT INTO usuario (id, nombre) VALUES (1, 'Miguel');";
    if (mysqli_query($conn, $queryInsert)){
        guardarEnLog($queryInsert);
    }
    $queryInsert = "INSERT INTO usuario (id, nombre) VALUES (2, 'Javi');";
    if (mysqli_query($conn, $queryInsert)){
        guardarEnLog($queryInsert);
    }
    $queryInsert = "INSERT INTO usuario (id, nombre) VALUES (3, 'Chechu');";
    if (mysqli_query($conn, $queryInsert)){
        guardarEnLog($queryInsert);
    }
    return true;
}

function registrarEdicion($conn, $edicion){
    $fecha = date("Y-m-d");
    $hora = date("H");
    $mins = date("i");
    $queryInsert = "INSERT INTO edicion (id, fecha, hora, mins) VALUES ($edicion, '$fecha', $hora, $mins);";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    return true;
}

function guardarEnLog($query){
    //$myfile = fopen('log.txt', 'w') or die("Can't create file");
    $myfile = file_put_contents('log.sql', $query.PHP_EOL , FILE_APPEND | LOCK_EX);
    //fclose($myfile);
}

function guardarEnLogEquipo($query){
    $myfile = file_put_contents('insertEquipos.sql', $query.PHP_EOL , FILE_APPEND | LOCK_EX);
}

function crearEsquema($conn){
    echo "Creo el esquema <br>";
    $query = "create database bc;";
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.partido
(
	id integer NOT NULL UNIQUE,	-- ID del partido
	edicion integer NOT NULL, 	-- Edicion
	tipo Varchar(20) NOT NULL,		-- Tipo {'Fase Grupos', 'Semifinal', 'Final'}
	num_ed Integer,				-- Numero de partido en Edicion
	prorroga Boolean,			-- Indica si hubo prorroga
	penaltis Boolean,			-- Indica si hubo penaltis
	ganador_penaltis Integer,	-- ID del equipo ganador en penaltis
 primary key (id)
);";
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.usuario
(
	id Integer NOT NULL UNIQUE,	-- ID del usuario
	nombre Varchar(20) NOT NULL,	-- Nombre del usuario
 primary key (id)
);";
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.equipo
(
	id Integer NOT NULL UNIQUE, -- ID del equipo
	nombre Varchar(30) NOT NULL,	-- Nombre del equipo
	imagen longblob NOT NULL,		-- Imagen
 primary key (id)
);";
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.marcador
(
	partido Integer NOT NULL,		-- # Partido
	equipo Integer NOT NULL,		-- Equipo que lo juega
	usuario Integer NOT NULL,		-- Usuario que lo juega
	local Boolean NOT NULL,			-- Indica si es local
	goles Integer NOT NULL,			-- Goles marcados
	ta Integer NOT NULL,			-- Tarjetas Amarillas 
	tr Integer NOT NULL,			-- Tarjetas Rojas
 primary key (partido,local)
);";
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.edicion
(
	id Integer NOT NULL UNIQUE,	-- Numero de edicion (desde 1)
	fecha Date NOT NULL,		-- Fecha en la que se jugo (DD-MM-AAAA)
	hora Integer NOT NULL,		-- Hora
	mins Integer NOT NULL,		-- Minutos
 primary key (id)
);";
    $result = mysqli_query($conn, $query);
}
