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
    $queryInsert = "INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES "
            . "($idp, $ed, $el, $ul, 1, $gl, $tal, $trl);";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert); 
    $queryInsert = "INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES "
            . "($idp, $ed, $ev, $uv, 0, $gv, $tav, $trv);";
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

function registrarEdicion($conn, $edicion, $em, $ej, $ec){
    $fecha = date("Y-m-d");
    $hora = date("H");
    $mins = date("i");
    $queryInsert = "INSERT INTO edicion (id, fecha, hora, mins) VALUES ($edicion, '$fecha', $hora, $mins);";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO eleccion (edicion, usuario, equipo) VALUES ($edicion, 1, ". getIDEquipo($conn, $em).");";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO eleccion (edicion, usuario, equipo) VALUES ($edicion, 2, ". getIDEquipo($conn, $ej).");";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    $queryInsert = "INSERT INTO eleccion (edicion, usuario, equipo) VALUES ($edicion, 3, ". getIDEquipo($conn, $ec).");";
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
    guardarEnLog($query);
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
    guardarEnLog($query);
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.usuario
(
	id Integer NOT NULL UNIQUE,	-- ID del usuario
	nombre Varchar(20) NOT NULL,	-- Nombre del usuario
 primary key (id)
);";
    guardarEnLog($query);
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.equipo
(
	id Integer NOT NULL UNIQUE, -- ID del equipo
	nombre Varchar(30) NOT NULL,	-- Nombre del equipo
	imagen longblob NOT NULL,		-- Imagen
 primary key (id)
);";
    guardarEnLog($query);
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.marcador
(
	partido Integer NOT NULL,		-- # Partido
        edicion Integer NOT NULL,
	equipo Integer NOT NULL,		-- Equipo que lo juega
	usuario Integer NOT NULL,		-- Usuario que lo juega
	local Boolean NOT NULL,			-- Indica si es local
	goles Integer NOT NULL,			-- Goles marcados
	ta Integer NOT NULL,			-- Tarjetas Amarillas 
	tr Integer NOT NULL,			-- Tarjetas Rojas
 primary key (partido,local)
);";
    guardarEnLog($query);
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.edicion
(
	id Integer NOT NULL UNIQUE,	-- Numero de edicion (desde 1)
	fecha Date NOT NULL,		-- Fecha en la que se jugo (DD-MM-AAAA)
	hora Integer NOT NULL,		-- Hora
	mins Integer NOT NULL,		-- Minutos
 primary key (id)
);";
    guardarEnLog($query);
    $result = mysqli_query($conn, $query);
    $query = "Create table bc.eleccion
(
	edicion Integer Not null,
	usuario Integer Not null,
	equipo Integer Not null,
	PRIMARY KEY(edicion, usuario, equipo)
);";
    guardarEnLog($query);
    $result = mysqli_query($conn, $query);
}

function listaEdiciones ($conn){
    $ned = getNumeroEdiciones($conn);
    echo "<p>Clasificación por ediciones:<br>";
    echo "<select name=\"clas_edicion\" id=\"clas_edicion\" onchange=\"cambiar_clas_ed()\">";
    echo "<option value=\"null\">Seleccione una edición</option>";
    for ($i=1; $i <= $ned; $i++){
        echo "<option value=\"".$i."\">".$i."ª</option>";
    }
    echo "</select>";
    echo "</p>";
}

function listaTodosPartidos ($conn){
    $ned = getNumeroEdiciones($conn);
    echo "<p>Partidos por edición:<br>";
    for ($i=1; $i <= $ned; $i++){
        echo "<h2>Edición ".$i."ª</h2>";
        getTablaPartidosEdicion($conn, $i);
    }
    echo "</p>";
}

function listaTodasClasificaciones($conn){
    $ned = getNumeroEdiciones($conn);
    for ($i = 1; $i <= $ned; $i++){
        echo "Edición ".$i."ª<br>";
        printClasificacion($conn, $i);
    }
}

function estadisticasJugador($conn, $jugador){
    $pj = getPJUsuario($conn, $jugador);
    $ta = getTAUsuario($conn, $jugador);
    $tr = getTRUsuario($conn, $jugador); 
    $pg = getPGUsuario($conn, $jugador);
    $pe = getPEUsuario($conn, $jugador);
    $pp = getPPUsuario($conn, $jugador);
    $gf = getGFUsuario($conn, $jugador);
    $gc = getGCUsuario($conn, $jugador);;
    $pts = $pg * 3 + $pe;
    $dg = $gf - $gc;
    $tpg = getPENGUsuario($conn, $jugador);
    
    ?>
<hr>
        <?php getImagenUsuario($jugador, 1.5); ?>
<h1><?= getUsuarioFromID($conn, $jugador) ?></h1>
<table border="1">
    <tr>
        <td>Campeonatos</td>
        <td><?= getCampeonatosUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td>Último título</td>
        <td><?php $fec = getUltimaUsuario($conn, $jugador);
        if($fec) { echo date("d/m/Y", strtotime($fec)); } else { echo "Sin títulos"; }?></td>
    </tr>
    <tr>
        <td>PTS Totales</td>
        <td><?= $pts ?></td>
    </tr>
    <tr>
        <td>PJ</td>
        <td><?= $pj ?></td>
    </tr>
    <tr>
        <td>PG</td>
        <td><?= $pg ?></td>
    </tr>
    <tr>
        <td>PE</td>
        <td><?= $pe ?></td>
    </tr>
    <tr>
        <td>PP</td>
        <td><?= $pp ?></td>
    </tr>
    <tr>
        <td>1º FG</td>
        <td><?= getPrimeroFGUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td>Prórrogas</td>
        <td><?= getPRUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td>Finales</td>
        <td><?= getFinalesUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td>TA</td>
        <td><?= $ta ?></td>
    </tr>
    <tr>
        <td>TR</td>
        <td><?= $tr ?></td>
    </tr>
    <tr>
        <td>T/P</td>
        <td><?php if ($pj > 0) { printf("%.2f", (($ta + $tr) / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td>GF</td>
        <td><?= $gf ?></td>
    </tr>
    <tr>
        <td>GC</td>
        <td><?= $gc ?></td>
    </tr>
    <tr>
        <td>DG</td>
        <td><?= $dg ?></td>
    </tr>
    <tr>
        <td>G/P</td>
        <td><?php if ($pj > 0) { printf("%.2f", ($gf / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td>%Victorias</td>
        <td><?php if ($pj > 0) { printf("%.2f %%", ($pg / $pj) * 100); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td>T.P Ganadas</td>
        <td><?php echo $tpg." / ".getTPUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td>Equipos</td>
        <td><?= getNumeroEquiposUsuario($conn, $jugador) ?></td>
    </tr>
</table>


        

    <?php
}

function estadisticasEquipo($conn, $equipo){
    $pj = getPJEquipo($conn, $equipo);
    $ta = getTAEquipo($conn, $equipo);
    $tr = getTREquipo($conn, $equipo); 
    $pg = getPGEquipo($conn, $equipo);
    $pe = getPEEquipo($conn, $equipo);
    $pp = getPPEquipo($conn, $equipo);
    $gf = getGFEquipo($conn, $equipo);
    $gc = getGCEquipo($conn, $equipo);
    $pts = $pg * 3 + $pe;
    $dg = $gf - $gc;
    $tpg = getPENGEquipo($conn, $equipo);
    
    ?>
<hr>
        <?php getImagenEquipoID($conn, $equipo, 0.5); ?>
<h1><?= getNombreEquipo($conn, $equipo) ?></h1>
<table border="1">
    <tr>
        <td>Campeonatos</td>
        <td><?= getCampeonatosEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td>Último título</td>
        <td><?php $fec = getUltimaEquipo($conn, $equipo);
        if($fec) { echo date("d/m/Y", strtotime($fec)); } else { echo "Sin títulos"; }?></td>
    </tr>
    <tr>
        <td>PTS Totales</td>
        <td><?= $pts ?></td>
    </tr>
    <tr>
        <td>PJ</td>
        <td><?= $pj ?></td>
    </tr>
    <tr>
        <td>PG</td>
        <td><?= $pg ?></td>
    </tr>
    <tr>
        <td>PE</td>
        <td><?= $pe ?></td>
    </tr>
    <tr>
        <td>PP</td>
        <td><?= $pp ?></td>
    </tr>
    <tr>
        <td>1º FG</td>
        <td><?= getPrimeroFGEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td>Prórrogas</td>
        <td><?= getPREquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td>Finales</td>
        <td><?= getFinalesEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td>TA</td>
        <td><?= $ta ?></td>
    </tr>
    <tr>
        <td>TR</td>
        <td><?= $tr ?></td>
    </tr>
    <tr>
        <td>T/P</td>
        <td><?php if ($pj > 0) { printf("%.2f", (($ta + $tr) / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td>GF</td>
        <td><?= $gf ?></td>
    </tr>
    <tr>
        <td>GC</td>
        <td><?= $gc ?></td>
    </tr>
    <tr>
        <td>DG</td>
        <td><?= $dg ?></td>
    </tr>
    <tr>
        <td>G/P</td>
        <td><?php if ($pj > 0) { printf("%.2f", ($gf / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td>%Victorias</td>
        <td><?php if ($pj > 0) { printf("%.2f %%", ($pg / $pj) * 100); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td>T.P Ganadas</td>
        <td><?php echo $tpg." / ".getTPEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td>Entrenadores</td>
        <td><?= getNumeroEntrenadoresEquipo($conn, $equipo) ?></td>
    </tr>
</table>


        

    <?php
}


function estadisticasEquiposTotal($conn){
    $query = "select id,nombre from equipo order by nombre asc;";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        estadisticasEquipo($conn, $row["id"]);
    }
}

function estadisticasCompeticion($conn){
    fechasEdiciones($conn);
    getTablaEquiposRegistrados($conn);
    equiposSeleccionadosPorUsuario($conn);
    equiposSeleccionadosPorEdicion($conn);
    goleadasPorPartido($conn);
}

function fechasEdiciones($conn){
    $query = "select * from Edicion order by fecha desc;";
    $result = mysqli_query($conn, $query);
    ?>

        <table border="1">
            <tr>
            <i>Celebración de ediciones</i>
            </tr>
            <tr>
                <td>Edición</td>
                <td>Fecha</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row["id"] ?></td>
                <td><?php echo date("d/m/Y", strtotime($row["fecha"]))." - ".$row["hora"].":".$row["mins"]; ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}
function equiposSeleccionadosPorUsuario($conn){
    $query = "select e.nombre, n.user, count(n.user) as veces_cogido          
   from Equipo as e          
   inner join (          
   	select distinct m.equipo, u.nombre as user, m.edicion          
       from Marcador as m          
       inner join Usuario as u          
       on m.usuario = u.id          
   ) as n          
   on e.id = n.equipo          
   group by e.nombre, n.user          
   order by veces_cogido desc;";
    $result = mysqli_query($conn, $query);
    ?>

        <table border="1">
            <tr>
            <i>Selección de equipos</i>
            </tr>
            <tr>
                <td>Equipo</td>
                <td>Usuario</td>
                <td>Veces seleccionado</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?php
                $eq = $row["nombre"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.07);
                echo " $eq";
                ?></td>
                <td><?php
                $us = $row["user"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.1);
                echo " $us";
                ?></td>
                <td><?= $row["veces_cogido"] ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}
function equiposSeleccionadosPorEdicion($conn){
    $query = "select e.nombre, n.user, n.edicion          
   from Equipo as e          
   inner join (          
   	select distinct m.equipo, u.nombre as user,          
       m.edicion          
       from Marcador as m          
       inner join Usuario as u          
       on m.usuario = u.id          
   ) as n          
   on e.id = n.equipo          
   order by n.edicion desc, n.user;";
    $result = mysqli_query($conn, $query);
    ?>

        <table border="1">
            <tr>
            <i>Selección de equipos</i>
            </tr>
            <tr>
                <td>Equipo</td>
                <td>Usuario</td>
                <td>Edición</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?php
                $eq = $row["nombre"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.07);
                echo " $eq";
                ?></td>
                <td><?php
                $us = $row["user"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.1);
                echo " $us";
                ?></td>
                <td><?= $row["edicion"] ?>ª</td>
            </tr>    
        <?php
    }
    echo "</table>";
}

function goleadasPorPartido($conn){
    $query = "select max(m.goles) as maximo_goles_partido, e.nombre, m.tipo, m.num_ed, m.edicion, m.user, m.id
   from (          
       select p.tipo, p.id, p.num_ed, p.edicion, n.goles, n.equipo, n.nombre as user          
       from (          
       	select m.goles, m.equipo, u.nombre, m.partido          
           from Usuario as u          
           inner join Marcador as m          
           on m.usuario = u.id       
       ) as n          
       inner join Partido as p          
       on p.id = n.partido          
   ) as m          
   inner join Equipo as e          
   on e.id = m.equipo          
   group by e.nombre, m.tipo, m.num_ed, m.edicion, m.id, m.user
   order by maximo_goles_partido desc          
   limit 20;";
    $result = mysqli_query($conn, $query);
    ?>

        <table border="1">
            <tr>
            <i>Mayores goleadas en partido</i>
            </tr>
            <tr>
                <td>Goles</td>
                <td>Equipo</td>
                <td>Edición</td>
                <td>Tipo</td>
                <td>Usuario</td>
                <td>#Partido</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?= $row["maximo_goles_partido"] ?></td>
                <td><?php
                $eq = $row["nombre"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.07);
                echo " $eq";
                ?></td>
                <td><?= $row["edicion"] ?>ª</td>
                <td><?= $row["tipo"] ?></td>
                <td><?php
                $us = $row["user"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.1);
                echo " $us";
                ?></td>
                <td><?= $row["id"] ?>º</td>
            </tr>    
        <?php
    }
    echo "</table>";
}