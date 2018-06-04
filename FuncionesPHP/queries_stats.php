<?php

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