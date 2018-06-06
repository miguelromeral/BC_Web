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
//Incluimos las funciones de PHP
include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';
include 'FuncionesPHP/queries_stats.php';

$conn = conectarse();
$ne = getNumeroEdiciones($conn);

//Listamos todas las opciones de estadísticas
listaOpciones($conn);

?>
        <!-- Panel para los partidos -->
        <div id="stats_partidos" style="display: none;">
            <h1>Partidos por Edición</h1>
            <?php 
            listaTodosPartidos($conn);
            ?>
        </div>
        <!-- Panel para las clasificaciones -->
        <div id="stats_clasificaciones" style="display: none;">
            <h1>Clasificación por Edición</h1>
            
            <?php
            
            //Generamos un select con todas las edicones
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
        <!-- Panel de la competición -->
        <div id="stats_competicion" style="display: none;">
            <h1>Estadísticas Competición</h1>
            <?php  estadisticasCompeticion($conn); ?>
        </div>
        <!-- Panel de los jugadores -->
        <div id="stats_jugadores" style="display: none;">
            <h1>Estadísticas Jugador</h1>
            <?php  
            
            //Creamos select con los nombres de todos los jugadores.
            $query = "select id,nombre from usuario order by id asc;";
            $result = mysqli_query($conn, $query);

            echo "<select onchange=\"ver_stats_jugadores(this, 3)\">";
            echo "<option value=\"null\">Seleccione un jugador</option>";
            while($row = mysqli_fetch_assoc($result))
            {
                echo "<option value=\"".$row["id"]."\">".$row["nombre"]."</option>";
            }
            echo "</select>";
            for($i = 1; $i < 4; $i++){ 
                estadisticasUsuario($conn, $i);
            }?>
        </div>
        <!-- Panel de los equipos -->
        <div id="stats_equipos" style="display: none;">
            <h1>Estadísticas Equipo</h1>
            <?php estadisticasEquiposTotal($conn); ?>
        </div>
</center></body>
</html>
