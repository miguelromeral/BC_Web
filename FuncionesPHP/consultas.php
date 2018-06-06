<?php
/**
 * Número de ediciones disputadas
 * @param \mysqli $conn Conexión con la BD
 * @return integer Número de ediciones dispoutadas
 */
function getNumeroEdiciones($conn){
    if ($conn){
        $query = "SELECT count(*) as cuenta FROM bc.edicion;";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row["cuenta"];
    }else{
        return -1;
    }
}
/**
 * Muestra el escudo de un equipo
 * @param \mysqli $conn Conexión con la BD
 * @param integer $id ID del equipo
 * @param float $pc Porcentaje de escala la imagen
 */
function getImagenEquipoID($conn, $id = null, $pc = 1){
    $query = null;
    if($id){
        $query = "SELECT imagen FROM equipo WHERE id = ".$id.";";
    }else{
        $query = "SELECT imagen FROM equipo;";
    }
    $result = mysqli_query($conn, $query);
    if ($result){
        while($imgData = mysqli_fetch_assoc($result)){
            $w = 305 * $pc;
            $h = 305 * $pc;
            echo '<img src="data:image/png;base64,'.base64_encode( $imgData['imagen'] ).'" width="'.$w.'" height="'.$h.'"/>';
        }
        mysqli_free_result($result); 
    }
}
/**
 * Muestra la imagen de un equipo por nombre
 * @param \mysqli $conn Conexión con la BD
 * @param string $nombre Nombre del equipo
 * @param integer $w Ancho
 * @param integer $h Alto
 */
function getImagenEquipoNombre($conn, $nombre, $w = 305, $h = 305){
    $query = "SELECT imagen FROM equipo WHERE nombre = '$nombre';";
    $result = mysqli_query($conn, $query);
    if ($result){
        $imgData = mysqli_fetch_assoc($result);
        echo '<img src="data:image/png;base64,'.base64_encode( $imgData['imagen'] ).'" width="'.$w.'" height="'.$h.'"/>';
        mysqli_free_result($result); 
    }
}

/**
 * Devuelve el nombre de usuario dado su ID
 * @param \mysqli $conn Conexión con la BD
 * @param integer $id ID del usuario
 * @return string Nombre del usuario
 */
function getUsuarioFromID($conn, $id){
    $query = "SELECT nombre FROM usuario WHERE id= $id;";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["nombre"];
    }
}

/**
 * Muestra en una tabla la lista de equipos registrados
 * @param \mysqli $conn Conexión con la BD
 * @return string
 */
function getTablaEquiposRegistrados($conn){
    if ($conn){
        echo "<table>";
        echo "<tr><i>Equipos registrados</i></tr>";
        $query = "SELECT id, nombre FROM equipo order by nombre;";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr><td id=\"td_ucl_blue\">";
            //echo $row["id"];
            //echo "</td><td>";
            getImagenEquipoID($conn, $row["id"], 0.16);
            echo "</td><td id=\"td_ucl_white\">";
            echo $row["nombre"];
            echo "</td></tr>";
        } 
        echo "</table>";
        mysqli_free_result($result); 
    }else{
        return "";
    }
}
/**
 * Muestra la imagen del usuario dado su ID
 * @param integer $id ID del usuario
 * @param float $pc porcentaje de escala
 */
function getImagenUsuario($id, $pc = 1){
    $w = 175 * $pc;
    $h = 253 * $pc;
    $nombre = "silueta";
    switch($id){
        case 1: $nombre = "Miguel"; break;
        case 2: $nombre = "Javi"; break;
        case 3: $nombre = "Chechu"; break;
        default: $nombre = "silueta";
    }
    echo "<img src=\"Imagenes/foto_$nombre.png\" width=\"$w\" height=\"$h\"/>";
}
/**
 * Muestra en una tabla los partidos disputados en esa edición
 * @param \mysqli $conn Conexión con laB BD.
 * @param integer $edicion Edición
 * @return string
 */
function getTablaPartidosEdicion($conn, $edicion){
    if ($conn){
        echo "<table id=\"tabla_partidos\">";
        $query = "SELECT * FROM partido where edicion = $edicion;";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result))
        {
            getFilaPartido($conn, $row["id"]);
        } 
        echo "</table>";
        mysqli_free_result($result); 
    }else{
        return "";
    }
}
/**
 * Devuelve una fila con la información del partido
 * @param \mysqli $conn Conexión con la BD
 * @param integer $id ID del partido
 */
function getFilaPartido($conn, $id){
    if ($conn){
        $query = "SELECT * FROM partido where id = $id;";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result))
        {
            $id = $row["id"];
            $edicion = $row["edicion"];
            $tipo = $row["tipo"];
            $num_ed = $row["num_ed"];
            echo "<tr>";
            if($tipo == "Final"){
                echo "<td id=\"td_ucl_blue\" colspan=\"7\"> Partido ".$id." - Ed. ".$edicion."ª - ".$tipo." </td>";
            }else{
                echo "<td id=\"td_ucl_blue\" colspan=\"7\"> Partido ".$id." - Ed. ".$edicion."ª - ".$tipo." (".$num_ed."º) </td>";
            }
            echo "</tr><tr>";
            
            
            $query2 = "SELECT * FROM marcador where partido = ".$row["id"]." and local = 1;";
            $result2 = mysqli_query($conn, $query2);
            $fila = mysqli_fetch_assoc($result2);
            
            echo "<td id=\"p_td\">";
            getImagenUsuario($fila["usuario"], 0.25);
            echo "</td>";
            echo "<td id=\"p_td\">";
            getImagenEquipoID($conn, $fila["equipo"], 0.2);
            echo "</td>";
            echo "<td id=\"p_td_equipo\">". getNombreEquipo($conn, $fila["equipo"]) ."</td>";
            
            $gl = $fila["goles"];
            
            $query3 = "SELECT * FROM marcador where partido = ".$row["id"]." and local = 0;";
            $result3 = mysqli_query($conn, $query3);
            $fila2 = mysqli_fetch_assoc($result3);
            
            $gv = $fila2["goles"];
            
            echo "<td id=\"marcador_g\">".$gl." - ".$gv."</td>";
            echo "<td id=\"p_td_equipo\">". getNombreEquipo($conn, $fila2["equipo"]) ."</td>";
            echo "<td id=\"p_td\">";
            getImagenEquipoID($conn, $fila2["equipo"], 0.2);
            echo "</td>";
            echo "<td id=\"p_td\">";
            getImagenUsuario($fila2["usuario"], 0.25);
            echo "</td>";
            if ($row["prorroga"]) { echo "<td id=\"p_td\">PR</td>"; } else { echo "<td>  </td>"; }
            if ($row["penaltis"]) {
                echo "<td id=\"p_td\">Ganó (P): ";
                getImagenEquipoID($conn, $row["ganador_penaltis"], 0.16); 
                echo "</td>";
            }
            echo "</tr>";
        } 
        mysqli_free_result($result); 
    }
}
/**
 * Obtiene el ID de un equipo dado su nombre
 * @param \mysqli $conn Conexión con la BD.
 * @param string $equipo Nombre del equipo
 * @return integer ID del equipo
 */
function getIDEquipo($conn, $equipo){
    $query = "SELECT id FROM equipo WHERE nombre = '$equipo';";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["id"]; 
    }
    return -1;
}

/**
 * Devuelve el número de equipos registrados
 * @param \mysqli $conn Conexión con la BD
 * @return integer Nñumero de equipos registrados
 */
function getNumeroEquipos($conn){
    $query = "SELECT count(id) as cuenta FROM equipo;";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["cuenta"]; 
    }
    return "";
}
/**
 * Devuelve le nombre del equipo dado su ID
 * @param \mysqli $conn Conexión con la BD
 * @param integer $id ID del equipo
 * @return string Nombre del equipo
 */
function getNombreEquipo($conn, $id){
    $query = "SELECT nombre FROM equipo WHERE id = $id;";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["nombre"]; 
    }
    return "";
}
/**
 * Obtiene el ID del usuario dado su nombre
 * @param \mysqli $conn Conexión con la BD
 * @param string $usuario Nombre de usuario
 * @return integer ID del usuario
 */
function getIDUsuario($conn, $usuario){
    $query = "SELECT id FROM usuario WHERE nombre = '$usuario';";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["id"]; 
    }
    return -1;
}
/**
 * Añade los datos dado un partido entre dos filas de clasificación tras un partido
 * @param array $jug Clasificación
 * @param type $yo Equipo al que se le añaden los valores
 * @param type $rival Equipo rival
 * @param type $i Posición de "yo" en la tabla
 * @return array Clasificación con los datos añadidos
 */
function anadirDatosMarcador($jug, $yo, $rival, $i){
    $gf = $yo["goles"];
    $gc = $rival["goles"];
    $jug[$i][4] += $gf;
    $jug[$i][5] += $gc;
    $jug[$i][6] += ($gf - $gc);
    $jug[$i][7] += $yo["ta"];
    $jug[$i][8] += $yo["tr"];
    if ($gf > $gc){
        $jug[$i][1] ++;
        $jug[$i][0] += 3;
    }elseif ($gf < $gc){
        $jug[$i][3] ++;
    }else{
        $jug[$i][2] ++;
        $jug[$i][0] ++;
    }
    return $jug;
}

/**
 * Intercambia dos filas en la clasificación
 * @param array $cl Clasificación
 * @param array $i Fila 1
 * @param array $j Fila 2
 * @return array Clasificación con ese intercambio
 */
function cambiarFilas($cl, $i, $j){
    $aux = $cl[$i];
    $cl[$i] = $cl[$j];
    $cl[$j] = $aux;
    return $cl;
}

/**
 * Ordena dos filas de la clasificación
 * @param array $cl Clasificación total
 * @param array $p Equipo superior en la tabla
 * @param array $s Equipo inferior en la tabla
 * @return array Clasificación con cambio de posición si fuera necesario
 */
function ordenarDosFilasClasificacion($cl, $p, $s){
    $cambio = false;
    $prim = $cl[$p];
    $seg = $cl[$s];
    
    // PTS[0], V[1], E[2], D[3], GF[4], GC[5], DG[6], TA[7], TR[8]
    if ($prim[0] < $seg[0]){
        $cambio = true;
    }elseif($prim[0] == $seg[0]){
        if($prim[6] < $seg[6]){
            $cambio = true;
        }elseif($prim[6] == $seg[6]){
            if($prim[4] < $seg[4]){
                $cambio = true;
            }elseif($prim[4] == $seg[4]){
                $pts_neg_prim = $prim[7] + $prim[8] * 2;
                $pts_neg_seg = $seg[7] + $seg[8] * 2;
                if($pts_neg_prim > $pts_neg_seg){
                    $cambio = true;
                }
            }
        }
    }
    
    if ($cambio){
        $cl = cambiarFilas($cl, $p, $s);
    }
    return $cl;
}

/**
 * Ordena la clasificación en función de unos determinados criterios
 * @param array $cl Clasificación
 * @return array Clasificación ordenada
 */
function ordenarClasificacion($cl){
    $cl = ordenarDosFilasClasificacion($cl, 0, 1);
    $cl = ordenarDosFilasClasificacion($cl, 1, 2);
    $cl = ordenarDosFilasClasificacion($cl, 0, 1);
    $cl = ordenarDosFilasClasificacion($cl, 1, 2);
    return $cl;
}
/**
 * Retorna un array con la clasificación ordenada de una edición
 * @param \mysqli $conn Conexión con la BD
 * @param integer $edicion Edición
 * @return array Clasificación de la edición
 */
function getClasificacion($conn, $edicion){
    // PTS[0], V[1], E[2], D[3], GF[4], GC[5], DG[6], TA[7], TR[8], Usuario[9], PJ[10], Equipo[11]
    //$m = [0,0,0,0,0,0,0,1];
    $m = array_fill(0, 10, 0);
    $m[9] = 1;
    $j = array_fill(0, 10, 0);
    $j[9] = 2;
    $c = array_fill(0, 10, 0);
    $c[9] = 3;
    array_push($m, 1, getEquipoPorUsuarioEdicion($conn, 1, $edicion));
    array_push($j, 2, getEquipoPorUsuarioEdicion($conn, 2, $edicion));
    array_push($c, 3, getEquipoPorUsuarioEdicion($conn, 3, $edicion));
    $jug = [$m, $j, $c];
    $query = "SELECT id FROM partido WHERE edicion = $edicion and tipo = 'Fase de Grupos';";
    $result = mysqli_query($conn, $query);
    while($partido = mysqli_fetch_assoc($result))
    {
        for ($i=0; $i<3; $i++){ 
            $q2 = "SELECT * FROM marcador WHERE partido = ".$partido["id"].";";
            $r1 = mysqli_query($conn, $q2);
            $l = mysqli_fetch_assoc($r1);
            $v = mysqli_fetch_assoc($r1);
            if ($l["usuario"] == ($i + 1)){
                $jug = anadirDatosMarcador($jug, $l, $v, $i);
            }elseif ($v["usuario"] == ($i + 1)){
                $jug = anadirDatosMarcador($jug, $v, $l, $i);
            }
        }
    }
    for ($i=0; $i<3; $i++){ 
        $jug[$i][10] = $jug[$i][1] + $jug[$i][2] + $jug[$i][3];
    }
    return ordenarClasificacion($jug);
    //return $jug;
}

/**
 * Muestra en una tabla la clasificación
 * @param \mysqli $conn Conexión con la BD
 * @param integer $edicion Edición
 * @return string 
 */
function printClasificacion($conn, $edicion){
    if($conn){
        $cl = getClasificacion($conn, $edicion);
        ?>
            <table border="1" id="tabla_clasificacion">
                <tr id="c_tr_head">
                    <td>POS.</td>
                    <td>Usuario</td>
                    <td>Equipo</td>
                    <td>PTS</td>
                    <td>PJ</td>
                    <td>V</td>
                    <td>E</td>
                    <td>D</td>
                    <td>GF</td>
                    <td>GC</td>
                    <td>DG</td>
                    <td>TA</td>
                    <td>TR</td>
                </tr>
        <?php
        for ($i=0; $i<3; $i++){ 
            if ($i < 2){
                echo "<tr id=\"c_tr_in\">";
            }else{
                echo "<tr id=\"c_tr_out\">";
            }
            
            ?>
                    <td> <?= $i+1 ?>º </td>
                    <td><?= getUsuarioFromID($conn, $cl[$i][9]) ?> </td>
                    <td><?= $cl[$i][11]?> </td>
                    <td id="c_td_pts"><?= $cl[$i][0] ?> </td>
                    <td><?= $cl[$i][10] ?> </td>
                    <td><?= $cl[$i][1] ?> </td>
                    <td><?= $cl[$i][2] ?> </td>
                    <td><?= $cl[$i][3] ?> </td>
                    <td><?= $cl[$i][4] ?> </td>
                    <td><?= $cl[$i][5] ?> </td>
                    <td><?= $cl[$i][6] ?> </td>
                    <td><?= $cl[$i][7] ?> </td>
                    <td><?= $cl[$i][8] ?> </td>
                </tr>
            <?php
        }
        echo "</table>";
    }else{
        return "";
    }
}
/**
 * Devuelve el nombre del equipo usado por un usuario en una edición.
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @param integer $edicion Edición
 * @return string Nombre del equipo
 */
function getEquipoPorUsuarioEdicion($conn, $usuario, $edicion){
    $query = "select nombre from bc.equipo as e inner join ( select equipo from bc.eleccion where edicion = $edicion and usuario = $usuario ) as n on n.equipo = e.id;";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)["nombre"];
}