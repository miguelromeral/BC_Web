<!DOCTYPE html>
<?php
//Comenzamos la sesión para recuperar los valores de sesión
session_start();

//Incluimos los ficheros con las funciones PHP
include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';

//Quitamos los warnings y errores.
error_reporting(E_ERROR | E_PARSE);

//Nos conectamos a la base de datos.
$conn = conectarse();

//Pueden ocurrir 2 post: que se inicia una edición, o que se acaba un partido:
if($_POST){
    // Primera vez que se accede al panel:
   if(@$_SESSION['en_curso'] != true){
       //Indicamos que está en curso para no elegir equipos de nuevo.
      $_SESSION['en_curso'] = true;
      //Nueva edición, para más tarde registrar la elección en la base de datos.
      $_SESSION['nueva_edicion'] = true;
      //Incluimos los equipos seleccionados por cada uno.
      $_SESSION['Miguel'] = $_POST['equipo_Miguel'];
      $_SESSION['Javi'] = $_POST['equipo_Javi'];
      $_SESSION['Chechu'] = $_POST['equipo_Chechu'];
   }else{
       //Si hacemos un post con un partido en curso, significa que ha finalizado:
       if (@$_SESSION['en_partido']){
            $ed = $_POST['ed']; //Edición
            $ul = getIDUsuario($conn, $_POST['ul']); //Usuario local
            $uv = getIDUsuario($conn, $_POST['uv']); //Usuario visitante
            $el = getIDEquipo($conn, $_POST['el']); //Equipo local
            $ev = getIDEquipo($conn, $_POST['ev']); //Equipo visitante
            $gl = $_POST['gl']; //Goles locales
            $gv = $_POST['gv']; //Goles visitantes
            $tal = $_POST['tal']; //TA local
            $tav = $_POST['tav']; //TA visitante
            $trl = $_POST['trl']; //TR local
            $trv = $_POST['trv']; //TR visitante
            $tipo = $_POST['tipo']; //Tipo de partido
            $pr = 'false'; if ($_POST['pr']) $pr = 'true'; //Prórroga (si la hay)
            $pen = 'false'; if ($_POST['pen']) $pen = 'true'; //Penaltis (si los hay)
            $ganp = 0; if ($_POST['ganp']) $ganp = $_POST['ganp']; //ID equipos ganador en penaltis (si los hay)
            //Regidstramos el partido con todos los valores
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
    <center>
<?php
       
$ed = $_SESSION['edicion']; //Edición
$em = $_SESSION['Miguel']; //Equipo de Miguel
$ej = $_SESSION['Javi']; //Equipo de Javi
$ec = $_SESSION['Chechu']; //Equipo de Chechu

//Si es nueva edición, registramos la edición
if(@$_SESSION['nueva_edicion'] == true){
   registrarEdicion($conn, $_SESSION['edicion'], $em, $ej, $ec);
   $_SESSION['nueva_edicion'] = false;
}

echo "<p>";
//Imprimimos la clasificación para ver quienes deberían jugar la final.
printClasificacion($conn, $ed);
echo "</p>";

?>
        <p>
            <!-- Se muestra la elección de los jugadores -->
        <table>
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
                <td id="td_ucl_blue">
                    <?php getImagenEquipoNombre($conn, $em, 50, 50); ?>
                </td>
                <td id="td_ucl_white"><?=$em?></td>
                <td id="td_ucl_blue">
                    <?php getImagenEquipoNombre($conn, $ej, 50, 50); ?>
                </td>
                
                <td id="td_ucl_white"><?=$ej?></td>
                <td id="td_ucl_blue">
                    <?php getImagenEquipoNombre($conn, $ec, 50, 50); ?>
                </td>
                <td id="td_ucl_white"><?=$ec?></td>
            </tr>
        

        </table>
    </p>
    
    <!-- Formulario para la elección del partido que se jugará -->
    <form name="fpartido" action="partido.php" onsubmit="return validar_equipos_partido()" method="post">
    <select name="local">
        <option value="null">Equipo LOCAL</option>
        <option value="<?= $em ?>"><?= $em ?></option>
        <option value="<?= $ej ?>"><?= $ej ?></option>
        <option value="<?= $ec ?>"><?= $ec ?></option>
    </select>
    <select name="visitante">
        <option value="null">Equipo VISITANTE</option>
        <option value="<?= $em ?>"><?= $em ?></option>
        <option value="<?= $ej ?>"><?= $ej ?></option>
        <option value="<?= $ec ?>"><?= $ec ?></option>
    </select>
        <p>
    <select name="tipo">
        <option value="null">TIPO DE PARTIDO</option>
        <option value="Fase de Grupos">Fase de Grupos</option>
        <option value="Final">FINAL</option>
    </select></p>
    <input class="button buttonBlue" type="submit" value="EMPEZAR">
    </form>
    
    <p>
        <!-- Obtenemos los partidos jugados hasta ahora -->
        <?php getTablaPartidosEdicion($conn, $ed); ?>
    </p>
    
    
    </center>
    </body>
</html>
