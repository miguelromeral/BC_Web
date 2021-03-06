<?php
/**
 * Obtiene los títulos de un usuario en función de su ID
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $usuario ID del usuario
 * @return integer Títulos del usuario
 */
function getCampeonatosUsuario($conn, $usuario){
    $query = "select count(a.partido) as BC_ganadas, a.nombre
from (
	select m.goles, m.partido, m.equipo, u.nombre, u.id
    from usuario as u
    inner join (
    	select m.usuario, m.goles, m.partido, m.equipo
        from marcador as m
        inner join Partido as p
        on m.partido = p.id
        where p.tipo = 'Final'
    ) as m
    on m.usuario = u.id
    where m.usuario = $usuario
) as a
inner join(
    select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido, n2.ganador_penaltis
    from (
        select m.partido, e.id
        from equipo as e
        inner join Marcador as m
        on e.id = m.equipo
    ) as n1
    inner join
    (
        select m.goles, m.equipo, p.id as partido, p.ganador_penaltis
        from partido as p
        inner join Marcador as m
        on m.partido = p.id
    ) as n2
    on n1.partido = n2.partido
    group by n1.partido, n2.ganador_penaltis
    order by n1.partido
) as b
on a.partido = b.partido
where a.goles = b.max_goles and a.goles > b.min_goles or a.equipo = b.ganador_penaltis
group by a.nombre
order by BC_ganadas desc;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["BC_ganadas"];
}

/**
 * Partidos jugados por un usuario.
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $usuario ID del usuario
 * @return integer Partidos jugados
 */
function getPJUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(m.partido) as cuenta
from usuario as u
inner join marcador as m
on u.id = m.usuario
where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["cuenta"];
}
/**
 * Equipos seleccionados por usuario
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $usuario ID del usuario
 * @return integer Equipos seleccionados por usuario
 */
function getNumeroEquiposUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(e.nombre) as cuenta
from equipo as e
inner join (
	select distinct m.equipo
    from marcador as m
    inner join usuario as u
    on m.usuario = u.id
    where u.nombre = '".$nombre."'

) as n
on e.id = n.equipo;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["cuenta"];
}

/**
 * Tarjetas amarillas de un usuario
 * @param \mysqli $conn Conexión con la BD.
 * @param integer $usuario ID del usuario
 * @return integer Tarjetas amarillas de un usuario
 */
function getTAUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select sum(m.ta) as ta
from Marcador as m
inner join Usuario as u
on m.usuario = u.id
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["ta"];
}

/**
 * Tarjetas rojas de un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Tarjetas rojas de un usuario
 */
function getTRUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select sum(m.tr) as tr
from Marcador as m
inner join Usuario as u
on m.usuario = u.id
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["tr"];
}

/**
 * Tandas de penaltis de un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Tandas de penaltis jugadas de un usuario
 */
function getTPUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(m.partido) as tandas
from Usuario as u
inner join 
(
	select m.partido, m.usuario
    from Partido as p
    inner join Marcador as m
    on p.id = m.partido
    where p.penaltis
) as m
on u.id = m.usuario
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["tandas"];
}

/**
 * Prórrogas jugadas de un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID usuario
 * @return integer Prórrogas jugadas de un usuario
 */
function getPRUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(m.partido) as pr
from Usuario as u
inner join 
(
	select m.partido, m.usuario
    from Partido as p
    inner join Marcador as m
    on p.id = m.partido
    where p.prorroga
) as m
on u.id = m.usuario
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["pr"];
}

/**
 * Goles totales marcados por un usuario
 * @param \mysqli $conn Conexión con BD
 * @param integer $usuario ID usuario
 * @return integer Goles totales marcados
 */
function getGFUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select sum(m.goles) as count
from Usuario as u
inner join Marcador as m
on u.id = m.usuario
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Goles encajados a un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Goles encajados
 */
function getGCUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select sum(b.goles) as count
from (
	select m.partido
    from Marcador as m
    inner join Usuario as u
    on m.usuario = u.id
    where u.nombre = '".$nombre."'
) as a
inner join (
	select m.partido, m.goles, m.edicion
    from Marcador as m
    inner join Usuario as u
    on m.usuario = u.id
    where u.nombre != '".$nombre."'
) as b
on a.partido = b.partido;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Victorias de un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Victorias
 */
function getPGUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(a.partido) as count  
 from (      
 	select m.goles, m.partido
     from Marcador as m      
     inner join      
     Usuario as u      
     on m.usuario = u.id
     where u.nombre = '".$nombre."'    
 ) as a      
 inner join(      
     select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido      
     from (      
         select m.partido, e.id
         from Equipo as e      
         inner join Marcador as m      
         on e.id = m.equipo      
     ) as n1      
     inner join      
     (      
         select m.goles, m.equipo, p.id as partido
         from Partido as p      
         inner join Marcador as m      
         on m.partido = p.id
     ) as n2      
     on n1.partido = n2.partido      
     group by n1.partido      
     order by n1.partido      
 ) as b      
 on a.partido = b.partido      
 where a.goles = b.max_goles and a.goles > b.min_goles;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Empates de un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Empates
 */
function getPEUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(a.partido) as count  
 from (      
 	select m.goles, m.partido
     from Marcador as m      
     inner join      
     Usuario as u      
     on m.usuario = u.id
     where u.nombre = '".$nombre."'    
 ) as a      
 inner join(      
     select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido      
     from (      
         select m.partido, e.id
         from Equipo as e      
         inner join Marcador as m      
         on e.id = m.equipo      
     ) as n1      
     inner join      
     (      
         select m.goles, m.equipo, p.id as partido
         from Partido as p      
         inner join Marcador as m      
         on m.partido = p.id
     ) as n2      
     on n1.partido = n2.partido      
     group by n1.partido      
     order by n1.partido      
 ) as b      
 on a.partido = b.partido      
 where a.goles = b.max_goles and a.goles = b.min_goles;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Derrotas de un usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Derrotas
 */
function getPPUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(a.partido) as count  
 from (      
 	select m.goles, m.partido
     from Marcador as m      
     inner join      
     Usuario as u      
     on m.usuario = u.id
     where u.nombre = '".$nombre."'    
 ) as a      
 inner join(      
     select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido      
     from (      
         select m.partido, e.id
         from Equipo as e      
         inner join Marcador as m      
         on e.id = m.equipo      
     ) as n1      
     inner join      
     (      
         select m.goles, m.equipo, p.id as partido
         from Partido as p      
         inner join Marcador as m      
         on m.partido = p.id
     ) as n2      
     on n1.partido = n2.partido      
     group by n1.partido      
     order by n1.partido      
 ) as b      
 on a.partido = b.partido      
 where a.goles < b.max_goles and a.goles = b.min_goles;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Veces en las que ha sido primero en una fase de grupos
 * @param \mysql $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return Veces en las que ha sido primero
 */
function getPrimeroFGUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(m.id) as count       
   from Usuario as u          
   inner join(          
   	select p.id, m.usuario          
       from Marcador as m          
       inner join Partido as p          
       on m.partido = p.id          
       where p.tipo = 'Final' and m.local = 1          
   ) as m          
   on u.id = m.usuario
   where u.nombre = '".$nombre."' ;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Tandas de penaltis ganadas por usuario
 * @param \mysql $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Tandas de penaltis gandas
 */
function getPENGUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(p.id)     as count     
   from Partido as p          
   inner join          
   (          
       select u.id, m.equipo, m.partido          
       from Marcador as m          
       inner join Usuario as u          
       on u.id = m.usuario          
      where u.nombre = '".$nombre."'          
   ) as n          
   on p.id = n.partido          
   where p.penaltis and p.ganador_penaltis = n.equipo";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Indica la fecha en la que el usuario gano la última edición
 * @param \mysql $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return string Fecha en que ganó la última 
 */
function getUltimaUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select a.partido, a.fecha          
   from (          
   	select m.goles, m.partido, m.equipo, u.nombre, u.id, m.fecha          
       from Usuario as u          
       inner join (          
       	select m.usuario, m.goles, m.partido, m.equipo, m.fecha          
           from(          
               select m.usuario, m.goles, m.partido, m.equipo, e.fecha          
               from Marcador as m          
               inner join Edicion as e          
               on m.edicion = e.id
           )          
           as m          
           inner join Partido as p          
           on m.partido = p.id
           where p.tipo = 'Final'          
       ) as m          
       on m.usuario = u.id          
       where u.nombre = '".$nombre."'          
   ) as a          
   inner join(          
       select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido, n2.ganador_penaltis          
       from (          
           select m.partido, e.id
           from Equipo as e          
           inner join Marcador as m          
           on e.id = m.equipo          
       ) as n1          
       inner join          
       (          
           select m.goles, m.equipo, p.id, p.ganador_penaltis          
           from Partido as p          
           inner join Marcador as m          
           on m.partido = p.id
       ) as n2          
       on n1.partido = n2.id          
       group by n1.partido, n2.ganador_penaltis          
       order by n1.partido          
   ) as b          
   on a.partido = b.partido          
   where a.goles = b.max_goles and a.goles > b.min_goles or a.equipo = b.ganador_penaltis          
   group by a.partido, a.fecha          
   order by a.partido desc          
   limit 1;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["fecha"];
}

/**
 * Fecha del último título que ganó un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return string Fecha de la última copa
 */
function getUltimaEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select a.partido, a.fecha          
   from (          
   	select m.goles, m.partido, m.equipo, u.nombre, u.id, m.fecha          
       from Equipo as u          
       inner join (          
       	select m.usuario, m.goles, m.partido, m.equipo, m.fecha          
           from(          
               select m.usuario, m.goles, m.partido, m.equipo, e.fecha          
               from Marcador as m          
               inner join Edicion as e          
               on m.edicion = e.id
           )          
           as m          
           inner join Partido as p          
           on m.partido = p.id
           where p.tipo = 'Final'          
       ) as m          
       on m.equipo = u.id          
       where u.nombre = '".$nombre."'          
   ) as a          
   inner join(          
       select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido, n2.ganador_penaltis          
       from (          
           select m.partido, e.id
           from Equipo as e          
           inner join Marcador as m          
           on e.id = m.equipo          
       ) as n1          
       inner join          
       (          
           select m.goles, m.equipo, p.id, p.ganador_penaltis          
           from Partido as p          
           inner join Marcador as m          
           on m.partido = p.id
       ) as n2          
       on n1.partido = n2.id          
       group by n1.partido, n2.ganador_penaltis          
       order by n1.partido          
   ) as b          
   on a.partido = b.partido          
   where a.goles = b.max_goles and a.goles > b.min_goles or a.equipo = b.ganador_penaltis          
   group by a.partido, a.fecha          
   order by a.partido desc          
   limit 1;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["fecha"];
}

/**
 * Finales que ha jugado un usuario
 * @param \mysql $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 * @return integer Finales jugadas
 */
function getFinalesUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select count(m.partido) as count  
   from Usuario as u          
   inner join           
   (          
   	select m.partido, m.usuario
       from Partido as p          
       inner join Marcador as m          
       on p.id = m.partido          
       where p.tipo = 'Final'          
   ) as m          
   on u.id = m.usuario          
   where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Títulos de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Títulos de un equipo
 */
function getCampeonatosEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(a.partido)      as count    
   from (          
   	select m.goles, m.partido, e.nombre as equipo, e.id, m.nombre as jugador          
       from Equipo as e          
       inner join (          
       	select m.goles, m.equipo, m.partido, m.nombre          
           from (          
           	select m.goles, m.equipo, m.partido, u.nombre          
               from Marcador as m          
               inner join Usuario as u          
               on m.usuario = u.id          
           ) as m          
           inner join Partido as p          
           on m.partido = p.id          
           where p.tipo = 'Final'          
       ) as m          
       on m.equipo = e.id          
       where e.nombre = '".$nombre."'          
   ) as a          
   inner join(          
       select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido, n2.ganador_penaltis          
       from (          
           select m.partido, e.id          
           from Equipo as e          
           inner join Marcador as m          
           on e.id = m.equipo          
       ) as n1          
       inner join          
       (          
           select m.goles, m.equipo, p.id, p.ganador_penaltis          
           from Partido as p          
           inner join Marcador as m          
           on m.partido = p.id
       ) as n2          
       on n1.partido = n2.id          
       group by n1.partido, n2.ganador_penaltis          
       order by n1.partido          
   ) as b          
   on a.partido = b.partido          
   where a.goles = b.max_goles and a.goles > b.min_goles or a.id = b.ganador_penaltis;";
   
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Partidos jugados de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Partidos jugados
 */
function getPJEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(m.partido) as count
from Equipo as u
inner join Marcador as m
on u.id = m.equipo
where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Número de usuarios que se han cogido este equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Usuarios que lo han seleccionado
 */
function getNumeroEntrenadoresEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(u.nombre) as count       
   from Usuario as u          
   inner join (          
   	select distinct m.usuario
       from Marcador as m          
       inner join Equipo as e          
       on m.equipo = e.id          
       where e.nombre = '".$nombre."'          
             
   ) as n          
   on u.id = n.usuario;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $ent = $row["count"]; 
    if ($ent == 3){
        return "Entrenado por todos";
    }elseif($ent == 0){
        return "Sin entrenadores";
    }else{
        return $ent;
    }
}

/**
 * Tarjetas amarillas de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Tarjetas amarillas
 */
function getTAEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select sum(m.ta) as ta          
from Marcador as m          
inner join Equipo as u          
on m.equipo = u.id
where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["ta"];
}

/**
 * Tarjetas rojas de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Tarjetas rojas de un equipo
 */
function getTREquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select sum(m.tr) as tr          
from Marcador as m          
inner join Equipo as u          
on m.equipo = u.id
where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["tr"];
}

/**
 * Tandas de penaltis jugadas de un equipop
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Tandas de penaltis jugadas
 */
function getTPEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(m.partido) as tandas
from Equipo as u
inner join 
(
	select m.partido, m.equipo
    from Partido as p
    inner join Marcador as m
    on p.id = m.partido
    where p.penaltis
) as m
on u.id = m.equipo
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["tandas"];
}

/**
 * Goles marcados por un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Goles a favor
 */
function getGFEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select sum(m.goles) as count          
   from Equipo as u          
   inner join Marcador as m          
   on u.id = m.equipo          
   where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}
/**
 * Goles encajados de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Goles en cnotra
 */
function getGCEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select sum(b.goles) as count          
   from (          
   	select m.partido          
       from Marcador as m          
       inner join Equipo as u          
       on m.equipo = u.id          
       where u.nombre = '".$nombre."'         
   ) as a          
   inner join (          
   	select m.partido, m.goles, m.edicion          
       from Marcador as m          
       inner join Equipo as u          
       on m.equipo = u.id          
       where u.nombre != '".$nombre."'         
   ) as b          
   on a.partido = b.partido;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}
/**
 * Victorias de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Victorias
 */
function getPGEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(a.partido) as count  
   from (          
   	select m.goles, m.partido          
       from Marcador as m          
       inner join          
       Equipo as e          
       on m.equipo = e.id          
       where e.nombre = '".$nombre."'         
   ) as a          
   inner join(          
       select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido          
       from (          
           select m.partido, e.id          
           from Equipo as e          
           inner join Marcador as m          
           on e.id = m.equipo          
       ) as n1          
       inner join          
       (          
           select m.goles, m.equipo, p.id
           from Partido as p          
           inner join Marcador as m          
           on m.partido = p.id      
       ) as n2          
       on n1.partido = n2.id
       group by n1.partido          
       order by n1.partido          
   ) as b          
   on a.partido = b.partido              
   where a.goles = b.max_goles and a.goles > b.min_goles;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Empates de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Empates
 */
function getPEEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(a.partido) as count  
 from (      
 	select m.goles, m.partido
     from Marcador as m      
     inner join      
     Equipo as u      
     on m.equipo = u.id
     where u.nombre = '".$nombre."'    
 ) as a      
 inner join(      
     select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido      
     from (      
         select m.partido, e.id
         from Equipo as e      
         inner join Marcador as m      
         on e.id = m.equipo      
     ) as n1      
     inner join      
     (      
         select m.goles, m.equipo, p.id as partido
         from Partido as p      
         inner join Marcador as m      
         on m.partido = p.id
     ) as n2      
     on n1.partido = n2.partido      
     group by n1.partido      
     order by n1.partido      
 ) as b      
 on a.partido = b.partido      
 where a.goles = b.max_goles and a.goles = b.min_goles;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}

/**
 * Derrotas de un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Derrotas
 */
function getPPEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(a.partido) as count  
 from (      
 	select m.goles, m.partido
     from Marcador as m      
     inner join      
     Equipo as u      
     on m.equipo = u.id
     where u.nombre = '".$nombre."'    
 ) as a      
 inner join(      
     select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido      
     from (      
         select m.partido, e.id
         from Equipo as e      
         inner join Marcador as m      
         on e.id = m.equipo      
     ) as n1      
     inner join      
     (      
         select m.goles, m.equipo, p.id as partido
         from Partido as p      
         inner join Marcador as m      
         on m.partido = p.id
     ) as n2      
     on n1.partido = n2.partido      
     group by n1.partido      
     order by n1.partido      
 ) as b      
 on a.partido = b.partido      
 where a.goles < b.max_goles and a.goles = b.min_goles;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}
/**
 * Veces que un equipo ha quedado primero en fase de grupos
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Veces que el equipo ha sido primero en Fase de Grupos
 */
function getPrimeroFGEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(m.id) as count       
   from Equipo as u          
   inner join(          
   	select p.id, m.equipo          
       from Marcador as m          
       inner join Partido as p          
       on m.partido = p.id          
       where p.tipo = 'Final' and m.local = 1          
   ) as m          
   on u.id = m.equipo
   where u.nombre = '".$nombre."' ;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}
/**
 * Tandas de penaltis ganadas por un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Tandas de penaltis ganadas
 */
function getPENGEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(p.id)     as count     
   from Partido as p          
   inner join          
   (          
       select u.id, m.equipo, m.partido          
       from Marcador as m          
       inner join Equipo as u          
       on u.id = m.equipo          
      where u.nombre = '".$nombre."'          
   ) as n          
   on p.id = n.partido          
   where p.penaltis and p.ganador_penaltis = n.equipo";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}
/**
 * Finales disputadas por un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Finales jugadas
 */
function getFinalesEquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(m.partido) as count  
   from Equipo as u          
   inner join           
   (          
   	select m.partido, m.equipo
       from Partido as p          
       inner join Marcador as m          
       on p.id = m.partido          
       where p.tipo = 'Final'          
   ) as m          
   on u.id = m.equipo          
   where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["count"];
}
/**
 * Prórrogas jugadas por un equipo
 * @param \mysql $conn Conexión con la BD
 * @param integer $equipo ID del equipo
 * @return integer Prórrogas jugadas de un equipo
 */
function getPREquipo($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select count(m.partido) as pr
from Equipo as u
inner join 
(
	select m.partido, m.equipo
    from Partido as p
    inner join Marcador as m
    on p.id = m.partido
    where p.prorroga
) as m
on u.id = m.equipo
where u.nombre = '".$nombre."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["pr"];
}

/**
 * Muestra todos los usuarios y equipos campeones
 * @param \mysqli $conn Conexión con la BD
 */
function palmares($conn){
    $query = "select  a.equipo, a.jugador, b.edicion          
   from (          
   	select m.goles, m.partido, e.nombre as equipo, e.id, m.nombre as jugador          
       from Equipo as e          
       inner join (          
       	select m.goles, m.equipo, m.partido, m.nombre          
           from (          
           	select m.goles, m.equipo, m.partido, u.nombre          
               from Marcador as m          
               inner join Usuario as u          
               on m.usuario = u.id          
           ) as m          
           inner join Partido as p          
           on m.partido = p.id          
           where p.tipo = 'Final'          
       ) as m          
       on m.equipo = e.id          
   ) as a          
   inner join(          
       select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido, n2.ganador_penaltis, n1.edicion          
       from (          
           select m.partido, e.id, m.edicion          
           from Equipo as e          
           inner join Marcador as m          
           on e.id = m.equipo          
       ) as n1          
       inner join          
       (          
           select m.goles, m.equipo, p.id, p.ganador_penaltis          
           from Partido as p          
           inner join Marcador as m          
           on m.partido = p.id          
       ) as n2          
       on n1.partido = n2.id          
       group by n1.edicion, n1.partido, n2.ganador_penaltis          
       order by n1.partido          
   ) as b          
   on a.partido = b.partido          
   where a.goles = b.max_goles and a.goles > b.min_goles or a.id = b.ganador_penaltis          
   group by b.edicion, a.equipo, a.jugador          
   order by b.edicion desc;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Campeones</i>
            </tr>
            <tr>
                <td id="td_ucl_blue" colspan="2">Equipo</td>
                
                <td id="td_ucl_blue" colspan="2">Usuario</td>
                <td id="td_ucl_blue">Edición</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr id="td_ucl_white">
                <td><?php
                $eq = $row["equipo"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1);
                ?></td>
                <td><?= $eq ?></td>
                <td><?php
                $us = $row["jugador"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15);
                ?></td>
                <td><?= $us ?></td>
                <td id="td_ucl_white_bold"><?= $row["edicion"] ?>ª</td>
            </tr>    
        <?php
    }
    echo "</table>";
}

/**
 * Muestra en una lista las fechas de las ediciones.
 * @param \mysqli $conn Conexión con la BD
 */
function fechasEdiciones($conn){
    $query = "select * from Edicion order by fecha desc;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Celebración de ediciones</i>
            </tr>
            <tr>
                <td id="td_ucl_blue">Edición</td>
                <td id="td_ucl_blue">Fecha</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td id="td_ucl_blue"><?= $row["id"] ?></td>
                <td id="td_ucl_white"><?php echo date("d/m/Y", strtotime($row["fecha"]))." - ".$row["hora"].":".$row["mins"]; ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}

/**
 * Muestra en una tabla las veces que un usuario ha seleccionado a un equipo
 * @param \mysqli $conn Conexión con la BD
 */
function equiposSeleccionadosPorUsuario($conn){
    $query = "select e.nombre, n.user, count(n.user) as veces_cogido          
   from Equipo as e          
   inner join (          
   	select distinct m.equipo, u.nombre as user, m.edicion          
       from Marcador as m          
       inner join Usuario as u          
       on m.usuario = u.id          
   ) as n          
   on e.id = n.equipo          
   group by e.nombre, n.user          
   order by veces_cogido desc limit 20;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Equipos más utilizados</i>
            </tr>
            <tr>
                <td id="td_ucl_blue" colspan="2">Equipo</td>
                <td id="td_ucl_blue" colspan="2">Usuario</td>
                <td id="td_ucl_blue">Veces seleccionado</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr id="td_ucl_white">
                <td><?php
                $eq = $row["nombre"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1);
                ?></td>
                <td><?= $eq ?></td>
                <td><?php
                $us = $row["user"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15);
                ?></td>
                <td><?= $us ?></td>
                <td id="td_ucl_white_bold"><?= $row["veces_cogido"] ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}
/**
 * Muestra en una tablas los equipos seleccionados por los usuarios
 * @param \mysqli $conn Conexión con la BD.
 */
function equiposSeleccionadosPorEdicion($conn){
    $query = "select e.nombre, n.user, n.edicion          
   from Equipo as e          
   inner join (          
   	select distinct m.equipo, u.nombre as user,          
       m.edicion          
       from Marcador as m          
       inner join Usuario as u          
       on m.usuario = u.id          
   ) as n          
   on e.id = n.equipo          
   order by n.edicion desc, n.user;";
    $result = mysqli_query($conn, $query);
    ?>

        <table border="1">
            <tr>
            <i>Selección de equipos</i>
            </tr>
            <tr>
                
                <td>Usuario</td>
                <td>Edición</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td><?php
                $eq = $row["nombre"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1);
                echo " $eq";
                ?></td>
                <td><?php
                $us = $row["user"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15);
                echo " $us";
                ?></td>
                <td><?= $row["edicion"] ?>ª</td>
            </tr>    
        <?php
    }
    echo "</table>";
}

/**
 * Muestra en una tabla las mayores goleadas en un partido.
 * @param \mysqli $conn Conexión con la BD
 */
function goleadasPorPartido($conn){
    $query = "select max(m.goles) as maximo_goles_partido, e.nombre, m.tipo, m.num_ed, m.edicion, m.user, m.id
   from (          
       select p.tipo, p.id, p.num_ed, p.edicion, n.goles, n.equipo, n.nombre as user          
       from (          
       	select m.goles, m.equipo, u.nombre, m.partido          
           from Usuario as u          
           inner join Marcador as m          
           on m.usuario = u.id       
       ) as n          
       inner join Partido as p          
       on p.id = n.partido          
   ) as m          
   inner join Equipo as e          
   on e.id = m.equipo          
   group by e.nombre, m.tipo, m.num_ed, m.edicion, m.id, m.user
   order by maximo_goles_partido desc          
   limit 20;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Mayores goleadas en partido</i>
            </tr>
            <tr>
                <td id="td_ucl_blue">Goles</td>
                <td colspan="2" id="td_ucl_blue">Equipo</td>
                
                <td id="td_ucl_blue">Edición</td>
                <td id="td_ucl_blue">Tipo</td>
                <td colspan="2"  id="td_ucl_blue">Usuario</td>
                <td id="td_ucl_blue">#Partido</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr id="td_ucl_white">
                <td id="td_ucl_white_bold"><?= $row["maximo_goles_partido"] ?></td>
                <td><?php
                $eq = $row["nombre"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1);
                ?></td>
                <td><?= $eq ?></td>
                <td><?= $row["edicion"] ?>ª</td>
                <td><?= $row["tipo"] ?></td>
                <td><?php
                $us = $row["user"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15);
                ?></td>
                <td><?= $us ?></td>
                <td><?= $row["id"] ?>º</td>
            </tr>    
        <?php
    }
    echo "</table>";
}
/**
 * Muestra en una tabla las mayores goleadas en una edición
 * @param \mysqli $conn Conexión con la BD
 */
function goleadasPorEdicion($conn){
    $query = "select n.edicion, sum(n.goles) as suma_goles_edicion, e.nombre as equipo, n.usuario          
   from Equipo as e          
   inner join          
   (          
       select m.edicion, m.partido, m.goles, u.nombre as usuario, m.equipo           
       from Marcador as m          
       inner join Usuario as u          
       on m.usuario = u.id         
   ) as n          
   on e.id = n.equipo          
   group by equipo, n.edicion, n.usuario          
   order by suma_goles_edicion desc limit 20;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Mayores goleadas en edición</i>
            </tr>
            <tr>
                <td id="td_ucl_blue">Goles</td>
                <td  id="td_ucl_blue" colspan="2">Equipo</td>
                
                <td  id="td_ucl_blue" colspan="2">Usuario</td>
                <td id="td_ucl_blue">Edición</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr id="td_ucl_white">
                <td id="td_ucl_white_bold"><?= $row["suma_goles_edicion"] ?></td>
                
                
                <td><?php
                $eq = $row["equipo"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1);
                ?></td>
                <td><?= $eq ?></td>
                <td><?php
                $us = $row["usuario"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15);
                ?></td>
                <td><?= $us ?></td>
                <td><?= $row["edicion"] ?>ª</td>
            </tr>    
        <?php
    }
    echo "</table>";
}
/**
 * Veces en que un usuario ha ganado con un mismo equipo
 * @param \mysqli $conn Conexión con la BD
 */
function palmaresEquipoUsuario($conn){
    $query = "select count(a.partido) as BC_ganadas_equipo_jugador, a.equipo, a.jugador          
   from (          
   	select m.goles, m.partido, e.nombre as equipo, e.id, m.nombre as jugador          
       from Equipo as e          
       inner join (          
       	select m.goles, m.equipo, m.partido, m.nombre          
           from (          
           	select m.goles, m.equipo, m.partido, u.nombre          
               from Marcador as m          
               inner join Usuario as u          
               on m.usuario = u.id          
           ) as m          
           inner join Partido as p          
           on m.partido = p.id          
           where p.tipo = 'Final'          
       ) as m          
       on m.equipo = e.id          
   ) as a          
   inner join(          
       select max(n2.goles) as max_goles, min(n2.goles) as min_goles, n1.partido, n2.ganador_penaltis          
       from (          
           select m.partido, e.id          
           from Equipo as e          
           inner join Marcador as m          
           on e.id = m.equipo          
       ) as n1          
       inner join          
       (          
           select m.goles, m.equipo, p.id, p.ganador_penaltis          
           from Partido as p          
           inner join Marcador as m          
           on m.partido = p.id          
       ) as n2          
       on n1.partido = n2.id          
       group by n1.partido, n2.ganador_penaltis          
       order by n1.partido          
   ) as b          
   on a.partido = b.partido          
   where a.goles = b.max_goles and a.goles > b.min_goles or a.id = b.ganador_penaltis          
   group by a.equipo, a.jugador          
   order by BC_ganadas_equipo_jugador desc limit 10;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Más títulos por Equipo y Usuario</i>
            </tr>
            <tr>
                <td id="td_ucl_blue">Títulos</td>
                <td id="td_ucl_blue" colspan="2">Equipo</td>
                
                <td id="td_ucl_blue" colspan="2">Usuario</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr id="td_ucl_white">
                <td id="td_ucl_white_bold"><?= $row["BC_ganadas_equipo_jugador"] ?></td>
                <td><?php
                $eq = $row["equipo"];
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1); ?></td>
                <td><?= $eq ?></td>
                <td><?php
                $us = $row["jugador"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15); ?></td>
                <td><?= $us ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}
/**
 * Muestra todos los equipos seleccionados en cada edición
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 */
function equiposSeleccionadosUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select distinct e.nombre, n.edicion
from Equipo as e
inner join (
	select m.equipo, m.edicion
    from Marcador as m
    inner join Usuario as u
    on m.usuario = u.id
    where u.nombre = '".$nombre."'
) as n
on e.id = n.equipo
order by n.edicion desc;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Equipos seleccionados por edición</i>
            </tr>
            <tr>
                <td id="td_ucl_blue">Edición</td>
                <td id="td_ucl_blue" colspan="2">Equipo</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td id="td_ucl_blue"><?= $row["edicion"] ?>ª</td>
                <td id="td_ucl_white"><?php
                $eq = $row["nombre"];
                echo "$eq";
                ?></td>
                <td id="td_ucl_white"><?php
                getImagenEquipoID($conn, getIDEquipo($conn, $eq), 0.1);
                ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}

/**
 * Muestra en una tabla las finales jugadas por cada usuario
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 */
function finalesUsuario($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select m.partido
   from Usuario as u          
   inner join           
   (          
   	select m.partido, m.usuario
       from Partido as p          
       inner join Marcador as m          
       on p.id = m.partido          
       where p.tipo = 'Final'          
   ) as m          
   on u.id = m.usuario          
   where u.nombre = '".$nombre."';";
    $result = mysqli_query($conn, $query);
    echo "<table id=\"tabla_partidos\"><tr><i>Finales jugadas</i></tr>";
    while($row = mysqli_fetch_assoc($result)){
            getFilaPartido($conn, $row["partido"]);
    }
    echo "</table>";
}
/**
 * Muestra una tabla con los goles encajados en cada edición a un usuario.
 * @param \mysqli $conn Conexión con la BD
 * @param integer $usuario ID del usuario
 */
function golesEncajadosUsuarioEdicion($conn, $usuario){
    $nombre = getUsuarioFromID($conn, $usuario);
    $query = "select sum(b.goles) as suma_goles, b.edicion
from (
	select m.partido
    from Marcador as m
    inner join Usuario as u
    on m.usuario = u.id
    where u.nombre = '".$nombre."'
) as a
inner join (
	select m.partido, m.goles, m.edicion
    from Marcador as m
    inner join Usuario as u
    on m.usuario = u.id
    where u.nombre != '".$nombre."'
) as b
on a.partido = b.partido
group by b.edicion
order by suma_goles desc limit 10;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Goles encajados por edición</i>
            </tr>
            <tr>
                <td id="td_ucl_blue">Edición</td>
                <td id="td_ucl_blue">Goles encajados</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr>
                <td id="td_ucl_blue"><?= $row["edicion"] ?>ª</td>
                <td id="td_ucl_white"><?= $row["suma_goles"] ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}
/**
 * Muestra una tabla con los goles marcados por cada  usuaruo con un mismo equipo
 * @param \mysqli $conn Conexión con la BD
 * @param integer $equipo ID del equipos
 */
function golesEquipoEdicionUsuario($conn, $equipo){
    $nombre = getNombreEquipo($conn, $equipo);
    $query = "select a.edicion, sum(a.goles) as suma_goles_edicion, a.equipo, b.usuario     
 from(     
 	select m.edicion, m.goles, m.usuario, e.nombre as equipo, m.partido     
     from Equipo as e     
     inner join Marcador as m     
     on m.equipo = e.id
     where e.nombre = '".$nombre."'     
 ) as a     
 inner join     
 (     
 	select m.partido, u.id, u.nombre as usuario     
     from Usuario as u     
     inner join Marcador as m     
     on m.usuario = u.id    
 ) as b     
 on a.partido = b.partido     
 where a.usuario = b.id
 group by a.edicion, b.usuario, a.equipo    
 order by suma_goles_edicion desc;";
    $result = mysqli_query($conn, $query);
    ?>

        <table>
            <tr>
            <i>Goles marcados por edición</i>
            </tr>
            <tr id="td_ucl_blue">
                <td>Edición</td>
                <td colspan="2">Usuario</td>
                <td>Goles</td>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        ?>
            <tr id="td_ucl_white">
                <td><?= $row["edicion"] ?>ª</td>
                <td><?php
                $us = $row["usuario"];
                getImagenUsuario(getIDUsuario($conn, $us), 0.15);
                ?></td>
                <td><?= $us ?></td>
                <td id="td_ucl_white_bold"><?= $row["suma_goles_edicion"] ?></td>
            </tr>    
        <?php
    }
    echo "</table>";
}