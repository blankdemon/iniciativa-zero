<?php	
	/* contendra el total general de honorarios por cobrar */
	$TOTAL_PARCIAL = 0;
	
	/* almacena el descuento total de las boletas de pago */
	$TOTAL_COSTO_BOLETAS_PAGO = 0;
	
		
	/*************************
		calculo primer nivel
		*********************/
	function calcularNivelCero($id_compra = 0) {
		global $TOTAL_PARCIAL, $monto, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		
		/* obtengo cada uno de mis hijos */
		$q01 = "SELECT c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra
			ORDER BY c.id_compra";
		
		// echo $q01;		
		
		$s01 = mysql_query($q01);
		$total_ventas_nivel_1 = mysql_num_rows($s01);
		if($total_ventas_nivel_1 > 0) {
			$reventa = false;
			$compras_cobradas = 0;
			$porcentaje_comision = 0.1;
			
			/* verifico si hay reventa */
			while($r = mysql_fetch_array($s01)) {
				//if($r[orden] == 2) $TOTAL_PARCIAL += $monto;
				//if($r[orden] == 3) $reventa = true;
				if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
			}
			mysql_free_result($s01);
				
			/* calculo residuales */
			if($total_ventas_nivel_1 > 2) 
				$TOTAL_PARCIAL += ($monto * ($total_ventas_nivel_1 - $compras_cobradas - 3) * $porcentaje_comision); // calculo % ganancia vtas 4-20
			
			//echo "Monto: " . $monto . "<br />";
			//echo "Num. Ventas: " . $total_ventas_nivel_1 . "<br />";
			//echo "Compras Cobradas: " . $compras_cobradas . "<br />";
			//echo "Porcentaje Comision: " . $porcentaje_comision . "<br />";
			
			calcularPrimerNivel($q01);

		}
	}
	
	/*************************
		calculo segundo nivel
		*********************/
	function calcularPrimerNivel($q01) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		
		/* obtengo cada uno de mis hijos */
		$s01 = mysql_query($q01);
		$total_ventas_nivel_1 = mysql_num_rows($s01);	
		if($total_ventas_nivel_1 > 0) {			
					
			/* recorro cada uno de mis hijos */	
			while ($r01 = mysql_fetch_array($s01)) {
				$total_compras_nivel_2 = 0;
				$id_compra_nivel_2 = $r01[id_compra];
				
				/* obtengo cantidad de ventas de cada uno de mis hijos */
				$q02 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
				FROM compras c
				LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
				WHERE c.id_compra_padre = $id_compra_nivel_2
				ORDER BY c.id_compra";
				$s02 = mysql_query($q02) or die(mysql_error());
				$total_compras_nivel_2 = mysql_num_rows($s02);
				if($total_compras_nivel_2) {
					$reventa = false;
					$compras_cobradas = 0;
					$porcentaje_comision = 0.1;
					
					calcularSegundoNivel($q02);
							
					/* verifico si hay reventa */
					while($r = mysql_fetch_array($s02)) {
						//if($r[orden] == 2) $TOTAL_PARCIAL += $monto;
						if($r[orden] == 3) $reventa = true;
						if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
					}
					
					/* calculo residuales */
					if($total_compras_nivel_2 > 2) {
						if($reventa) {									
							$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_2 - $compras_cobradas - 3) * $porcentaje_comision); // calculo 15% ganancia vtas 4-10
						} else {
							$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_2 - $compras_cobradas - 2) * $porcentaje_comision); // calculo 15% ganancia vtas 4-10
						}
					}				
				}
			}
		}
	}
	
	/***********************
		calculo tercer nivel
		*******************/
	function calcularSegundoNivel($sql) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $uf, $TOTAL_COSTO_BOLETAS_PAGO;		
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r02 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_3 = 0;
			$id_compra_nivel_3 = $r02[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_3
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_3 = mysql_num_rows($s03);
			if($total_compras_nivel_3) {
				$reventa = false;
				$compras_cobradas = 0;
				$porcentaje_comision = 0.1;
								
				/* si es nivel 4 */
				calcularTercerNivel($q03);
				
				/* verifico si hay reventa */
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] == 2 && $r[id_compra_hijo]>0) $vcc_cobrada = true;
					if($r[orden] == 3) $reventa = true;
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
				}

				/* asigno VCC */
				//if(!$vcc_cobrada && ($total_compras_nivel_4 > 1)) {
				//	$TOTAL_PARCIAL += $monto; // agrego una venta
				//}
				
				/* calculo residuales */
				if($total_compras_nivel_3 > 2) {
					if($reventa) {
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_3 - $compras_cobradas - 3) * $porcentaje_comision); // calculo % ganancia vtas 4-20
					} else {
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_3 - $compras_cobradas - 2) * $porcentaje_comision); // calculo 15% ganancia vtas 4-20
					}					
				}				
			}			
		}
	}
	
	/***********************
		calculo cuarto nivel
		*******************/
	function calcularTercerNivel($sql) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras AS c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre=$id_compra_nivel_4
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				$reventa = false;
				$compras_cobradas = 0;
				$porcentaje_comision = 0.1;
				
				calcularCuartoNivel($q03);
				
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] == 3) $reventa = true;
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
				}
				
				/* calculo residuales */
				if($total_compras_nivel_4 > 2) {
					if($reventa) {
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_4 - $compras_cobradas - 3) * $porcentaje_comision);
					} else {
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_4 - $compras_cobradas - 2) * $porcentaje_comision);
					}
				}
			}			
		}
	}
	
	function calcularCuartoNivel($sql) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras AS c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre=$id_compra_nivel_4
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				$reventa = false;
				$vcc_cobrada = false;
				$compras_cobradas = 0;
				$porcentaje_comision = 0.2;
			
				while($r = mysql_fetch_array($s03)) {
					if($r[orden] == 3) $reventa = true;
					if($r[orden] == 2 && $r[id_compra_hijo]>0) $vcc_cobrada = true;
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
				}
				
				/* asigno VCC */
				if(!$vcc_cobrada && ($total_compras_nivel_4 > 1)) {
					$TOTAL_PARCIAL += $monto; // agrego una venta
				}
					
				/* calculo residuales */
				if($total_compras_nivel_4 > 2) {
					if($reventa) {
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_4 - $compras_cobradas - 3) * $porcentaje_comision);
					} else {
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_4 - $compras_cobradas - 2) * $porcentaje_comision);
					}
				}
			}			
		}
	}
	
	/* despliego mensaje de error */
	function mostrarMensajeFormadePagoGuardada() {
		echo '<table border="0" cellspacing="0" cellpadding="0" width="100%" class="listados">
		  <tr>
		    <td style="color:green;text-align:left;padding:8px;"><b>Informacion!</b><br />Su forma de pago ha sido asignada correctamente. Por favor, clickee nuevamente "Cobrar" para efectuar su cobro.</td>
		  </tr>
		</table>
		<br />';
	}	
	
	



	/* 
		CADA COMPRA que hace esta persona se contempla como GRUPO
		$id_persona viene definido con el identificador de quien inicia sesion
	*/
	
	echo '<h1>My Commissions</h1>';
	
	$q = "SELECT * FROM cobro WHERE id_persona = $_SESSION[id_cuenta] AND estado = 0";
	$s = mysql_query($q) or die(mysql_error());
	if(mysql_num_rows($s)) $ha_cobrado = true;
		
	$query00 = "SELECT 
		c.id_compra, p.precio, p.nombre
	FROM compras c
	INNER JOIN productos p ON (c.id_producto = p.id_producto)
	WHERE (c.fecha_pago != '0000-00-00' AND c.fecha_pago BETWEEN '" . date("Y-m-d", (mktime() - (60*60*24*365))) . "' AND '" . date("Y-m-d", (mktime() - (date("d")*60*60*24))) . "') AND 
		c.id_persona = $_SESSION[id_cuenta] AND estado != 'cerrada'
	ORDER BY c.id_compra";
	
	//echo '<pre>' . $query00 . '</pre>';
	
	$result00 = mysql_query($query00) or die(mysql_error());
	$total_mis_ventas = mysql_num_rows($result00);

	//echo $total_mis_ventas;
	
	if ($total_mis_ventas) {
		$i = -1;
		$TOTAL_PARCIAL = 0;
		$HTML_LISTADO = '';
		$TOTAL_GENERAL_RETENCION = 0;
		$TOTAL_GENERAL_A_PAGAR = 0;
		
		$totales_por_grupo = array();
		
		while($fila00 = mysql_fetch_array($result00)) {
			$i++;
			$TOTAL_GRUPO = 0;
			$id_compra = $fila00[id_compra];
			$monto = $fila00[precio];
			
			/* esta funcion contiene calcular los siguientes niveles */
			calcularNivelCero($id_compra);
			
			/* pusheo valores de cada grupo */
			array_push($totales_por_grupo, $TOTAL_PARCIAL);
			
			/* calculo los totales para imprimir */
			$TOTAL_GRUPO = ($TOTAL_PARCIAL - $totales_por_grupo[($i-1)]);
			$TOTAL_VENTA_NETA = $TOTAL_GRUPO;
			$TOTAL_RETENCION_LEGAL = ($TOTAL_VENTA_NETA * 0.1);
			$TOTAL_A_PAGAR = ($TOTAL_VENTA_NETA - $TOTAL_RETENCION_LEGAL);
			
			$TOTAL_GENERAL_RETENCION += $TOTAL_RETENCION_LEGAL;
			$TOTAL_GENERAL_A_PAGAR += $TOTAL_A_PAGAR;
			
			$html = '<li class="lcomisiones" onmouseover="uno(this, \'DFEFFF\')" onmouseout="dos(this, \'ffffff\')">
				<span id="grupo">' . $fila00[id_compra] . '</span>
				<span id="categoria">';
			$html .= (!$TOTAL_GRUPO) ? $fila00[nombre]  : '<a href="?id_pagina=28&id_grupo=' . $id_compra . '" title="ver detalles">' . $fila00[nombre] . '</a>';
			$html .= '</span>
				<span id="total">&euro; ' . number_format($TOTAL_GRUPO, 2, ".", ".") . '</span>
				<span id="retencion">&euro; ' . number_format($TOTAL_RETENCION_LEGAL, 2, ".", ".") . '</span>
				<span id="tpagar">&euro; ' . number_format($TOTAL_A_PAGAR, 2, ".", ".") . '</span>
			</li>';
			
			$HTML_LISTADO .= $html;
		}
		mysql_free_result($result00);
		

		/* si no tengo registros no muestro totales, envio mensaje de no registros */
?>
		<ul id="comisiones">
			<li id="tcomisiones">
				<span id="grupo">Group</span>
				<span id="categoria">Product</span>
				<span id="total">Total purchase</span>
				<span id="retencion">10% Retention</span>
				<span id="tpagar">Total to pay</span>               
			</li>

			<?=$HTML_LISTADO ?>
		
			<li class="lcomisiones">
				<span id="ftpagar"><b>&euro; <?=number_format($TOTAL_GENERAL_A_PAGAR, 2, ".", ".") ?></b></span>
				<span id="fretencion"><b>&euro; <?=number_format($TOTAL_GENERAL_RETENCION, 2, ".", ".") ?></b></span>
				<span id="ftexto"><b>Total General</b></span>
			</li>
<?php
			if(date("d") > 7) echo '<li>Your next period to collect commission will be from day 1 to 7 from the next month.</li>';		
			if(isset($ha_cobrado)) echo '<li>You already collect your commissions. You must wait the next month for collect again.</li>';
?>  
		</ul>
<?php
		if($TOTAL_GENERAL_A_PAGAR && date('d') < 8 && !isset($ha_cobrado)) {
			echo '<div align="center">
				<input type="button" onclick="location.href=\'?id_pagina=27\';" value="Collect" />
			</div>'; 
		}
	} else {
		echo '<div id="rechazado">You do not have downlines pending of pay.</div>';
	}
?>
