<?php

function getNumeroEdiciones($conn){
    if ($conn){
        $query = "SELECT count(*) as cuenta FROM edicion;";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row["cuenta"];
    }else{
        return -1;
    }
}

function getImagenEquipoID($conn, $w = 305, $h = 305, $id = null){
    $query = null;
    if($id){
        $query = "SELECT imagen FROM equipo WHERE id = ".$id.";";
    }else{
        $query = "SELECT imagen FROM equipo;";
    }
    $result = mysqli_query($conn, $query);
    if ($result){
        while($imgData = mysqli_fetch_assoc($result)){
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
        echo "<table border=\"1\">";
        echo "<tr><i>Equipos registrados</i></tr>";
        $query = "SELECT id, nombre FROM equipo order by nombre;";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr><td>";
            echo $row["id"];
            echo "<tr><td>";
            getImagenEquipoID($conn, 40, 40, $row["id"]);
            echo "</td><td>";
            echo $row["nombre"];
            echo "</td></tr>";
        } 
        echo "</table>";
        mysqli_free_result($result); 
    }else{
        return "";
    }
}

function getTablaPartidosEdicion($conn, $edicion){
    if ($conn){
        echo "<table>";
        $query = "SELECT * FROM partido where edicion = $edicion;";
        $result = mysqli_query($conn, $query);
        // IDP, Tipo, #Num_ED, el, gl, gv, ev, PR, PEN, Ganador, Penaltis
        //id, tipo, num_ed, - , prorroga, penaltis, ganador_penaltis
        echo "<tr>";
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
        echo "<td>Ganador (Penaltis)</td></tr>";
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["tipo"]."</td>";
            echo "<td>".$row["num_ed"]."</td>";
            
            
            $query2 = "SELECT * FROM marcador where partido = ".$row["id"]." and local = 1;";
            $result2 = mysqli_query($conn, $query2);
            $fila = mysqli_fetch_assoc($result2);
            echo "<td>";
            getImagenEquipoID($conn, 50, 50, $fila["equipo"]);
            echo "</td>";
            echo "<td>". getNombreEquipo($conn, $fila["equipo"]) ."</td>";
            echo "<td>".$fila["goles"]."</td>";
            
            $query3 = "SELECT * FROM marcador where partido = ".$row["id"]." and local = 0;";
            $result3 = mysqli_query($conn, $query3);
            $fila2 = mysqli_fetch_assoc($result3);
            echo "<td>".$fila2["goles"]."</td>";
            echo "<td>". getNombreEquipo($conn, $fila2["equipo"]) ."</td>";
            echo "<td>";
            getImagenEquipoID($conn, 50, 50, $fila2["equipo"]);
            echo "</td>";
            if ($row["prorroga"]) { echo "<td>Sí</td>"; }
            if ($row["penaltis"]) {
                echo "<td>Sí</td>";
                echo "<td>".getNombreEquipo($conn, $row["ganador_penaltis"])."</td>";
            }
            echo "</tr>";
        } 
        echo "</table>";
        mysqli_free_result($result); 
    }else{
        return "";
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
    // PTS[0], V[1], E[2], D[3], GF[4], GC[5], DG[6], TA[7], TR[8]
    //$m = [0,0,0,0,0,0,0,1];
    $m = array_fill(0, 9, 0);
    $m[9] = 1;
    $j = array_fill(0, 9, 0);
    $j[9] = 2;
    $c = array_fill(0, 9, 0);
    $c[9] = 3;
    array_push($m, 1);
    array_push($j, 2);
    array_push($c, 3);
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
    return ordenarClasificacion($jug);
    //return $jug;
}

function printClasificacion($conn, $edicion){
    if($conn){
        $cl = getClasificacion($conn, $edicion);
        ?>
            <table border="1">
                <tr>
                    <td>POS</td>
                    <td>Usuario</td>
                    <td>PTS</td>
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
            ?>
                
                <tr>
                    <td> <?= $i+1 ?> </td>
                    <td><?= getUsuarioFromID($conn, $cl[$i][9]) ?> </td>
                    <td><?= $cl[$i][0] ?> </td>
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