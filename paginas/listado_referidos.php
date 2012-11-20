<h1>My Downlines</h1>
<?php
	function retornarEstado($e) {
		switch($e) {
			case 'habilitada':
				$c = '00ff00';
			break;
			default:
				$c = 'ff0000';
			break;
		}

		$e = str_replace("cerrada", "closed", $e);
		$e = str_replace("habilitada", "enabled", $e);
		echo '<span id="estado" style="background:#' . $c . '">' . $e . '</span>';
	}
	
	function generateThirdPurchase() {
		global $aceptado, $rechazado;
	
		/* obtiene el orden de la invitacion para generar la compra inmediatamente o no */
		$query = "SELECT 
			i.nombre , i.apellido, i.id_compra, i.id_invitacion, i.estado
		FROM invitaciones AS i
		INNER JOIN cuentas AS c ON (i.correo = c.email)
		INNER JOIN cuentas AS ci ON (i.id_persona = ci.id_cuenta)
		WHERE c.id_cuenta = $_SESSION[id_cuenta] AND i.orden = 3 AND i.id_invitacion = " . (int) $_REQUEST[id_invitacion];
		$s01 = mysql_query($query) or die(mysql_error());

		if(mysql_num_rows($s01)) {
			$r = mysql_fetch_array($s01);
			$id_compra_padre = $r[id_compra];
			$fecha_vencimiento = (mktime() + 60*60*24*365);
			
			$qqq = "DELETE FROM invitaciones WHERE id_invitacion = " . (int) $_REQUEST[id_invitacion];
			if(mysql_query($qqq)) {
				$q = "INSERT INTO compras (fecha_compra, hora_compra, id_persona, fecha_inicio, fecha_vencimiento, fecha_pago, hora_pago, forma_pago, id_producto, pagado, id_compra_padre, estado)
				VALUES
				('" . date("Y-m-d") . "', '" . date("H:i:s") . "', '$_SESSION[id_cuenta]', '" . date("Y-m-d") . "', '" . date("Y-m-d", $fecha_vencimiento) . "', '" . date("Y-m-d") . "', '" . date("H:i:s") . "', '1', '" . (int) $_REQUEST[id_producto] . "', '1', '$id_compra_padre', '2')";
				if(mysql_query($q))
					$aceptado = 'The invitation has been processed successfully, your purchase was generated.';
				else 
					$rechazado = 'The invitation was not processed successfully, something was wrong, please try again. Your purchase was not generated.';
					
			} else {
				$rechazado = 'The invitation was not processed successfully, something was wrong, please try again. Your purchase was not generated.';
			}
		} else {
			$rechazado = 'The invitation that you are processing do not exist. Your purchase was not generated.';
		}
	}

	if(!eregi("^third$", $_REQUEST[order])) {
		if(($_SESSION[id_invitacion_pagar] != $_REQUEST[id_invitacion]) && $_REQUEST[aceptado]==true) {
			echo '<div id="rechazado">You do not have allowed to do this operation. Try to pay the invitation correctly.</div>';
		} else {
			$q01 = "SELECT nombre , apellido, id_compra, id_invitacion, estado FROM invitaciones WHERE id_invitacion = " . (int) $_REQUEST[id_invitacion];	
			$s01 = mysql_query($q01);
			if(mysql_num_rows($s01)) {
				$r = mysql_fetch_array($s01);
				$id_compra_padre = $r[id_compra];
				$fecha_vencimiento = (mktime() + 60*60*24*365);
				
				$q = "INSERT INTO compras (fecha_compra, hora_compra, id_persona, fecha_inicio, fecha_vencimiento, 
				fecha_pago, hora_pago, forma_pago, id_producto, pagado, id_compra_padre, estado)
				VALUES
				('" . date("Y-m-d") . "', '" . date("H:i:s") . "', '$_SESSION[id_cuenta]', '" . date("Y-m-d") . "', '" . date("Y-m-d", $fecha_vencimiento) . "',
				'" . date("Y-m-d") . "', '" . date("H:i:s") . "', '1', '" . (int) $_REQUEST[id_producto] . "', '1', '$id_compra_padre', '2')";
				if(mysql_query($q)) {
					$qqq = "DELETE FROM invitaciones WHERE id_invitacion = " . (int) $_REQUEST[id_invitacion];
					mysql_query($qqq);
					
					$aceptado = 'The invitation was paid successful, you Purchase was generated.';
					unset($_SESSION[id_invitacion_pagar]);
				}
			}		
		}
	} else {
		generateThirdPurchase();
	}
	
	
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado)) echo '<div id="rechazado">' . $rechazado . '</div>';
	
	$q = "SELECT 
		c.*,
		CONCAT(cu.nombres , ' ' , cu.apellidos) AS nombre
	FROM compras c
	LEFT JOIN compras cp ON (c.id_compra_padre = cp.id_compra)
	LEFT JOIN cuentas cu ON (cp.id_persona = cu.id_cuenta)
	WHERE c.id_persona = $_SESSION[id_cuenta]
	ORDER BY c.id_compra DESC";
	$result00 = mysql_query($q) or die(mysql_error());

	if (mysql_num_rows($result00) > 0) {
		echo '<ul id="referidos">';
?>
		<li id="treferidos">
        	<span id="num">Number</span>
		    <span id="compra">Purchase Number</span>
		    <span id="fcompra">Purchase Date</span>
		    <span id="fvcmto">Expiration Date</span>
		    <span id="srestantes">Weeks remaining</span>
		    <span id="invitadopor">Invited by</span>
		    <span id="estado">State</span>
		    <span id="accion">Action</span>
		</li>        
<?php
		$i = 0;
		$cmn = mysql_num_rows($result00)+1; //muestra la cantidad de compras hechas, sumando uno para iniciar en 1 y no en cero.
		while($r = mysql_fetch_array($result00)) {
			$i++;
?>
			<li class="lreferidos" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
		        <span id="num"><?=$i ?></span>
		        <span id="compra"><?=$r[id_compra] ?></span>
		        <span id="fcompra"><?=date("d/m/Y", strtotime($r[fecha_compra])) ?></span>
		        <span id="fvcmto"><?=date("d/m/Y", strtotime($r[fecha_vencimiento])) ?></span>
		        <span id="srestantes"><?=number_format(((strtotime($r[fecha_vencimiento]) - strtotime($r[fecha_compra]))/(60*60*24*7)), "0", ".", ".") ?> week(s)</span>
		        <span id="invitadopor"><?=!empty($r[nombre]) ? $r[nombre] : 'without name' ?></span>
		        <?php retornarEstado($r[estado]); ?>
		        <span id="accion">
<?php
                    if($r[estado]!= 'cerrada') echo '<a href="?id_pagina=20&id_compra=' . $r[id_compra] . '" id="invitar">invite</a>';
?>
                	<a href="?id_pagina=21&id_compra=<?=$r[id_compra] ?>" id="arbol">tree</a>
                </span>
            </li>
<?php
		}
		echo '</ul>';
	} else {
		echo '<div id="rechazado">Actually you do not have Referrals. First need receive invitations.</div>';
	}
?>
