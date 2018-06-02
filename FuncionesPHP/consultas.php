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
        echo "<table border=\"1\">";
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


