<?php
	if(eregi("^rechazar$", $_REQUEST[action])) rechazarInvitacion();
?>
<h1>Buy</h1>
<?php
	function cargarLinea($row) {
		global $productos_contador, $orden_invitacion;
		if($row) {
			$c = 0;	
						
			while($r = mysql_fetch_array($row)) {
				$c++;
				
				echo '<li class="lproductos" onmouseover="uno(this, \'DFEFFF\')" onmouseout="dos(this, \'ffffff\')">
					<span id="numero">' . $c . '</span>
					<span id="nombre">' . $r[nombre] . '</span>
					<span id="descripcion">' .$r[descripcion] . '</span>
					<span id="precio">&euro; ' . number_format($r[precio], 2, ".", ".") . '</span>
					<span id="accion">';
				
				if($orden_invitacion!=3)
					echo '<a href="paginas/formas_pago.php?id_invitacion=' . (int) $_REQUEST[id_invitacion] . '&id_producto=' . $r[id_producto] . '" class="cproducto">buy</a>';
				else
					echo '<a href="?id_pagina=17&id_invitacion=' . (int) $_REQUEST[id_invitacion] . '&id_producto=' . $r[id_producto] . '&order=third" class="ctproducto">buy</a>';
				
				echo '</span>
				</li>';
    		}
			
			/* incremento para añadir el contador correctamente a la fila siguiente en vacio */
			$productos_contador = $c;
			$c++;			
		}
	}
	
	
	
	/* obtiene el orden de la invitacion para generar la compra inmediatamente o no */
	$query = "SELECT 
		i.orden
	FROM invitaciones AS i
	INNER JOIN cuentas AS c ON (i.correo = c.email)
	INNER JOIN cuentas AS ci ON (i.id_persona = ci.id_cuenta)
	WHERE c.id_cuenta = $_SESSION[id_cuenta] AND i.id_invitacion = " . (int) $_REQUEST[id_invitacion];
	$result = mysql_query($query) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		$r = mysql_fetch_array($result);
		$orden_invitacion = $r[orden];
	}
        
	/* selecciono las productos para desplegar */
	$query = "SELECT p.* FROM productos p ORDER BY p.orden";
	$result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		echo '
		<ul id="productos">
			<li id="tproductos">
				<span id="numero">N&ordm;</span>
				<span id="nombre">Name</span>
				<span id="descripcion">Description</span>
				<span id="precio">Price</span>
				<span id="accion">Action</span>
			 </li>';
		
			cargarLinea($result);
			
		echo '</ul>';
	} else {
		echo '<div id="rechazado">No products availables.</div>';
	}
?>
