<!DOCTYPE html>
<?php
//Comenzamos la sesión para recuperar los valores de sesión
session_start();
//Si se hace una petición con salir, se dejan de guardar la edición.
if (@$_GET['salir'] == "true") {
    $_SESSION['en_curso'] == false;
}
?>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
        <script src="Scripts/checker.js"></script> 
        <link rel="stylesheet" type="text/css" href="Estilos/estilos.css">
    </head>
    <body><center>
<?php
//Si ya estamos en una edición en curso, redirigimos hacia el panel principal.
if (@$_SESSION['en_curso'] == true) {
    ?>
            <p>
                Ya hay una edición en curso. 
            <form action="principal.php" method="get">
                <button  class="button buttonBlue" type="submit">IR AL PANEL PRINCIPAL</button>
            </form>
        </p>
    <?php
} else {

    //Incluimos los ficheros con las funciones PHP
    include 'FuncionesPHP/funciones.php';
    include 'FuncionesPHP/consultas.php';
//Quitamos Warning
//error_reporting(E_ERROR | E_PARSE);

    //Nos conectamos a la base de datos.
    $conn = conectarse();
    //Ahora no estamos en un partido
    $_SESSION['en_partido'] = false;
    ?>

        <h1>Brother's Cup PHP</h1>

        <?php
        //Obtenemos el número de ediciones
        $ne = getNumeroEdiciones($conn);
        // Ponemos el número de edición. Si no hay ediciones, se registran los usuarios que jugarán.
        if ($ne != -1) {
            echo "<h2>Edición " . ($ne + 1) . "ª</h2>";
            $_SESSION['edicion'] = $ne + 1;
            if ($ne == 0) {
                registrarUsuarios($conn);
            }
        } else {
            echo "ERROR EN LA BASE DE DATOS";
        }

        //Si se hace post, es que se está registrando un equipo nuevo
        if ($_POST) {
            //Obtenemos la imagen subida
            $check = getimagesize($_FILES["imagen_equipo_nuevo"]["tmp_name"]);
            if ($check !== false) {
                //Obtenemos los bytes de la imagen subida
                $imgContent = addslashes(file_get_contents($_FILES['imagen_equipo_nuevo']['tmp_name']));
                //Y su nombre
                $nuevo = $_POST['nombre_equipo_nuevo'];
                //Intentamos registrar el equipo.
                if (registrarEquipos($conn, $nuevo, $imgContent)) {
                    echo "<p><b>" . strtoupper($nuevo) . " REGISTRADO CON ÉXITO.</b></p>";
                } else {
                    echo "<p><b>EQUIPO REPETIDO. NO SE HA REGISTRADO.</b></p>";
                }
            } else {
                echo "<p><b>DEBE ENVIAR UNA IMAGEN PARA PODER REGISTRAR EL EQUIPO.</b></p>";
            }
        }
        ?>
        <!-- Formulario con las elecciones de cada jugador. Validamos que no sean repetidos -->
        <form name="fequipos" action="principal.php" onsubmit="return validar_eleccion_equipos()" method="post">

        <?php
        //Por cada jugador, creamos un panel con los equipos que pueden seleccionar.
        listaEquipos("Miguel");
        listaEquipos("Javi");
        listaEquipos("Chechu");
        ?>

            <input class="button buttonBlue" type="submit" value="COMENZAR">
        </form>

        <p>
            <!-- Mostramos la tabla de equipos registrados -->
            <?php getTablaEquiposRegistrados($conn); ?>
        </p>

        <!-- Formulario para registrar un equipo -->
        <h3>Registrar Equipo</h3>
        <form action="" method="post" enctype="multipart/form-data">
            Equipo: <input type="text" name="nombre_equipo_nuevo">
            Imagen (cuadrada): <input type="file" name="imagen_equipo_nuevo"/>
            <input type="submit" value="Registrar">
        </form>

        <!-- Botón para ver las estadísticas -->
        <p>
        <form action="estadisticas.php" method="get">
            <button class="button buttonBlue" type="submit">VER ESTADÍSTICAS</button>
        </form>
        <!-- Botón para sortear tres equipos aleatoriamente -->
        <form action="sorteo.php" method="get">
            <button class="button buttonBlue" type="submit">SORTEAR EQUIPOS</button>
        </form>
    </p>

<?php } ?>
</center></body>
</html>
