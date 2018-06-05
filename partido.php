<!DOCTYPE html>
<?php

session_start();

if($_POST){
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

include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';
//Quitamos Warning
//error_reporting(E_ERROR | E_PARSE);

$conn = conectarse();
$el = $_SESSION['local'];
$ev = $_SESSION['visitante'];
$ne = $_SESSION['edicion'];
$tipo = $_SESSION['tipo'];     
$ul = getUsuarioFromEquipoSesion($_SESSION, $el);
$uv = getUsuarioFromEquipoSesion($_SESSION, $ev);
$_SESSION['en_partido'] = true;
  
?>
        <p>
    <center>

        <form name="fpartido" id="fpartido" action="principal.php" onsubmit="return confirmar_final()" method="post">
            <input type="hidden" name="el" value="<?= $el ?>" />
            <input type="hidden" name="ev" value="<?= $ev ?>" />
            <input type="hidden" name="ul" value="<?= $ul ?>" />
            <input type="hidden" name="uv" value="<?= $uv ?>" />
            <input type="hidden" name="tipo" value="<?= $tipo ?>" />
            <input type="hidden" name="ed" value="<?= $ne ?>" />
            <table>
                <tr>
                    <td colspan="3" id="td_ucl_white"><?= $el ?></td>
                    <td colspan="3" id="td_ucl_blue"><?= $tipo ?></td>
                    <td colspan="3" id="td_ucl_white"><?= $ev ?></td>
                </tr>
                <tr>
                    <td colspan="3"><?php getImagenEquipoNombre($conn, $el);?></td>
                    <td colspan="3"><p class="m_goles"><input type="number" name="gl" min="0" value="0" class="m_goles_uno">-<input type="number" name="gv" min="0" value="0" class="m_goles_uno"></p></td>
                    <td colspan="3"><?php getImagenEquipoNombre($conn, $ev);?></td>
                </tr>
                <tr>
                    <td><input type="number" name="trl" min="0" max="5" value="0" class="m_tr"></td>
                    <td><input type="number" name="tal" min="0" value="0" class="m_ta"></td>
                    <td><?php getImagenUsuario(getIDUsuario($conn, $ul), 0.3); ?></td>
                    <td><p id="td_ucl_white"><?= $ul ?></p></td>
                    <td><p id="td_ucl_blue"><span id="minutos">0</span>:<span id="segundos">00</span></p></td>
                    <td><p id="td_ucl_white"><?= $uv ?></p></td>
                    <td><?php getImagenUsuario(getIDUsuario($conn, $uv), 0.3);?></td>
                    <td><input type="number" name="tav" min="0" value="0" class="m_ta"></td>
                    <td><input type="number" name="trv" min="0" max="5" value="0" class="m_tr"></td>
                </tr>
                
            </table>
            <input class="button buttonBlue" type="submit" value="FINAL DEL PARTIDO">

        
        <?php if($tipo == "Final"){ ?>
            <p>
                <img src="Imagenes/Champions.png" width="241" height="327"/>
            </p>
        <?php } ?>
    
    </center>
    </body>
</html>

<?php

}else{
    
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
