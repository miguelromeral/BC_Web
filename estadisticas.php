<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
         <script src="Scripts/checker.js"></script> 
         <link rel="stylesheet" type="text/css" href="Estilos/estilos.css">
    </head>
    <body><center>
       
<?php
include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';
$conn = conectarse();

$ne = getNumeroEdiciones($conn);
listaEdiciones($conn);
?>
        <h2>Partidos por Edición</h2>
        <?php listaTodosPartidos($conn); ?>
        <h2>Clasificación por Edición</h2>
        <?php listaTodasClasificaciones($conn); ?>
        <h2>Estadísticas Jugador</h2>
        <?php for($i = 1; $i < 4; $i++){ estadisticasJugador($conn, $i); }?>

       
</center></body>
</html>
