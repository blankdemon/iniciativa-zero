<?php
	/*************************
		calculo nivel cero
		*********************/
	function calcularNivelZero($id_compra) {
		global $nivel, $monto, $uf;
		global $TOTAL_PARCIAL;
		global $VCC_NIVEL, $VRES_NIVEL1, $TOTAL_VRES_NIVEL1;
		
		$q01 = "SELECT
			c.id_compra, c.id_persona, c.orden, p.precio, cc.id_compra_hijo 
		FROM compras c
		INNER JOIN productos p ON (c.id_producto = p.id_producto)
		LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
		WHERE c.id_compra_padre = $id_compra
		ORDER BY c.id_compra";
		
		//echo $q01;
		
		$s01 = mysql_query($q01);	
		$total_compras_nivel_1 = mysql_num_rows($s01);
		if($total_compras_nivel_1 > 1) {
			$reventa = false;
			$vcc_cobrada = false;
			$compras_cobradas = 0;
			$porcentaje_comision = 0.1;
					
			calcularPrimerNivel($q01);
							
			/* verifico si hay reventa */
			while($r = mysql_fetch_array($s01)) {
				//if($r[orden] == 2 && $r[id_compra_hijo]>0) $vcc_cobrada = true;
				if($r[orden] == 3) $reventa = true;
				if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
			}
						
			/* calculo residuales */
			if($total_compras_nivel_1 > 2) {
				if($reventa) {
					$VRES_NIVEL1 = ($total_compras_nivel_1 - $compras_cobradas - 3);							
					$TOTAL_PARCIAL += ($monto * $VRES_NIVEL1 * $porcentaje_comision ); // calculo % ganancia vtas 4-20			
				} else {
					$VRES_NIVEL1 = ($total_compras_nivel_1 - $compras_cobradas - 2);
					$TOTAL_PARCIAL += ($monto * $VRES_NIVEL1 * $porcentaje_comision); // calculo % ganancia vtas 4-20
				}
				$TOTAL_VRES_NIVEL1 += $VRES_NIVEL1;
			}
		}
	}
	
	/*************************
		calculo primer nivel
		*********************/
	function calcularPrimerNivel($q01) {
		global $id_compra, $monto, $uf;
		global $TOTAL_PARCIAL;
		global $VRES_NIVEL2, $TOTAL_VRES_NIVEL2;
		
		$s01 = mysql_query($q01) or die(mysql_error());		
		if(mysql_num_rows($s01) > 1) {
								
			/* recorro cada uno de mis hijos */	
			while ($r01 = mysql_fetch_array($s01)) {
				$total_compras_nivel_2 = 0;
				$id_compra_nivel_2 = $r01[id_compra];
				
				/* obtengo cantidad de ventas de cada uno de mis hijos */
				$q02 = "SELECT 
					c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo
				FROM compras c
				INNER JOIN productos p ON (c.id_producto = p.id_producto)
				LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
				WHERE c.id_compra_padre = $id_compra_nivel_2
				ORDER BY c.id_compra";
				$s02 = mysql_query($q02) or die(mysql_error());
				$total_compras_nivel_2 = mysql_num_rows($s02);
				if($total_compras_nivel_2) {
					$compras_cobradas = 0;
					$porcentaje_comision = 0.1;
					
					calcularSegundoNivel($q02);
						
					/* verifico si hay reventa */
					while($r = mysql_fetch_array($s02)) {
						if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
					}
					
					/* calculo residuales */
					if($total_compras_nivel_2 > 2) {
						$VRES_NIVEL2 = ($total_compras_nivel_2 - $compras_cobradas - 3);
						$TOTAL_PARCIAL += ($monto * $VRES_NIVEL2 * $porcentaje_comision); // calculo 15% ganancia vtas 4-20
						$TOTAL_VRES_NIVEL2 += $VRES_NIVEL2;
					}				
				}
			}
		}
	}
	
	/***********************
		calculo segundo nivel
		*******************/
	function calcularSegundoNivel($sql) {
		global $id_compra, $monto, $uf;
		global $TOTAL_PARCIAL;
		global $VCC_NIVEL, $TOTAL_VRES_NIVEL3;
				
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r02 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_3 = 0;
			$id_compra_nivel_3 = $r02[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT 
				c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo
			FROM compras c
			INNER JOIN productos p ON (c.id_producto = p.id_producto)
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_3
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_3 = mysql_num_rows($s03);
			if($total_compras_nivel_3) {
				$compras_cobradas = 0;
				$porcentaje_comision = 0.1;
								
				/* si es nivel 3 */
				calcularTercerNivel($q03);
				
				/* verifico si hay reventa */
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
				}
				
				/* calculo residuales */
				if($total_compras_nivel_3 > 2) {
					$VRES_NIVEL3 = ($total_compras_nivel_3 - $compras_cobradas - 3);
					$TOTAL_PARCIAL += ($monto * $VRES_NIVEL3 * $porcentaje_comision); // calculo 15% ganancia vtas 4-10
					$TOTAL_VRES_NIVEL3 += $VRES_NIVEL3;			
				}				
			}			
		}
	}
	
	/***********************
		calculo tercer nivel
		*******************/
	function calcularTercerNivel($sql) {
		global $id_compra, $monto, $uf;
		global $TOTAL_PARCIAL;
		global $VCC_NIVEL, $TOTAL_VRES_NIVEL4;
		
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT 
				c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo
			FROM compras c
			INNER JOIN productos p ON (c.id_producto = p.id_producto)
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_4
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				$compras_cobradas = 0;
				$porcentaje_comision = 0.1;
				
				calcularCuartoNivel($q03);
			
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
				}
				
				/* calculo residuales */
				if($total_compras_nivel_4 > 2) {					
					$VRES_NIVEL4 = ($total_compras_nivel_4 - $compras_cobradas - 3);
					$TOTAL_PARCIAL += ($monto * $VRES_NIVEL4 * $porcentaje_comision);					
					$TOTAL_VRES_NIVEL4 += $VRES_NIVEL4;
				}
			}			
		}
	}
	
	/***********************
		calculo cuarto nivel
		*******************/
	function calcularCuartoNivel($sql) {
		global $id_compra, $monto, $uf;
		global $TOTAL_PARCIAL;
		global $VCC_NIVEL, $TOTAL_VRES_NIVEL4;
		
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_5 = 0;
			$id_compra_nivel_5 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT 
				c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo
			FROM compras c
			INNER JOIN productos p ON (c.id_producto = p.id_producto)
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_5
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_5 = mysql_num_rows($s03);
			if($total_compras_nivel_5) {
				$compras_cobradas = 0;
				$porcentaje_comision = 0.2;
			
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
				}
				
				/* calculo residuales */
				if($total_compras_nivel_5 > 2) {
					$VRES_NIVEL5 = ($total_compras_nivel_5 - $compras_cobradas - 3);
					$TOTAL_PARCIAL += ($monto * $VRES_NIVEL5 * $porcentaje_comision);					
					$TOTAL_VRES_NIVEL5 += $VRES_NIVEL5;
				}
			}			
		}
	}
	
	/* genero la lista con los ingresos  */
	function listarIngresos() {
		global $monto;
		global $VCC_NIVEL;
		global $TOTAL_VRES_NIVEL1, $TOTAL_VRES_NIVEL2, $TOTAL_VRES_NIVEL3, $TOTAL_VRES_NIVEL4, $TOTAL_VRES_NIVEL5;
		
		$total_nivel_1 = $TOTAL_VRES_NIVEL1 * 0.1 * $monto;
		$total_nivel_2 = $TOTAL_VRES_NIVEL2 * 0.1 * $monto;
		$total_nivel_3 = $TOTAL_VRES_NIVEL3 * 0.1 * $monto;
		$total_nivel_4 = $TOTAL_VRES_NIVEL4 * 0.1 * $monto;
		$total_nivel_5 = $TOTAL_VRES_NIVEL5 * 0.2 * $monto;
		
		$factor_nivel_1 = $TOTAL_VRES_NIVEL1*0.1;
		$factor_nivel_2 = $TOTAL_VRES_NIVEL2*0.1;
		$factor_nivel_3 = $TOTAL_VRES_NIVEL3*0.1;
		$factor_nivel_4 = $TOTAL_VRES_NIVEL4*0.1;
		$factor_nivel_5 = $TOTAL_VRES_NIVEL5*0.2;
				
		echo '<table width="100%" border="1" cellpadding="0" cellspacing="0" class="bordes">
		<tr>
			<td></td>
			<td><b>Groups quantity</b></td>
			<td><b>% commission</b></td>
			<td><b>Commissions by level</b></td>
			<td><b>Commissions in euros</b></td>
		</tr>';
		
		/* muestro ingresos VRES de nivel 1 */
		echo '<tr><td>' , ($TOTAL_VRES_NIVEL1) ? '<a href="?id_pagina=29&id_grupo=' . (int) $_REQUEST[id_grupo] . '&tipo_venta=vres&nivel=0">Gross Sales Level 0</a>' : 'Gross Sales Level 0' , '</td>';
		echo '<td align="center">' . $TOTAL_VRES_NIVEL1 . '</td>';
		echo '<td align="center">10%</td>';
		echo '<td align="center">' . number_format(($TOTAL_VRES_NIVEL1 * 0.1), 2, ",", ".") . '</td>';
		echo '<td align="right">&euro; ' . number_format(($TOTAL_VRES_NIVEL1 * $monto * 0.1), 2, ".", ".") . '</td></tr>';
		
			
		/* muestro ingresos VRES de nivel 2 */
		echo '<tr><td>' , ($TOTAL_VRES_NIVEL2) ? '<a href="?id_pagina=29&id_grupo=' . (int) $_REQUEST[id_grupo] . '&tipo_venta=vres&nivel=1">Gross Sales Level 1</a>' : 'Gross Sales Level 1' , '</td>';
		echo '<td align="center">' . $TOTAL_VRES_NIVEL2 . '</td>';
		echo '<td align="center">10%</td>';
		echo '<td align="center">' .number_format(($TOTAL_VRES_NIVEL2 * 0.1), 2, ",", ".") . '</td>';
		echo '<td align="right">&euro; ' . number_format(($TOTAL_VRES_NIVEL2 * $monto * 0.1), 2, ".", ".") . '</td></tr>';
		
		
		/* muestro ingresos VRES de nivel 3 */
		echo '<tr><td>' , ($TOTAL_VRES_NIVEL3) ? '<a href="?id_pagina=29&id_grupo=' . (int) $_REQUEST[id_grupo] . '&tipo_venta=vres&nivel=2">Gross Sales Level 2</a>' : 'Gross Sales Level 2' , '</td>';
		echo '<td align="center">' . $TOTAL_VRES_NIVEL3 . '</td>';
		echo '<td align="center">10%</td>';
		echo '<td align="center">' , ($nivel>3) ? number_format(($TOTAL_VRES_NIVEL3 * 0.1) , 2, ",", ".") : number_format(($TOTAL_VRES_NIVEL3 * 0.2) , 2, ",", ".") , '</td>';
		echo '<td align="right">&euro; ' , ($nivel>3) ? number_format(($TOTAL_VRES_NIVEL3 * $monto * 0.1), 2, ".", ".") : number_format(($TOTAL_VRES_NIVEL3 * $monto * 0.2), 2, ".", ".") , '</td></tr>';
		
		
		/* muestro ingresos VRES de nivel 4 */
		echo '<tr><td>' , ($TOTAL_VRES_NIVEL4) ? '<a href="?id_pagina=29&id_grupo=' . (int) $_REQUEST[id_grupo] . '&tipo_venta=vres&nivel=3">Gross Sales Level 3</a>' : 'Gross Sales Level 3' , '</td>';
		echo '<td align="center">' . $TOTAL_VRES_NIVEL4 . '</td>';
		echo '<td align="center">10%</td>';
		echo '<td align="center">' . number_format(($TOTAL_VRES_NIVEL4 * 0.1) , 2, ",", ".") . '</td>';
		echo '<td align="right">&euro; ' . number_format(($TOTAL_VRES_NIVEL4 * $monto * 0.1), 2, ".", ".") . '</td></tr>';
		
		/* muestro ingresos VRES de nivel 5 */
		echo '<tr><td>' , ($TOTAL_VRES_NIVEL5) ? '<a href="?id_pagina=29&id_grupo=' . (int) $_REQUEST[id_grupo] . '&tipo_venta=vres&nivel=4">Gross Sales Level 4</a>' : 'Gross Sales Level 4' , '</td>';
		echo '<td align="center">' . $TOTAL_VRES_NIVEL5 . '</td>';
		echo '<td align="center">20%</td>';
		echo '<td align="center">' . number_format(($TOTAL_VRES_NIVEL5 * 0.2) , 2, ",", ".") . '</td>';
		echo '<td align="right">&euro; ' . number_format(($TOTAL_VRES_NIVEL5 * $monto * 0.2), 2, ".", ".") . '</td></tr>';
		
		/* muestro ingresos de VCC */
		echo '<tr><td>' , ($VCC_NIVEL) ? '<a href="?id_pagina=29&id_grupo=' . (int) $_REQUEST[id_grupo] . '&tipo_venta=vcc">VCC</a>' : 'VCC' , '</td>';
		echo '<td align="center">' . $VCC_NIVEL . '</td>';
		echo '<td align="center">100%</td>';
		echo '<td align="center">' . number_format(($VCC_NIVEL * 1) , 2, ",", ".") . '</td>';
		echo '<td align="right">&euro; ' . number_format(($VCC_NIVEL * $monto), 2, ".", ".") . '</td></tr>';
		
		/* cargo la fila con los totales de los ingresos */
		$total_comisiones = number_format(($VCC_NIVEL*1*$monto + $total_nivel_1 + $total_nivel_2 + $total_nivel_3 + $total_nivel_4 + $total_nivel_5), 2, ",", ".");

		echo '<tr>
				<td>TOTAL</td>
				<td align="center">' . ($VCC_NIVEL + $TOTAL_VRES_NIVEL1 + $TOTAL_VRES_NIVEL2 + $TOTAL_VRES_NIVEL3 + $TOTAL_VRES_NIVEL4 + $TOTAL_VRES_NIVEL5) .'</td>
				<td align="center">--</td>
				<td align="center">' . number_format(($VCC_NIVEL*1 + $factor_nivel_1 + $factor_nivel_2 + $factor_nivel_3 + $factor_nivel_4 + $factor_nivel_5), 2, ",", ".") . '</td>
				<td align="right">&euro; ' . $total_comisiones . '</td>
			</tr>
		</table>';
	}
	
	/* genero lista con los egresos */
	function listarGastos() {
		global $TOTAL_A_PAGAR, $TOTAL_RETENCION_LEGAL;
		echo '<table width="100%" border="1" cellpadding="0" cellspacing="0" class="bordes">
			<tr>
				<td><b>Pro-rata Basis</b></td>
				<td><b>Sundry Expenses</b></td>
			</tr>
			<tr>
				<td>Credit Card Fee</td>
				<td align="right">&euro; ' . number_format($TOTAL_RETENCION_LEGAL, 2, ".", ".") . '</td>
			</tr>
			<tr>
				<td>Wire Transfer Fee</td>
				<td align="right">&euro; ' . number_format(5, 2, ".", ".") . '</td>
			</tr>
			<tr>
				<td>Accrued Charges</td>
				<td align="right">&euro; ' . number_format(($TOTAL_RETENCION_LEGAL + 5), 2, ".", ".") . '</td>
			</tr>
		</table>';
	}
	
	/* genero lista con los totales generales */
	function listarTotales() {
		global $TOTAL_GRUPO, $TOTAL_IVA, $TOTAL_RETENCION_LEGAL, $TOTAL_A_PAGAR;
		echo '<table border="1" cellpadding="0" cellspacing="0" class="bordes" align="right">
			<tr>
				<td colspan="3" align="right"><b>Gross Sales</b></td>
				<td><b>&euro; ' . number_format($TOTAL_GRUPO, 2, ".", ".") . '</b></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><b>Total Expenses</b></td>
				<td><b>&euro; ' . number_format($TOTAL_RETENCION_LEGAL, 2, ".", ".") . '</b></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><b>Balance</b></td>
				<td><b>&euro; ' . number_format($TOTAL_A_PAGAR, 2, ".", ".") . '</b></td>
			</tr>
		</table>';	
	}
	
	/* listar tabla de detalles */
	function listarDetalles() {
		echo '<h1>Downline Nº ' . (int) $_REQUEST[id_grupo] . ' details</h1>
		<table class="listados" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><b>Revenues</b></td>
				<td style="padding-left:10px;"><b>Discount Account</b></td>
			</tr>
			<tr>
				<td valign="top">' , listarIngresos() , '</td>
				<td valign="top" style="padding-left:10px;">' , listarGastos() , '</td>
			</tr>
			<tr>
			  <td colspan="2" align="center">' , listarTotales() , '</td>
			</tr>
			<tr>
				<td colspan="3" style="padding-top: 20px;" align="center"><input class="rojo" onclick="window.history.back();" onmouseover="this.className=\'verde\'" onmouseout="this.className=\'rojo\'" value="Back" type="button"></td>
			</tr>  
		</table>';
	}
	
	
	
	/********************************************************************************************************************************/
	/********************************************************************************************************************************/
	
	
	/*determina comision recaudadores*/
	$COMISION_RECAUDADORES_MASIVOS = 0.03;
	
	/* contendra el total general de honorarios por cobrar */
	$TOTAL_PARCIAL = 0;
	
	/* almacena el descuento total de las boletas de pago */
	$TOTAL_COSTO_BOLETAS_PAGO = 0;
	
	/* totales para columna ingresos */
	$VCC_NIVEL = 0;
	$TOTAL_VCC_NIVEL = 0;	
	$TOTAL_VRES_NIVEL2 = 0;
	$TOTAL_VRES_NIVEL3 = 0;
	$TOTAL_VRES_NIVEL4 = 0;
	$TOTAL_VRES_NIVEL5 = 0;
	
	/* variables de totales generales */
	$TOTAL_PARCIAL = 0;
	$TOTAL_GENERAL_RETENCION = 0;
	$TOTAL_GENERAL_A_PAGAR = 0;
	$TOTAL_GRUPO = 0;
		
	/* $id_persona viene definido con el identificador de quien inicia sesion */
	$query00 = "SELECT 
		c.id_compra, p.precio, p.nombre
	FROM compras c
	INNER JOIN productos p ON (c.id_producto = p.id_producto)
	WHERE c.id_persona = $_SESSION[id_cuenta] AND c.id_compra= ". (int) $_REQUEST[id_grupo];
	$result00 = mysql_query($query00) or die(mysql_error());
		
	$r = mysql_fetch_array($result00);
		$id_compra = $r[id_compra];
		$categoria = $r[nombre];
		$monto = $r[precio];
		
		/* esta funcion contiene calcular los siguientes niveles */
		calcularNivelZero($id_compra);
			
		/* calculo los totales para imprimir */
		$TOTAL_GRUPO = $TOTAL_PARCIAL;
		$TOTAL_VENTA_NETA = $TOTAL_GRUPO;
		$TOTAL_RETENCION_LEGAL = ($TOTAL_VENTA_NETA * 0.1);
		$TOTAL_A_PAGAR = ($TOTAL_VENTA_NETA - $TOTAL_RETENCION_LEGAL);
		
		// mostrarEncabezado();
		listarDetalles();
?>    
