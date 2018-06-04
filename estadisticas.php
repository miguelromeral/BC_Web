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

listaOpciones($conn);

?>
        <div id="stats_partidos" style="display: none;">
            <h1>Partidos por Edición</h1>
            <?php 
            listaTodosPartidos($conn);
            ?>
        </div>
        <div id="stats_clasificaciones" style="display: none;">
            <h1>Clasificación por Edición</h1>
            
            <?php
            
            $ned = getNumeroEdiciones($conn);
            echo "<select onchange=\"ver_stats_clasificacion(this, $ned)\">";
            echo "<option value=\"null\">Seleccione una edición</option>";
            for ($i=1; $i <= $ned; $i++){
                echo "<option value=\"".$i."\">".$i."ª</option>";
            }
            echo "</select>";
            echo "</p>";
            
            //listaEdiciones($conn, "ver_stats_clasificacion");
            listaTodasClasificaciones($conn);
            ?>
        </div>
        <div id="stats_competicion" style="display: none;">
            <h1>Estadísticas Competición</h1>
            <?php  estadisticasCompeticion($conn); ?>
        </div>
        <div id="stats_jugadores" style="display: none;">
            <h1>Estadísticas Jugador</h1>
            <?php  for($i = 1; $i < 4; $i++){ estadisticasUsuario($conn, $i); }?>
        </div>
        <div id="stats_equipos" style="display: none;">
            <h1>Estadísticas Equipo</h1>
            <?php estadisticasEquiposTotal($conn); ?>
        </div>
</center></body>
</html>
