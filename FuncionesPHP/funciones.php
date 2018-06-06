<?php
 /**
  * Crea un select en HTML para que se puedan seleccionar los equipos
  * @param string $jugador Nombre del jugador que tendrá ese equipo.
  */
function listaEquipos ($jugador){
    echo "<p> ".$jugador.": <br>";
    //Obtenemos el nombre de todos los equipos registrados en orden.
    $query = "SELECT nombre from equipo order by nombre;";
    global $conn;
    $result = mysqli_query($conn, $query);
    echo "<select name=\"equipo_".$jugador."\">";
    echo "<option value=\"null\">SELECCIONE UN EQUIPO</option>";
    while($row = mysqli_fetch_assoc($result))
    {
        //Por cada equipo, creamos un option
        $equipo = $row["nombre"];
        echo "<option value=\"".$equipo."\">".$equipo."</option>";
    }
    echo "</select>";
    echo "</p>";
}

/**
 * Se conecta a la base de datos. Si no se puede conectar, genera el esquema de la 
 * base de datos y vuelve a intentar la conexión. (Si MySQL está desactivado,
 * no retornará nada).
 * @return \mysqli Conexión con la base de datos
 */
function conectarse(){
    $host = 'localhost';    //Dirección del servidor
    $database = 'bc';       //Base de datos
    $user = 'root';         //Nombre de usuario del propietario
    $password = '';         //Contraseña del usuario
    //Generamos la conexión
    $conn = new mysqli($host,$user,$password);
    if($conn->select_db($database)){
        //Si todo salió bien, la devolvemos.
        return $conn;
    }else{
        //Sino, creamos el esquema y volvemos a intentarlo.
        crearEsquema($conn);
        $conn = new mysqli($host,$user,$password);
        $conn->select_db($database);
        return $conn;
    }
}

/**
 * Registra en la base de datos el equipo
 * @param \mysqli $conn Conexión con la base de datos.
 * @param string $nombre Nombre del equipo.
 * @param string $imagen Escudo del equipo.
 * @return boolean Registro con éxito.
 */
function registrarEquipos($conn, $nombre, $imagen){
    //Comprobamos que le nombre del equipo esté entre lo permitido.
    if (strlen($nombre) <= 30 && strlen($nombre) > 0){
        //Obtenemos el número de equipos que coinciden con ese nombre.
        $query = "SELECT count(*) as cuenta from equipo where nombre = \"".$nombre."\";";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        //Si no hay ningún equipo, se registrará:
        if ($row["cuenta"] == 0){
            //Obtenemos el ID del equipo contando los existentes.
            $query = "SELECT count(*) as cuenta FROM equipo;";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $idn = $row["cuenta"] + 1;
            //Creamos la consulta SQL y la guardamos en el log.
            $queryInsert = "INSERT INTO equipo (id, nombre, imagen) VALUES ('".$idn."', '".$nombre."', '".$imagen."');";
            guardarEnLogEquipo("INSERT INTO equipo (id, nombre, imagen) VALUES ('".$idn."', '".$nombre."', '??');");
            //Ejecutamos la consulta
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

/**
 * Retorna el usuario que ha seleccionado un equipo en esta sesión.
 * @param _SESSION  $sesion Sesión actual
 * @param string $equipo Nombre del equipo por el que buscar
 * @return string Nombre de usuario 
 */
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

/**
 * Retorna el nombre del equipo en función del ID del usuario
 * @param _SESSION $sesion Sesión actual
 * @param integer $id ID del usuario
 * @return string Nombre del equipo
 */
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

/**
 * Registra en la BD un partido creando consultas MySQL
 * @param \mysqli $conn Conexión con la BD
 * @param integer $ed Número de edición
 * @param string $tipo Tipo de partido
 * @param integer $ul ID usuario local
 * @param integer $uv ID usuario visitante
 * @param integer $el ID equipo local
 * @param integer $ev ID equipo visitante
 * @param integer $gl Goles local
 * @param integer $gv Goles visitante
 * @param integer $tal TA local
 * @param integer $tav TA visitante
 * @param integer $trl TR local
 * @param integer $trv TR visitante
 * @param boolean $pr Prórroga
 * @param boolean $pen Penaltis
 * @param integer $ganp ID equipo ganador de penaltis
 * @return boolean Operacion realizada
 */
function registrarPartido($conn, $ed, $tipo, $ul, $uv, $el, $ev, $gl, $gv, $tal, $tav, $trl, $trv, $pr, $pen, $ganp){
    $query = "SELECT count(*) as cuenta from partido;";
    $result = mysqli_query($conn, $query);
    //Número de partido en total
    $idp = mysqli_fetch_assoc($result)["cuenta"] + 1;
    
    $query = "SELECT count(*) as cuenta from partido where edicion = $ed;";
    $result = mysqli_query($conn, $query);
    //Número d epartido en esta edición
    $num_ed = mysqli_fetch_assoc($result)["cuenta"] + 1;
    
    //Insertamos el partido y guardamos en log
    $queryInsert = "INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES "
            . "($idp, $ed, '$tipo', $num_ed, $pr, $pen, $ganp);";
    guardarEnLog($queryInsert);
    mysqli_query($conn, $queryInsert);
    //Insertamos los marcadores y guardamos en log
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

/**
 * Registra los usuarios por defecto (Miguel, Javi, Chechu).
 * @param \mysqli $conn Conexión con la base de datos.
 * @return boolean Registro exitoso.
 */
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

/**
 * Registra la edición con la fecha actual y la elección de los jugadores.
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $edicion Número de edición
 * @param string $em Nombre del equipo seleccionado por Miguel.
 * @param string $ej Nombre del equipo seleccionado por Javi.
 * @param string $ec Nombre del equipo seleccionado por Chechu.
 * @return boolean Inserción exitosa
 */
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

/**
 * Añade al fichero de log las consultas que se le pasan.
 * @param string $query Consulta a guardar.
 */
function guardarEnLog($query){
    $myfile = file_put_contents('log.sql', $query.PHP_EOL , FILE_APPEND | LOCK_EX);
}

/**
 * Añade al fichero de registro de equipos la consulta con la que se registro.
 * @param string $query Consulta para guardar un equipo
 */
function guardarEnLogEquipo($query){
    $myfile = file_put_contents('insertEquipos.sql', $query.PHP_EOL , FILE_APPEND | LOCK_EX);
}

/**
 * Crea el esquema en la base de datos (que debe estar vacía).
 * @param \mysqli $conn Conexión con la BD.
 */
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
    //Por el momento, no se generan las restricciones porque está en pruebas.
}

/**
 * Lista las opciones en select de HTML con las estadísticas.
 * @param \mysqli $conn Conexión con la BD.
 */
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

/**
 * Lista todos los partidos en función de la edición
 * @param \mysqli $conn Conexión con la BD.
 */
function listaTodosPartidos ($conn){
    $ned = getNumeroEdiciones($conn);
    
    //Creamos select para que se pueda seleccionar por edición, en lugar de
    //mostrar todos de golpe.
    echo "<select onchange=\"ver_stats_partidos(this, $ned)\">";
    echo "<option value=\"null\">Seleccione una edición</option>";
    for ($i=1; $i <= $ned; $i++){
        echo "<option value=\"".$i."\">".$i."ª</option>";
    }
    echo "</select>";
    echo "</p>";
    
    
    echo "<p>";
    for ($i=1; $i <= $ned; $i++){
        
        echo "<div id=\"stats_partidos_$i\" style=\"display: none;\">";
            echo "<h2>Edición ".$i."ª</h2>";
            //Mostramos en cada div los partidos de cada edición
            getTablaPartidosEdicion($conn, $i);
        echo "</div>";
    }
    echo "</p>";
}

/**
 * Genera todas las clasificaciones en HTML separadas por edición.
 * @param \mysqli $conn Conexión con la BD.
 */
function listaTodasClasificaciones($conn){
    $ned = getNumeroEdiciones($conn);
    for ($i = 1; $i <= $ned; $i++){
            //Por cada edición, se genera un div para cambiar en función de la edición.
        ?>
            <div id="stats_clasificaciones_<?= $i ?>" style="display: none;">
                Edición <?= $i ?>ª<br>
                <?php printClasificacion($conn, $i); ?>
            </div>
        <?php
    }
}

/**
 * Muestra las estadísticas generales de un jugador.
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $jugador ID del jugador
 */
function estadisticasJugador($conn, $jugador){
    $pj = getPJUsuario($conn, $jugador);    //Partidos jugados
    $ta = getTAUsuario($conn, $jugador);    //Tarjetas amarillas
    $tr = getTRUsuario($conn, $jugador);    //Tarjetas rojas
    $pg = getPGUsuario($conn, $jugador);    //Victorias
    $pe = getPEUsuario($conn, $jugador);    //Empates
    $pp = getPPUsuario($conn, $jugador);    //Derrotas
    $gf = getGFUsuario($conn, $jugador);    //Goles a favor
    $gc = getGCUsuario($conn, $jugador);    //Goles en contra
    $pts = $pg * 3 + $pe;                   //PTS totales (incluidas finales)
    $dg = $gf - $gc;                        //Diferencia de goles
    $tpg = getPENGUsuario($conn, $jugador); //Tandas de penaltis ganadas
    
    ?>
<h1><!-- Nombre del usuario --><?= getUsuarioFromID($conn, $jugador) ?></h1>
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
        <td id="td_ucl_blue">Finales</td>
        <td id="td_ucl_white"><?= getFinalesUsuario($conn, $jugador) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Prórrogas</td>
        <td id="td_ucl_white"><?= getPRUsuario($conn, $jugador) ?></td>
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

/**
 * Muestra todas las estadísticas de un usuairo
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $usuario ID del usuario
 */
function estadisticasUsuario($conn, $usuario){
    //Panel por cada usuario
    echo "<div id=\"stats_jugadores_$usuario\" style=\"display: none;\">";
    //Cogemos imagen de usuario
    getImagenUsuario($usuario, 1.5);
    ?>
        <p><?php estadisticasJugador($conn, $usuario); ?></p>
        <p><?php equiposSeleccionadosUsuario($conn, $usuario); ?></p>
        <p><?php finalesUsuario($conn, $usuario); ?></p>
        <p><?php golesEncajadosUsuarioEdicion($conn, $usuario); ?></p>
    <?php
    echo "</div>";
}

/**
 * Estadísticas generales de un equipo
 * @param \mysqli $conn Conexión con BD.
 * @param integer $equipo ID del equipo
 */
function estadisticasEquipo($conn, $equipo){
    $pj = getPJEquipo($conn, $equipo);  //Partidos jugados
    $ta = getTAEquipo($conn, $equipo);  //Tarjetas amarillas
    $tr = getTREquipo($conn, $equipo);  //Tarjetas rojas
    $pg = getPGEquipo($conn, $equipo);  //Victorias
    $pe = getPEEquipo($conn, $equipo);  //Empates
    $pp = getPPEquipo($conn, $equipo);  //Derrotas
    $gf = getGFEquipo($conn, $equipo);  //Goles a favor
    $gc = getGCEquipo($conn, $equipo);  //Goles en contra
    $pts = $pg * 3 + $pe;               //Puntos totales
    $dg = $gf - $gc;                    //Diferencia de goles
    $tpg = getPENGEquipo($conn, $equipo);   //Tandas de penaltis ganadas
    
    ?>
        <?php getImagenEquipoID($conn, $equipo, 0.5); ?>
<h1><?= getNombreEquipo($conn, $equipo) ?></h1>
<table cellpadding="-1">
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
        <td id="td_ucl_blue">Finales</td>
        <td id="td_ucl_white"><?= getFinalesEquipo($conn, $equipo) ?></td>
    </tr>
    <tr>
        <td id="td_ucl_blue">Prórrogas</td>
        <td id="td_ucl_white"><?= getPREquipo($conn, $equipo) ?></td>
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

/**
 * Muestra las estadísticas de todos los equipos (separadas por div)
 * @param \mysqli $conn Conexión con BD.
 */
function estadisticasEquiposTotal($conn){
    $query = "select id,nombre from equipo order by nombre asc;";
    $result = mysqli_query($conn, $query);
    
    //Creamos un select con todos los equipos (para mostrar solo estadísticas de uno.
    echo "<select onchange=\"ver_stats_equipos(this, ". mysqli_num_rows($result).")\">";
    echo "<option value=\"null\">Seleccione un equipo</option>";
    while($row = mysqli_fetch_assoc($result))
    {
        echo "<option value=\"".$row["id"]."\">".$row["nombre"]."</option>";
    }
    echo "</select>";
    
    //Realizamos de nuevo la consulta
    $result = mysqli_query($conn, $query);
    
    //Por cada equipo, genera un panel con las estadísticas.
    while($row = mysqli_fetch_assoc($result)){
        $equipo = $row["id"];
        
        echo "<div id=\"stats_equipos_$equipo\" style=\"display: none;\">";
        
        estadisticasEquipo($conn, $equipo);
        ?>
        <p><?php golesEquipoEdicionUsuario($conn, $equipo); ?></p>
        <?php
        
        echo "</div>";
    }
}

/**
 * Muestra todas las estadísticas de la competición
 * @param \mysqli $conn Conexión con la BD.
 */
function estadisticasCompeticion($conn){
        //Creamos un select con todas las opciones
    ?>
        <select onchange="ver_stats_competicion(this)">
            <option value="null">Seleccione una tabla</option>
            <option value="stats_competicion_fechas">Fechas Ediciones</option>
            <option value="stats_competicion_seleccionados">Equipos seleccionados</option>
            <option value="stats_competicion_goles_partido">Partidos con más goles</option>
            <option value="stats_competicion_goles_edicion">Ediciones con más goles</option>
            <option value="stats_competicion_palmares_equipo_usuario">Palmarés Equipo + Jugador</option>
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

/**
 * Devuelve todos los escudos de los equipos para la página del sorteo.
 * @param \mysqli $conn Conexión con la BD.
 */
function getEquiposSorteo($conn){
    $query = "SELECT id FROM equipo;";
    $result = mysqli_query($conn, $query);
    while($imgData = mysqli_fetch_assoc($result)){
            //Tamaño adaptado para que se vea correctamente.
        ?>
            <div><?php getImagenEquipoID($conn, $imgData["id"], 0.33) ?></div>
        <?php
    }
    mysqli_free_result($result);
}