<?php
	/* asigna datos a HTML */
	function cargarHTML($correo, $nombre, $celular) {
		global $HTML_FILA, $cantidad;
		$cantidad++;
		$HTML_FILA .= '<tr>';
		$HTML_FILA .= '<td>' . $nombre . '</td>';
		$HTML_FILA .= '<td align="center">' . $correo . '</td>';
		$HTML_FILA .= '<td align="center">';
		$HTML_FILA .= (!empty($celular)) ? $celular  : '--';
		$HTML_FILA .= '</td>';
		$HTML_FILA .= '</tr>';
	}
	
	/* calculo primer nivel */
	function calcularNivelZero($id_compra) {
		/* obtengo cantidad de ventas de cada uno de mis hijos */
		$q01 = "SELECT 
			c.id_persona, c.orden, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, cu.email correo, cc.id_compra_hijo
		FROM compras AS c
		INNER JOIN cuentas cu ON (c.id_persona = cu.id_cuenta)
		LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
		WHERE c.id_compra_padre = $id_compra
		ORDER BY c.id_compra";
		
		$s01 = mysql_query($q01) or die(mysql_error());
		$total_compras_nivel_1 = mysql_num_rows($s01);
		if($total_compras_nivel_1) {
			
			calcularPrimerNivel($q01);
			
			if($_REQUEST[nivel]==0) {	
				/* recorro cada uno demis hijos - verifico si hay reventa */
				while($r = mysql_fetch_array($s01)) {
					if($r[orden] > 3) {
						if(eregi("^vres$", $_REQUEST[tipo_venta])){
							 cargarHTML($r[correo], $r[nombre], $r[celular]);
						}
					}
				}
			}
		}	
	}
	
	/* calculo primer nivel */
	function calcularPrimerNivel($q01) {
		global $id_compra;
		
		$s01 = mysql_query($q01);
		if(mysql_num_rows($s01) > 1) {

			/* recorro cada uno de mis hijos */	
			while ($r01 = mysql_fetch_array($s01)) {
				$total_compras_nivel_2 = 0;
				$id_compra_nivel_2 = $r01[id_compra];
				
				/* obtengo cantidad de ventas de cada uno de mis hijos */
				$q02 = "SELECT 
					c.id_persona, c.orden, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, cu.email correo, cc.id_compra_hijo
				FROM compras AS c
				INNER JOIN cuentas cu ON (c.id_persona = cu.id_cuenta)
				LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
				WHERE c.id_compra_padre = $id_compra_nivel_2
				ORDER BY c.id_compra";
				
				$s02 = mysql_query($q02) or die(mysql_error());
				$total_compras_nivel_2 = mysql_num_rows($s02);
				if($total_compras_nivel_2) {

					calcularSegundoNivel($q02);
					
					if($_REQUEST[nivel]==1) {
						while($r = mysql_fetch_array($s02)) {
							if(eregi("^vres$", $_REQUEST[tipo_venta])){
								if($r[id_compra_hijo]<1 && $r[orden] > 3) cargarHTML($r[correo], $r[nombre], $r[celular]);
							}
						}
					}
				}
			}
		}
	}
	
	/* calculo segundo nivel */
	function calcularSegundoNivel($sql) {
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r02 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_3 = 0;
			$id_compra_nivel_3 = $r02[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT 
				c.id_persona, c.orden, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, cu.email correo, cc.id_compra_hijo
			FROM compras AS c
			INNER JOIN cuentas cu ON (c.id_persona = cu.id_cuenta)
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_3 
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_3 = mysql_num_rows($s03);
			if($total_compras_nivel_3) {			
				
				calcularTercerNivel($q03);
				
				/* verifico si hay reventa */
				if($_REQUEST[nivel]==2) {
					while($r = mysql_fetch_array($s03)) {
						if(eregi("^vres$", $_REQUEST[tipo_venta])){
							if($r[id_compra_hijo]<1 && $r[orden] > 3)  cargarHTML($r[correo], $r[nombre], $r[celular]);
						}
					}
				}							
			}			
		}	
	}
	
	/* calculo tercer nivel */
	function calcularTercerNivel($sql) {
		$consulta = mysql_query($sql) or die(mysql_error());	
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT 
				c.id_persona, c.orden, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, cu.email correo, cc.id_compra_hijo
			FROM compras AS c
			INNER JOIN cuentas cu ON (c.id_persona = cu.id_cuenta)
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_4
			ORDER BY c.id_compra";
			
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				while($r = mysql_fetch_array($s03)) {
					if(eregi("^vres$", $_REQUEST[tipo_venta])){
						if($r[id_compra_hijo]<1 && $r[orden] > 3) cargarHTML($r[correo], $r[nombre], $r[celular]);						
					}
				}		
			}			
		}			
	}
	
	/* calculo cuarto nivel */
	function calcularCuartoNivel($sql) {
		$consulta = mysql_query($sql) or die(mysql_error());	
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT 
				c.id_persona, c.orden, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, cu.email correo, cc.id_compra_hijo
			FROM compras AS c
			INNER JOIN cuentas cu ON (c.id_persona = cu.id_cuenta)
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_4 
			ORDER BY c.id_compra";
			
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] > 1) {
						if(eregi("^vcc$", $_REQUEST[tipo_venta])) {
							if(eregi("^2$", $r[orden]) && $r[id_compra_hijo]<1) cargarHTML($r[correo], $r[nombre], $r[celular]);
						} else if(eregi("^vres$", $_REQUEST[tipo_venta])){
							if($r[id_compra_hijo]<1 && $r[orden] > 3) cargarHTML($r[correo], $r[nombre], $r[celular]);
						}						
					}
				}		
			}			
		}			
	}
	
	/* muestro titulo del listado de correos */
	function prepararTitulo() {
		global $nivel;
		if(eregi("^vres$", $_REQUEST[tipo_venta]) && $_REQUEST[nivel]==0) $pcomision = 0.1;
		if(eregi("^vres$", $_REQUEST[tipo_venta]) && $_REQUEST[nivel]==1) $pcomision = 0.1;
		if(eregi("^vres$", $_REQUEST[tipo_venta]) && $_REQUEST[nivel]==2) $pcomision = 0.1;
		if(eregi("^vres$", $_REQUEST[tipo_venta]) && $_REQUEST[nivel]==3) $pcomision = 0.1;
		if(eregi("^vres$", $_REQUEST[tipo_venta]) && $_REQUEST[nivel]==4) $pcomision = 0.2;
		if(eregi("^vcc$", $_REQUEST[tipo_venta])) $pcomision = 1;		
		echo '<h1>Details ';	
		if(eregi("^vcc$", $_REQUEST[tipo_venta])) echo '<span title="ventas calificadas de comision">VCC</span>';
		if(eregi("^vres$", $_REQUEST[tipo_venta])) echo '<span title="ventas residuales">VRES</span>';		
		if(eregi("^[01234]{1}$", $_REQUEST[nivel])) echo ', Level ' . $_REQUEST[nivel];
		echo ', ' . $pcomision * 100 . '% of commission</h1>';
	}
	
	$HTML_FILA = '';
	$cantidad =0;
	
	/* $id_persona viene definido con el identificador de quien inicia sesion */
	$query00 = "SELECT 
		c.id_compra, c.fecha_inicio, p.precio, p.nombre
	FROM compras c
	INNER JOIN productos p ON (c.id_producto = p.id_producto)
	WHERE c.id_persona = $_SESSION[id_cuenta] AND c.id_compra= ". (int) $_REQUEST[id_grupo];
	$result00 = mysql_query($query00) or die(mysql_error());	
	$r = mysql_fetch_array($result00);
		$id_compra = $r[id_compra];
		$categoria = $r[nombre];
		$monto = $r[precio];
		$fecha_inicio = $r[fecha_inicio];
		
		/* esta funcion contiene calcular los siguientes niveles */
		calcularNivelZero($id_compra);
		
		/* muestro la parte superior del listado */
		//mostrarEncabezado();
		prepararTitulo();
?>
    <table class="bordes" border="1" cellpadding="0" cellspacing="0" width="100%">
        <tr>
			<td align="center"><b>Name</b></td>
		    <td align="center"><b>Email</b></td>
		    <td align="center"><b>Cellphone</b></td>
        </tr>
        <?=$HTML_FILA ?>
        <tr>
        	<td></td>
            <td align="center"><b>Total</b></td>
            <td align="center"><b><?=$cantidad ?></b></td>
        </tr>
	</table>
    <div align="center"><input onclick="window.history.back();" value="Back" type="button"></div>
    
