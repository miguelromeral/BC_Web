<!DOCTYPE html>
<?php

if($_POST){
   if(@$_SESSION['en_curso'] != true){
      $_SESSION['en_curso'] = true;
      $_SESSION['miguel'] = $_POST['equipo_Miguel'];
      $_SESSION['javi'] = $_POST['equipo_Javi'];
      $_SESSION['chechu'] = $_POST['equipo_Chechu'];
   }
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

include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';
//Quitamos Warning
//error_reporting(E_ERROR | E_PARSE);

$conn = conectarse();
       
        
$em = $_SESSION['miguel'];
$ej = $_SESSION['javi'];
$ec = $_SESSION['chechu'];
?>
        <p>
    <center>
        <table cellspacing="30">
            <tr>
                <td colspan="2">
            <center><img src="Imagenes/foto_Miguel.png" width="175" height="253"/></center>
                </td>
                <td colspan="2">
            <center><img src="Imagenes/silueta.png" width="175" height="253"/></center>
                </td>
                <td colspan="2">
            <center><img src="Imagenes/silueta.png" width="175" height="253"/></center>
                </td>
            </tr>
            <tr>
                <td>
                    <?php getImagenEquipoNombre($conn, $em, 50, 50); ?>
                </td>
                <td>
                    <b><?=$em?></b>
                </td>
                
                <td>
                    <?php getImagenEquipoNombre($conn, $ej, 50, 50); ?>
                </td>
                
                <td>
                    <b><?=$ej?></b>
                </td>
                <td>
                    <?php getImagenEquipoNombre($conn, $ec, 50, 50); ?>
                </td>
                <td>
                    <b><?=$ec?></b>
                </td>
            </tr>
        

        </table>
    </center>
    </p>
        
    </body>
</html>
