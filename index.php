<!DOCTYPE html>
<?php 

session_start();

if(@$_GET['salir']=="true")
{
    $_SESSION['en_curso'] == false;
}


?>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
         <script src="Scripts/checker.js"></script> 
    </head>
    <body>
<?php
   if(@$_SESSION['en_curso'] == true){
?>
      <p>
      Ya hay una edición en curso. 
      <form action="principal.php" method="get">
            <button type="submit">IR AL PANEL PRINCIPAL</button>
        </form>
      </p>
<?php 
   }else{
       




include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';
//Quitamos Warning
//error_reporting(E_ERROR | E_PARSE);

$conn = conectarse();
$_SESSION['en_partido'] = false;
        
?>
        
        <h1>Brother's Cup PHP</h1>
        
<?php

$ne = getNumeroEdiciones($conn);
if ($ne != -1){
    echo "<h2>Edición ".($ne + 1)."ª</h2>";
    $_SESSION['edicion'] = $ne + 1;
    if ($ne == 0){
        registrarUsuarios($conn);
    }
}else{
    echo "ERROR EN LA BASE DE DATOS";
}


if($_POST)
{
    //Registramos el equipo
    $check = getimagesize($_FILES["imagen_equipo_nuevo"]["tmp_name"]);
    if($check !== false){
        $imgContent = addslashes(file_get_contents($_FILES['imagen_equipo_nuevo']['tmp_name']));
        $nuevo = $_POST['nombre_equipo_nuevo'];
        if(registrarEquipos($conn, $nuevo, $imgContent)){
            echo "<p><b>".strtoupper($nuevo)." REGISTRADO CON ÉXITO.</b></p>";
        }else{
            echo "<p><b>EQUIPO REPETIDO. NO SE HA REGISTRADO.</b></p>";
        }
    }else{
        echo "<p><b>DEBE ENVIAR UNA IMAGEN PARA PODER REGISTRAR EL EQUIPO.</b></p>";
    }
}

?>
        <form name="fequipos" action="principal.php" onsubmit="return validar_eleccion_equipos()" method="post">
       
            <?php
listaEquipos("Miguel");
listaEquipos("Javi");
listaEquipos("Chechu");
?>
    
            <input type="submit" value="COMENZAR">
        </form>
        
        <p>
       <?php getTablaEquiposRegistrados($conn); ?>
       </p>
       
         <h3>Registrar Equipo</h3>
        <form action="" method="post" enctype="multipart/form-data">
            Equipo: <input type="text" name="nombre_equipo_nuevo">
            Imagen (305x305): <input type="file" name="imagen_equipo_nuevo"/>
         <input type="submit" value="Registrar">
      </form>
       
   <?php } ?>
    </body>
</html>
