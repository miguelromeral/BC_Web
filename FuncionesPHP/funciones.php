<?php
function listaEquipos ($jugador){
    echo "<p> ".$jugador.": ";
    $query = "SELECT nombre from equipo;";
    global $conn;
    $result = mysqli_query($conn, $query);
    echo "<select name=\"equipo_".$jugador."\">";
    echo "<option value=\"null\">Seleccione un equipo</option>";
    while($row = mysqli_fetch_assoc($result))
    {
        $equipo = $row["nombre"];
        echo "<option value=\".".$equipo."\">".$equipo."</option>";
    }
    echo "</select>";
    echo "</p>";
}



?>