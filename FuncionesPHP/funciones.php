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

function listaOpciones ($conn){
    ?>
    <select name="select_stats" onchange="ver_stats(this)">
        <option value="null">SELECCIONE LAS ESTADÍSTICAS</option>
        <option value="partidos">Partidos</option>
        <option value="clasificaciones">Clasificaciones</option>
        <option value="competicion">Competición</option>
        <option value="jugadores">Jugadores</option>
        <option value="equipos">Equipos</option>
    </select>
   
    <?php
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
        ?>
            <div id="stats_clasificaciones_<?= $i ?>" style="display: none;">
                Edición <?= $i ?>ª<br>
                <?php printClasificacion($conn, $i); ?>
            </div>
        <?php
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
<h1><?= getUsuarioFromID($conn, $jugador) ?></h1>
<table>
    <tr>
        <td id="td_ucl_blue">Títulos</td>
        <td id="td_ucl_white_bold" style="font-size: 46px;"><?= getCampeonatosUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Último título</td>
        <td id="td_ucl_white"><?php $fec = getUltimaUsuario($conn, $jugador);
        if($fec) { echo date("d/m/Y", strtotime($fec)); } else { echo "Sin títulos"; }?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">PTS Totales</td>
        <td id="td_ucl_white"><?= $pts ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Partidos</td>
        <td id="td_ucl_white"><?= $pj ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Victorias</td>
        <td id="td_ucl_white"><?= $pg ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Empates</td>
        <td id="td_ucl_white"><?= $pe ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Derrotas</td>
        <td id="td_ucl_white"><?= $pp ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">1º FG</td>
        <td id="td_ucl_white"><?= getPrimeroFGUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Prórrogas</td>
        <td id="td_ucl_white"><?= getPRUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Finales</td>
        <td id="td_ucl_white"><?= getFinalesUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">T. Amarillas</td>
        <td id="td_ucl_white"><?= $ta ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">T. Rojas</td>
        <td id="td_ucl_white"><?= $tr ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Tarj/Partido</td>
        <td id="td_ucl_white"><?php if ($pj > 0) { printf("%.2f", (($ta + $tr) / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Goles marcados</td>
        <td id="td_ucl_white"><?= $gf ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Goles encajados</td>
        <td id="td_ucl_white"><?= $gc ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Diferencia Goles</td>
        <td id="td_ucl_white"><?= $dg ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Goles/Partido</td>
        <td id="td_ucl_white"><?php if ($pj > 0) { printf("%.2f", ($gf / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">%Victorias</td>
        <td id="td_ucl_white"><?php if ($pj > 0) { printf("%.2f %%", ($pg / $pj) * 100); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">T.P Ganadas</td>
        <td id="td_ucl_white"><?php echo $tpg." / ".getTPUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Equipos</td>
        <td id="td_ucl_white"><?= getNumeroEquiposUsuario($conn, $jugador) ?></td>
    </tr>
</table>
    <?php
}

function estadisticasUsuario($conn, $usuario){
    echo "<div id=\"stats_jugadores_$usuario\" style=\"display: none;\">";
    //echo "<div id=\"stats_jugadores_$usuario\">";
    
    getImagenUsuario($usuario, 1.5);
    ?>
        <p><?php estadisticasJugador($conn, $usuario); ?></p>
        <p><?php equiposSeleccionadosUsuario($conn, $usuario); ?></p>
        <p><?php //finalesUsuario($conn, $usuario); ?></p>
        <p><?php golesEncajadosUsuarioEdicion($conn, $usuario); ?></p>
    <?php
    echo "</div>";
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
<table>
    <tr>
        <td id="td_ucl_blue">Campeonatos</td>
        <td id="td_ucl_white_bold" style="font-size: 46px;"><?= getCampeonatosEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Último título</td>
        <td id="td_ucl_white"><?php $fec = getUltimaEquipo($conn, $equipo);
        if($fec) { echo date("d/m/Y", strtotime($fec)); } else { echo "Sin títulos"; }?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">PTS Totales</td>
        <td id="td_ucl_white"><?= $pts ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Partidos</td>
        <td id="td_ucl_white"><?= $pj ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Victorias</td>
        <td id="td_ucl_white"><?= $pg ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Empates</td>
        <td id="td_ucl_white"><?= $pe ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Derrotas</td>
        <td id="td_ucl_white"><?= $pp ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">1º FG</td>
        <td id="td_ucl_white"><?= getPrimeroFGEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Prórrogas</td>
        <td id="td_ucl_white"><?= getPREquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Finales</td>
        <td id="td_ucl_white"><?= getFinalesEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">T. Amarillas</td>
        <td id="td_ucl_white"><?= $ta ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">T. Rojas</td>
        <td id="td_ucl_white"><?= $tr ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Tarj/Partido</td>
        <td id="td_ucl_white"><?php if ($pj > 0) { printf("%.2f", (($ta + $tr) / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Goles marcados</td>
        <td id="td_ucl_white"><?= $gf ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Goles encajados</td>
        <td id="td_ucl_white"><?= $gc ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Diferencia Goles</td>
        <td id="td_ucl_white"><?= $dg ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Goles/Partido</td>
        <td id="td_ucl_white"><?php if ($pj > 0) { printf("%.2f", ($gf / $pj)); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">%Victorias</td>
        <td id="td_ucl_white"><?php if ($pj > 0) { printf("%.2f %%", ($pg / $pj) * 100); } else { echo "NaN"; } ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">T.P Ganadas</td>
        <td id="td_ucl_white"><?php echo $tpg." / ".getTPEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Entrenadores</td>
        <td id="td_ucl_white"><?= getNumeroEntrenadoresEquipo($conn, $equipo) ?></td>
    </tr>
</table>
    <?php
}


function estadisticasEquiposTotal($conn){
    $query = "select id,nombre from equipo order by nombre asc;";
    $result = mysqli_query($conn, $query);
    
    
    echo "<select onchange=\"ver_stats_equipos(this, ". mysqli_num_rows($result).")\">";
    echo "<option value=\"null\">Seleccione un equipo</option>";
    while($row = mysqli_fetch_assoc($result))
    {
        echo "<option value=\"".$row["id"]."\">".$row["nombre"]."</option>";
    }
    echo "</select>";
    
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_assoc($result)){
        $equipo = $row["id"];
        //$equipo = 3;
        
        echo "<div id=\"stats_equipos_$equipo\" style=\"display: none;\">";
        
        estadisticasEquipo($conn, $equipo);
        ?>
        <p><?php golesEquipoEdicionUsuario($conn, $equipo); ?></p>
        <?php
        
        echo "</div>";
    }
}

function estadisticasCompeticion($conn){
    ?>
        <select onchange="ver_stats_competicion(this)">
            <option value="null">Seleccione una tabla</option>
            <option value="stats_competicion_fechas">Fechas Ediciones</option>
            <option value="stats_competicion_seleccionados">Equipos seleccionados</option>
            <option value="stats_competicion_goles_partido">Partidos con más goles</option>
            <option value="stats_competicion_goles_edicion">Ediciones con más goles</option>
            <option value="stats_competicion_palmares_equipo_usuario">Palmarés Equio + Jugador</option>
            <option value="stats_competicion_palmares">Palmarés</option>
    </select>
      
        <div id="stats_competicion_fechas" style="display: none;">
            <?php fechasEdiciones($conn);?></div>
    <div id="nanai" style="display: none;">
        <?php //getTablaEquiposRegistrados($conn);?></div>
    <div id="stats_competicion_seleccionados" style="display: none;">
        <?php equiposSeleccionadosPorUsuario($conn);?></div>
    <div id="nanai" style="display: none;">
        <?php //equiposSeleccionadosPorEdicion($conn);?></div>
    <div id="stats_competicion_goles_partido" style="display: none;">
        <?php goleadasPorPartido($conn);?></div>
    <div id="stats_competicion_goles_edicion" style="display: none;">
        <?php goleadasPorEdicion($conn);?></div>
    <div id="stats_competicion_palmares_equipo_usuario" style="display: none;">
        <?php palmaresEquipoUsuario($conn);?></div>
    <div id="stats_competicion_palmares" style="display: none;">
        <?php palmares($conn);?></div>
    
    <?php
}
