<h1>Historical Downlines</h1>
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
		echo '<span id="estado" style="background:#' . $c . '">' . $e . '</span>';
	}

	$query00 = "SELECT 
		c.*,
		CONCAT(cu.nombres , ' ' , cu.apellidos) AS nombre
	FROM compras c
	LEFT JOIN compras cp ON (c.id_compra_padre = cp.id_compra)
	LEFT JOIN cuentas cu ON (cp.id_persona = cu.id_cuenta)
	WHERE c.id_persona = $_SESSION[id_cuenta] AND c.estado = 'cerrada'
	ORDER BY c.id_compra DESC";
	//echo $query00;
	
	$result00 = mysql_query($query00) or die(mysql_error());
	
	if (mysql_num_rows($result00) > 0) {
		echo '<ul id="referidos">';
?>
		<li id="treferidos">
			<span id="num">N&ordm;</span>
			<span id="compra">Purchase numbre</span>
			<span id="fcompra">Purchase date</span>
			<span id="fvcmto">Expiration date</span>
			<span id="srestantes">Weeks remaining</span>
			<span id="invitadopor">Invited by</span>
			<span id="estado">Status</span>
			<span id="accion">Action</span>
		</li>        
<?php
		$i = 0;
		while($r = mysql_fetch_array($result00)) {
			$i++;
?>
		<li class="lreferidos" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
			<span id="num"><?=$i ?></span>
			<span id="compra"><?=$r[id_compra] ?></span>
			<span id="fcompra"><?=date("d/m/Y", strtotime($r[fecha_compra])) ?></span>
			<span id="fvcmto"><?=date("d/m/Y", strtotime($r[fecha_vencimiento])) ?></span>
			<span id="srestantes"><?=number_format(((strtotime($r[fecha_vencimiento]) - strtotime($r[fecha_compra]))/(60*60*24*7)), "0", ".", ".") ?> weeks</span>
			<span id="invitadopor"><?=!empty($r[nombre]) ? $r[nombre] : 'sin nombre' ?></span>
			<?php retornarEstado($r[estado]); ?>
			<span id="accion"><a href="?id_pagina=21&id_compra=<?=$r[id_compra] ?>" id="arbol">tree</a></span>
		</li>
<?php
		}
		echo '</ul>';
	} else {
		echo '<div id="rechazado">Have no yet closed referrals groups.</div>';
	}	
?>
