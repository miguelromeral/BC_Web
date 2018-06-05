<?php

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

function getImagenEquipoNombre($conn, $nombre, $w = 305, $h = 305){
    $query = "SELECT imagen FROM equipo WHERE nombre = '$nombre';";
    $result = mysqli_query($conn, $query);
    if ($result){
        $imgData = mysqli_fetch_assoc($result);
        echo '<img src="data:image/png;base64,'.base64_encode( $imgData['imagen'] ).'" width="'.$w.'" height="'.$h.'"/>';
        mysqli_free_result($result); 
    }
}


function getUsuarioFromID($conn, $id){
    $query = "SELECT nombre FROM usuario WHERE id= $id;";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["nombre"];
    }
}

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

function getTablaPartidosEdicion($conn, $edicion){
    if ($conn){
        echo "<table id=\"tabla_partidos\">";
        $query = "SELECT * FROM partido where edicion = $edicion;";
        $result = mysqli_query($conn, $query);
        // IDP, Tipo, #Num_ED, el, gl, gv, ev, PR, PEN, Ganador, Penaltis
        //id, tipo, num_ed, - , prorroga, penaltis, ganador_penaltis
        /*echo "<tr>";
        echo "<td>#Partido</td>";
        echo "<td>Tipo</td>";
        echo "<td>#Partido Ed.</td>";
        echo "<td>Local (S)</td>";
        echo "<td>Local</td>";
        echo "<td>GL</td>";
        echo "<td>GV</td>";
        echo "<td>Visitante</td>";
        echo "<td>Visitante (S)</td>";
        echo "<td>Prórroga</td>";
        echo "<td>Penaltis</td>";
        echo "<td>Ganador (Penaltis)</td></tr>";*/
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
            }/*else{
                echo "<td style=\" opacity: 0.0; \">---------------------------------</td>";
            }*/
            echo "</tr>";
        } 
        mysqli_free_result($result); 
    }
}

function getIDEquipo($conn, $equipo){
    $query = "SELECT id FROM equipo WHERE nombre = '$equipo';";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["id"]; 
    }
    return -1;
}


function getNumeroEquipos($conn){
    $query = "SELECT count(id) as cuenta FROM equipo;";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["cuenta"]; 
    }
    return "";
}

function getNombreEquipo($conn, $id){
    $query = "SELECT nombre FROM equipo WHERE id = $id;";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["nombre"]; 
    }
    return "";
}

function getIDUsuario($conn, $usuario){
    $query = "SELECT id FROM usuario WHERE nombre = '$usuario';";
    $result = mysqli_query($conn, $query);
    if ($result){
        $row = mysqli_fetch_assoc($result); 
        return $row["id"]; 
    }
    return -1;
}

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


function cambiarFilas($cl, $i, $j){
    $aux = $cl[$i];
    $cl[$i] = $cl[$j];
    $cl[$j] = $aux;
    return $cl;
}

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

function ordenarClasificacion($cl){
    $cl = ordenarDosFilasClasificacion($cl, 0, 1);
    $cl = ordenarDosFilasClasificacion($cl, 1, 2);
    $cl = ordenarDosFilasClasificacion($cl, 0, 1);
    $cl = ordenarDosFilasClasificacion($cl, 1, 2);
    return $cl;
}

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

function getEquipoPorUsuarioEdicion($conn, $usuario, $edicion){
    $query = "select nombre from bc.equipo as e inner join ( select equipo from bc.eleccion where edicion = $edicion and usuario = $usuario ) as n on n.equipo = e.id;";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)["nombre"];
}