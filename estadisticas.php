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
include 'FuncionesPHP/queries_stats.php';
$conn = conectarse();

$ne = getNumeroEdiciones($conn);
listaEdiciones($conn);
?>
        <h1>Partidos por Edición</h1>
        <?php listaTodosPartidos($conn); ?>
        <h1>Clasificación por Edición</h1>
        <?php listaTodasClasificaciones($conn); ?>
        <h1>Estadísticas Jugador</h1>
        <?php for($i = 1; $i < 4; $i++){ estadisticasJugador($conn, $i); }?>
        <h1>Estadísticas Equipo</h1>
        <?php estadisticasEquiposTotal($conn); ?>

       
</center></body>
</html>
