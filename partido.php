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
    </head>
    <body>
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
            <table border="1">
                <tr>
                    <td>Equipo</td>
                    <td>Usuario</td>
                    <td>Escudo</td>
                    <td>Goles</td>
                    <td>TA</td>
                    <td>TR</td>
                    
                    <?php if($tipo == "Final"){ ?>
                    
                    <td>Añadido</td>
                    <td>Ganador Penaltis</td>
                    
                    <?php } ?>
                </tr>
                <tr>
                    <td><?= $el ?></td>
                    <td><?= $ul ?></td>
                    <td><?php getImagenEquipoNombre($conn, $el, 50, 50) ?></td>
                    <td><input type="number" name="gl" min="0" value="0"></td>
                    <td><input type="number" name="tal" min="0" value="0"></td>
                    <td><input type="number" name="trl" min="0" max="5" value="0"></td>
                    
                    <?php if($tipo == "Final"){ ?>
                    
                    <td><input type="checkbox" name="pr" value="true">Prórroga<br>
                    <td><input id="ganp1" type="radio" name="ganp" value="<?= getIDEquipo($conn, $el)?>">Gané en penaltis<br></td>
                
                    <?php } ?>
                </tr>
                
                <tr>
                    <td><?= $ev ?></td>
                    <td><?= $uv ?></td>
                    <td><?php getImagenEquipoNombre($conn, $ev, 50, 50) ?></td>
                    <td><input type="number" name="gv" min="0" value="0"></td>
                    <td><input type="number" name="tav" min="0" value="0"></td>
                    <td><input type="number" name="trv" min="0" max="5" value="0"></td>
                    
                    <?php if($tipo == "Final"){ ?>
                    
                    <td><input type="checkbox" name="pen" value="true">Penaltis<br>
                    <td><input id="ganp2" type="radio" name="ganp" value="<?= getIDEquipo($conn, $ev)?>">Gané en penaltis<br>
                        <a onclick="limpiarGanp()">Resetear ganador</a>
                    </td>
                    <?php } ?>
                </tr>
                
            </table>
        <input type="submit" value="FINAL DEL PARTIDO">

    
    
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
