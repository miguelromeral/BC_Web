<!DOCTYPE html>
<?php

session_start();

include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';


error_reporting(E_ERROR | E_PARSE);

$conn = conectarse();

if($_POST){
    // Primera vez que se accede al panel:
   if(@$_SESSION['en_curso'] != true){
      $_SESSION['en_curso'] = true;
      $_SESSION['nueva_edicion'] = true;
      $_SESSION['Miguel'] = $_POST['equipo_Miguel'];
      $_SESSION['Javi'] = $_POST['equipo_Javi'];
      $_SESSION['Chechu'] = $_POST['equipo_Chechu'];
   }else{
       if (@$_SESSION['en_partido']){
        // Final de un partido:
        $ed = $_POST['ed'];
        $ul = getIDUsuario($conn, $_POST['ul']);
        $uv = getIDUsuario($conn, $_POST['uv']);
        $el = getIDEquipo($conn, $_POST['el']);
        $ev = getIDEquipo($conn, $_POST['ev']);
        $gl = $_POST['gl'];
        $gv = $_POST['gv'];
        $tal = $_POST['tal'];
        $tav = $_POST['tav'];
        $trl = $_POST['trl'];
        $trv = $_POST['trv'];
        $tipo = $_POST['tipo'];
        $pr = 'false'; if ($_POST['pr']) $pr = 'true';
        $pen = 'false'; if ($_POST['pen']) $pen = 'true';
        $ganp = 0; if ($_POST['ganp']) $ganp = $_POST['ganp'];
        registrarPartido($conn, $ed, $tipo, $ul, $uv, $el, $ev, $gl, $gv, $tal, $tav, $trl, $trv, $pr, $pen, $ganp);
        $_SESSION['en_partido'] = false;
       }
       
   }
}



?>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
         <script src="Scripts/checker.js"></script>
         <link rel="stylesheet" type="text/css" href="Estilos/estilos.css">
    </head>
    <body>
        
        <div>Prueba F1</div>
        
    <form name="fsalir" action="index.php" method="get">
        <button type="submit" name="salir" id="salir" value="true">CERRAR SESIÓN</button>
    </form>
        
<?php

//Quitamos Warning
//error_reporting(E_ERROR | E_PARSE);

       
$ed = $_SESSION['edicion'];
$em = $_SESSION['Miguel'];
$ej = $_SESSION['Javi'];
$ec = $_SESSION['Chechu'];

if(@$_SESSION['nueva_edicion'] == true){
   registrarEdicion($conn, $_SESSION['edicion']);
   $_SESSION['nueva_edicion'] = false;
}


printClasificacion($conn, $ed);

$cl = getClasificacion($conn, $ed);

//$_SESSION['primero'] = getEquipoFromUsuarioSesion($sesion, $cl[0][9]);
//$_SESSION['segundo'] = getEquipoFromUsuarioSesion($sesion, $cl[1][9]);


?>
        <p>
    <center>
        <table cellspacing="30">
            <tr>
                <td colspan="2">
            <center><img src="Imagenes/foto_Miguel.png" width="175" height="253"/></center>
                </td>
                <td colspan="2">
            <center><img src="Imagenes/foto_Javi.png" width="175" height="253"/></center>
                </td>
                <td colspan="2">
            <center><img src="Imagenes/foto_Chechu.png" width="175" height="253"/></center>
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
    </p>
    
    <form name="fpartido" action="partido.php" onsubmit="return validar_equipos_partido()" method="post">
    <select name="local">
        <option value="null">Equipo LOCAL</option>
        <option value="<?= $em ?>"><?= $em ?></option>
        <option value="<?= $ej ?>"><?= $ej ?></option>
        <option value="<?= $ec ?>"><?= $ec ?></option>
    </select>
    <i> v </i> 
    <select name="visitante">
        <option value="null">Equipo VISITANTE</option>
        <option value="<?= $em ?>"><?= $em ?></option>
        <option value="<?= $ej ?>"><?= $ej ?></option>
        <option value="<?= $ec ?>"><?= $ec ?></option>
    </select>
    <br>
    <select name="tipo">
        <option value="null">TIPO DE PARTIDO</option>
        <option value="Fase de Grupos">Fase de Grupos</option>
        <option value="Final">FINAL</option>
    </select><br>
    <input type="submit" value="EMPEZAR">
    
    </form>
    
    <p>
        <?php getTablaPartidosEdicion($conn, $ed); ?>
    </p>
    
    <p>
        <?php  ?>
    </p>
    
    
    
    
    
    </center>
    </body>
</html>
