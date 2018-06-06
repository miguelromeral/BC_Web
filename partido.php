<!DOCTYPE html>
<?php
//Comenzamos la sesión para recuperar los valores de sesión
session_start();

//Si se hace un post, es que estamos creando un nuevo partido.
if($_POST){
    //Si la sesión está en curso, ponemos los equipos que se han pasado.
   if(@$_SESSION['en_curso']){
      $_SESSION['local'] = $_POST['local'];
      $_SESSION['visitante'] = $_POST['visitante'];
      $_SESSION['tipo'] = $_POST['tipo'];
   }

?>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script src="Scripts/checker.js"></script> 
         <link rel="stylesheet" type="text/css" href="Estilos/estilos.css">
    </head>
	<body onload="carga()">
<?php
//Incluimos las funciones
include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';

//Nos conectamos la BD
$conn = conectarse();
$el = $_SESSION['local'];       //Equipo local
$ev = $_SESSION['visitante'];   //Equipo visitante
$ne = $_SESSION['edicion'];     //Edición
$tipo = $_SESSION['tipo'];      //Tipo de partido
$ul = getUsuarioFromEquipoSesion($_SESSION, $el);   //Usuario local
$uv = getUsuarioFromEquipoSesion($_SESSION, $ev);   //Usuario visitante
$_SESSION['en_partido'] = true;
?>
        <p>
    <center>
        <!-- Formulario con el resultado del partido -->
        <form name="fpartido" id="fpartido" action="principal.php" onsubmit="return confirmar_final()" method="post">
            <!-- Valores ocultos del formulario, para poder pasárselos al panel principal al finalizar -->
            <!-- Equipo local -->
            <input type="hidden" name="el" value="<?= $el ?>" />
            <!-- Equipo Visitante -->
            <input type="hidden" name="ev" value="<?= $ev ?>" />
            <!-- Usuario local -->
            <input type="hidden" name="ul" value="<?= $ul ?>" />
            <!-- Usuario visitante -->
            <input type="hidden" name="uv" value="<?= $uv ?>" />
            <!-- Tipo de partido -->
            <input type="hidden" name="tipo" value="<?= $tipo ?>" />
            <!-- Número de edición -->
            <input type="hidden" name="ed" value="<?= $ne ?>" />
            <table>
                <tr>
                    <!-- Equipo local -->
                    <td colspan="3" id="td_ucl_white"><?= $el ?></td>
                    <!-- Tipo de partido -->
                    <td colspan="3" id="td_ucl_blue"><?= $tipo ?></td>
                    <!-- Equipo visitante -->
                    <td colspan="3" id="td_ucl_white"><?= $ev ?></td>
                </tr>
                <tr>
                    <!-- Escudo local -->
                    <td colspan="3"><?php getImagenEquipoNombre($conn, $el);?></td>
                    <!-- Tanteo -->
                    <td colspan="3"><p class="m_goles"><input type="number" name="gl" min="0" value="0" class="m_goles_uno">-<input type="number" name="gv" min="0" value="0" class="m_goles_uno"></p></td>
                    <!-- Escudo visitante -->
                    <td colspan="3"><?php getImagenEquipoNombre($conn, $ev);?></td>
                </tr>
                <tr>
                    <!-- TR local -->
                    <td><input type="number" name="trl" min="0" max="5" value="0" class="m_tr"></td>
                    <!-- TA local -->
                    <td><input type="number" name="tal" min="0" value="0" class="m_ta"></td>
                    <!-- Imagen usuario local -->
                    <td><?php getImagenUsuario(getIDUsuario($conn, $ul), 0.3); ?></td>
                    <!-- Usuario local -->
                    <td><p id="td_ucl_white"><?= $ul ?></p></td>
                    <!-- Tiempo que lleva el partido en juego -->
                    <td><p id="td_ucl_blue"><span id="minutos">0</span>:<span id="segundos">00</span></p></td>
                    <!-- Usuario visitante -->
                    <td><p id="td_ucl_white"><?= $uv ?></p></td>
                    <!-- Imagen usuario visitante -->
                    <td><?php getImagenUsuario(getIDUsuario($conn, $uv), 0.3);?></td>
                    <!-- TA visitante -->
                    <td><input type="number" name="tav" min="0" value="0" class="m_ta"></td>
                    <!-- TR visitante -->
                    <td><input type="number" name="trv" min="0" max="5" value="0" class="m_tr"></td>
                </tr>
                
            </table>
            <input class="button buttonBlue" type="submit" value="FINAL DEL PARTIDO">

        
        <?php 
        //Si estamos ante una final, se muestra la copa
        if($tipo == "Final"){ ?>
            <p>
                <img src="Imagenes/Champions.png" width="241" height="327"/>
            </p>
        <?php } ?>
    
    </center>
    </body>
</html>

<?php

}else{
    //Si no se hace post, se hace get, no se puede hacer un partido. Solo 
    //desde el panel principal
 ?>

<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
    </head>
    <body>
        Solo puede acceder desde el panel principal.
    </body>
</html>


    <?php
        
}
