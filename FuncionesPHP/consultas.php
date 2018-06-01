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

