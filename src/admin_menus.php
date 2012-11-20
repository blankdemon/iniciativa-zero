<h1>Administracion de menus</h1>
<table width="100%" border="0">
<?php
	$q = "SELECT * FROM menu";
	$s = mysql_query($q);
	
	if(mysql_num_rows($s)) {
?>
  <tr>
    <td width="3%">N&ordm;</td>
    <td width="5%" align="center">ID</td>
    <td width="13%">Nombre</td>
    <td width="27%">Descripcion</td>
    <td width="15%">Pagina</td>
    <td width="6%">En menu</td>
    <td width="5%">Linkbl.</td>
    <td width="12%">Menu Padre</td>
    <td width="5%">Nivel</td>
    <td width="9%">Accion</td>
  </tr>
<?php
		while($r = mysql_fetch_array($s)) {
			$c++;
?>
          <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
            <td><?=$c ?></td>
            <td align="center"><?=$r[id_menu] ?></td>
            <td><?=$r[nombre] ?></td>
            <td><?=$r[descripcion] ?></td>
            <td><?=$r[pagina] ?>.php</td>
            <td align="center"><?=($r[en_menu]) ? 'si' : 'no' ?></td>
            <td align="center"><?=($r[linkeable]) ? 'si' : 'no' ?></td>
            <td><?=$r[id_menu_pertenece] ?></td>
            <td align="center"><?=$r[nivel_acceso] ?></td>
            <td>accion</td>
          </tr>
<?php
		}
?>
</table>
<?php
	} else {
		echo '<div  id="rechazado">Actualmente no hay peliculas ingresadas en la base de datos.</div>';
	}
?>
	<div id="opciones">
    	<a id="ipelicula" href="paginas/ingresar_pelicula.php">agregar</a>
<?php
		if(mysql_num_rows($s)) echo '<a href="javascript:alert(\'eliminando...\');">eliminar</a>';
?>
    </div>

