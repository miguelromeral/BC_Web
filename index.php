<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
         <script src="Scripts/checker.js"></script> 
    </head>
    <body>
<?php

include 'FuncionesPHP/funciones.php';
//Quitamos Warning
//error_reporting(E_ERROR | E_PARSE);

$host = 'localhost';
$database = 'bc';
$user = 'root';
$password = '';
$conn = null;
try{
    $conn = new mysqli($host,$user,$password);
    $conn->select_db($database);
       
        
?>
        
        <h1>Brother's Cup PHP</h1>
        
<?php
if ($conn){
    $query = "SELECT count(*) as cuenta FROM edicion;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $ne = $row["cuenta"] + 1;
    echo "<h2>Edición ".$ne."</h2>";
}else{
    echo "<p>Error en la Base de Datos</p>";
}
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}
 
if($_POST)
{
    $nuevo = $_POST['nombre_equipo_nuevo'];
    if (strlen($nuevo) <= 20 && strlen($nuevo) > 0){
        $query = "SELECT count(*) as cuenta from equipo where nombre = \"".$nuevo."\";";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row["cuenta"] == 0){
            $query = "SELECT count(*) as cuenta FROM equipo;";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $idn = $row["cuenta"] + 1;
            $queryInsert = "INSERT INTO equipo (id, nombre) VALUES ('".$idn."', '".$nuevo."');";

            $resultInsert = mysqli_query($conn, $queryInsert); 
            if($resultInsert){
               echo "<strong>Registrado el equipo ".$nuevo." correctamente</strong>. <br>";
            }
            else{
               echo "No se ingresaron los registros. <br>";
            }
        }else
            echo "El equipo ".$nuevo." ya está registrado.";
    }else{
        echo "El equipo debe estar entre 1 y 20 caracteres.<br>";
    }
               
}






listaEquipos("Miguel");
listaEquipos("Javi");
listaEquipos("Chechu");

?>
    
        <br>
        <form name="fequipos" action="principal.php" onsubmit="return validar_eleccion_equipos()">
            <input type="submit" value="COMENZAR">
        </form>
        
        <h3>Registrar Equipo</h3>
        <form action="" method="post">
            Equipo: <input type="text" name="nombre_equipo_nuevo">
         <input type="submit" value="Registrar">
      </form>
         
    </body>
</html>
