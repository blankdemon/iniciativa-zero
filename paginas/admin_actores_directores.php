<h1>Administraci&oacute;n de Actores y Directores</h1>
<form name="form1" id="buscador" method="post" action="">
    <input type="text" name="words" id="words" />
    <input type="submit" name="action" id="button" value="buscar" />
    <div id="orden">
    	<a href="?id_pagina=38">Ingresar Artista</a>
	</div>
</form>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado) && !isset($_REQUEST[id_invitacion])) echo '<div id="rechazado">' . $rechazado . '</div>';

	$q = "SELECT 
		a.id_artista, a.tipo_artista, a.nombre, 
		(SELECT COUNT(*) FROM imagen_artista WHERE id_artista = a.id_artista) AS numimg
	FROM cart_artista a";
	
	if(eregi("^buscar$", $_REQUEST[action])) {
		$q .= " WHERE ";
		$q .= " a.nombre LIKE '%$_REQUEST[words]%'";
	}
	
	$q .= " ORDER BY a.nombre";
	
	if(!eregi("^buscar$", $_REQUEST[action])) $q .= " LIMIT 0, 30";
	$s = mysql_query($q) or die(mysql_error());	
	if(mysql_num_rows($s)) {
?>
		<ul id="artistas">        
            <li id="tartistas">
                <span id="num">N&ordm;</span>
                <span id="nombre">Nombre</span>
                <span id="tartista">Tipo Artista</span>
                <span id="tartista">Num. Imagenes</span>
                <span id="accion">Acci&oacute;n</span>
            </li>
<?php
			while($r = mysql_fetch_array($s)) {
				$c++;
?>
				<li class="lartistas" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
                    <span id="num"><?=$c ?></span>
                    <span id="nombre"><?=$r[nombre] ?></span>
                    <span id="tartista"><?=$general->listarTipoArtista($r[tipo_artista], true) ?></span>
                    <span id="numimg"><?=($r[numimg]>0) ? $r[numimg] . ' imagen(es)' : 'sin imagenes' ?></span>
                    <span id="accion"><a href="?id_pagina=38&id_artista=<?=$r[id_artista] ?>&action=update" class="actualizar" title="modificar artista">&nbsp;</a></span>
                </li>
<?php
		}
?>
		</ul>
<?php
	} else {
		echo '<div  id="rechazado">No se han encontrado resultados de busqueda.</div>';
	}
?>