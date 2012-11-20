<?php
	/* almacena las compras que deben ser insertadas en la tabla COBRO_COMPRA */
	$compras_a_insertar = array();
	
	function enviarNotificacionCobro() {
		global $aceptado, $rechazado, $MAIL_GLOBAL_PRUEBAS;
			
		require("clases/PHPMailer/class.phpmailer.php");
		
		/* seleccionar correo para envio de notificacion */
		$qq = "SELECT CONCAT(c.nombres , ' ' , c.apellidos) AS nombre, c.email FROM cuentas c WHERE c.id_cuenta = " . (int)$_SESSION[id_cuenta];
		$ss = mysql_query($qq);
		$r = mysql_fetch_array($ss);
				
		if(!empty($r[email])) {						
			/* envio de correos electronicos */				
			$mail = new phpmailer();
			$mail->From     = "invitaciones@wiki-global.com";
			$mail->FromName = $_SESSION[nombre_usuario];
			$mail->Host     = "mail.wiki-global.com";
			$mail->Mailer   = "smtp";
			$mail->Username = "invitaciones@wiki-global.com";
			$mail->Password = "54712548";
			$mail->WordWrap = 50;
			$mail->SMTPAuth = true;
			$mail->IsHTML(true);
			$mail->SetLanguage("es", "clases/PHPMailer/language/");
			$mail->Subject = "Honoraries Pay from Wiki-Global.com";
			$mail->Subject = "Honoraries Pay!";
			
			/* asigno los datos de nombre y apellidos si no vienen vacios */
			if(!empty($r[nombre])) $destinatario = $r[nombre];
			
			/* asigno el mail de la invitacion */
			$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : $r[email];
			
			$body .= "Tenemos el agrado de informarle que el dinero correspondiente a sus comisiones se encuentra en proceso de pago.<br>";
			$body .= "<br>";
			$body .= "Debe considerar que la Institución financiera tarda 2 días hábiles para ejecutar la transferencias masivas, plazos decretados por ellos.<br>";
			$body .= "<br>";
			$body .= "Se ha emitido una boleta de honorarios de terceros electrónica con la retención de 10% correspondiente al impuesto a la renta. ";
			$body .= "Le recordamos que <b><u>ese dinero usted no lo pierde</u></b>, por el contrario, debe considerarlo en la devolución de la operación renta del próximo año.<br>";
			$body .= "<br>";
			$body .= "<br>";
			
			$mail-> Body = $body;
			$mail->AddAddress($email, $destinatario);
			
			if(!$mail->Send()) {
				$rechazado = 'An unexpected error was found. Email with pay information was not sent.';
			}
			
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		} else {
			$rechazado = 'This operation is forbidden for you.';
		}
	}
	
	/*************************
		calculo primer nivel
		*********************/
	function calcularNivelZero($id_compra = 0) {
		global $TOTAL_PARCIAL, $nivel, $monto, $compras_a_insertar, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
			
		/* obtengo cantidad de ventas de cada uno de mis hijos */
		$q01 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras AS c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra
			ORDER BY c.id_compra";
		$s01 = mysql_query($q01);
		$total_compras_nivel_1 = mysql_num_rows($s01);
		if($total_compras_nivel_1 > 1) {
			$reventa = false;
			$vcc_cobrada = false;
			$compras_cobradas = 0;
			$porcentaje_comision = 0.1;
			
			calcularPrimerNivel($q01);
					
			/* recorro cada uno demis hijos - verifico si hay reventa */
			while($r = mysql_fetch_array($s01)) {
				if($r[orden] > 1) {
					if($r[orden] == 3) $reventa = true;
					if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
					if($r[id_compra_hijo]<1 && $r[orden] > 3) {
						array_push($compras_a_insertar, array($id_compra, $r[id_compra], $monto, $porcentaje_comision, ($monto * $porcentaje_comision)));
					}
				}
			}
			mysql_free_result($s01);
			
			/* calculo residuales */
			if($total_compras_nivel_1 > 2) {
				if($reventa) {									
					$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_1 - $compras_cobradas - 3) * $porcentaje_comision); // calculo % ganancia vtas 4-20
				} else {
					$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_1 - $compras_cobradas - 2) * $porcentaje_comision); // calculo % ganancia vtas 4-20
				}
			}
		}
	}	
	
	/*************************
		calculo segundo nivel
		*********************/
	function calcularPrimerNivel($q01) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $compras_a_insertar, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		
		$s01 = mysql_query($q01);		
		if(mysql_num_rows($s01) > 1) {
				
			/* recorro cada uno de mis hijos */	
			while ($r01 = mysql_fetch_array($s01)) {
				$total_compras_nivel_2 = 0;
				$id_compra_nivel_2 = $r01[id_compra];
				
				/* obtengo cantidad de ventas de cada uno de mis hijos */
				$q02 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
				FROM compras AS c
				LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
				WHERE c.id_compra_padre = $id_compra_nivel_2
				ORDER BY c.id_compra";
				$s02 = mysql_query($q02) or die(mysql_error());
				$total_compras_nivel_2 = mysql_num_rows($s02);
				if($total_compras_nivel_2) {
					$reventa = false;
					$vcc_cobrada = false;
					$compras_cobradas = 0;
					$porcentaje_comision = 0.1;
					
					calcularSegundoNivel($q02);
					
					/* recorro cada uno demis hijos - verifico si hay reventa */
					while($r = mysql_fetch_array($s02)) {
						$comision = 0;
						if($r[orden] > 1) {
							if($r[orden] > 3) $comision = $porcentaje_comision;
							if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
							if($r[id_compra_hijo]<1 && $r[orden] > 3 && $comision>0) {
								array_push($compras_a_insertar, array($id_compra, $r[id_compra], $monto, $comision, ($monto * $comision)));
							}
						}
					}
								
					/* calculo residuales */
					if($total_compras_nivel_2 > 2) 
						$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_2 - $compras_cobradas - 3) * $porcentaje_comision); // calculo 15% ganancia vtas 4-10
					
				}
			}
		}
	}
	
	/***********************
		calculo tercer nivel
		*******************/
	function calcularSegundoNivel($sql) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $compras_a_insertar, $uf, $TOTAL_COSTO_BOLETAS_PAGO;		
		$consulta = mysql_query($sql) or die(mysql_error());
		while($r02 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_3 = 0;
			$id_compra_nivel_3 = $r02[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras AS c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_3
			ORDER BY c.id_compra";
			$s03 = mysql_query($q03);
			$total_compras_nivel_3 = mysql_num_rows($s03);
			if($total_compras_nivel_3) {
				$reventa = false;
				$compras_cobradas = 0;
				$porcentaje_comision = 0.1;
				
				calcularTercerNivel($q03);
				
				/* verifico si hay reventa */
				while($r = mysql_fetch_array($s03)) {
					$comision = 0;
					if($r[orden] > 1) {
						if($r[orden] > 3) $comision = $porcentaje_comision;
						if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
						if($r[id_compra_hijo] < 1 && $r[orden] > 3 && $comision > 0) {
							array_push($compras_a_insertar, array($id_compra, $r[id_compra], $monto, $comision, ($monto * $comision)));
						}
					}
				}
				
				/* calculo residuales */
				if($total_compras_nivel_3 > 2)
					$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_3 - $compras_cobradas - 3) * $porcentaje_comision); // calculo % ganancia vtas 4-20
							
			}			
		}	
	}
	
	/***********************
		calculo cuarto nivel
		*******************/
	function calcularTercerNivel($sql) {
		global $TOTAL_PARCIAL, $id_compra, $nivel, $monto, $compras_a_insertar, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		$consulta = mysql_query($sql) or die(mysql_error());		
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras AS c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_4
			ORDER BY c.id_compra";
			
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				$reventa = false;
				$vcc_cobrada = false;
				$compras_cobradas = 0;
				
				calcularCuartoNivel($q03);
				
				while($r = mysql_fetch_array($s03)) {
					$comision = 0;
					if($r[orden] > 1) {
						if($r[orden] > 3) $comision = 0.1;
						if($r[orden] == 2 && $r[id_compra_hijo]>0) $vcc_cobrada = true;
						if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
						if($r[id_compra_hijo] < 1 && $r[orden] > 3 && $comision>0) {
							array_push($compras_a_insertar, array($id_compra, $r[id_compra], $monto, $comision, ($monto * $comision)));
						}
					}
				}
				
				/* calculo residuales */
				if($total_compras_nivel_4 > 2) 
					$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_4 - $compras_cobradas - 3) * 0.1);
				
			}			
		}			
	}
	
	/***********************
		calculo cuarto nivel
		*******************/
	function calcularCuartoNivel($sql) {
		global $TOTAL_PARCIAL, $id_compra, $monto, $compras_a_insertar, $uf, $TOTAL_COSTO_BOLETAS_PAGO;
		$consulta = mysql_query($sql) or die(mysql_error());		
		while($r03 = mysql_fetch_array($consulta)) {
			$total_compras_nivel_4 = 0;
			$id_compra_nivel_4 = $r03[id_compra];
			
			/* obtengo cantidad de ventas de cada uno de mis nietos */
			$q03 = "SELECT c.id_persona, c.id_compra, c.orden, cc.id_compra_hijo 
			FROM compras AS c
			LEFT JOIN cobro_compra AS cc ON (c.id_compra = cc.id_compra_hijo)
			WHERE c.id_compra_padre = $id_compra_nivel_4
			ORDER BY c.id_compra";
			
			$s03 = mysql_query($q03) or die(mysql_error());
			$total_compras_nivel_4 = mysql_num_rows($s03);
			if($total_compras_nivel_4) {
				$reventa = false;
				$vcc_cobrada = false;
				$compras_cobradas = 0;
				
				while($r = mysql_fetch_array($s03)) {
					$comision = 0;
					if($r[orden] > 1) {
						if($r[orden] > 3) $comision = 0.2;							
						if($r[orden] == 2 && $r[id_compra_hijo]>0) $vcc_cobrada = true;
						if($r[orden] > 3 && $r[id_compra_hijo]>0) $compras_cobradas++;
						if($r[id_compra_hijo]<1 && $r[orden] > 3 && $comision>0) {
							array_push($compras_a_insertar, array($id_compra, $r[id_compra], $monto, $comision, ($monto * $comision)));
						}
					}
				}
				
				/* calculo residuales */
				if($total_compras_nivel_4 > 2)
					$TOTAL_PARCIAL += ($monto * ($total_compras_nivel_4 - $compras_cobradas - 3) * 0.2);
							
			}			
		}			
	}
	
	/* 
		CADA COMPRA que hace esta persona se contempla como GRUPO
		$id_persona viene definido con el identificador de quien inicia sesion
	*/
	$query00 = "SELECT 
		c.id_compra, p.precio, p.nombre
	FROM compras c
	INNER JOIN productos p ON (c.id_producto = p.id_producto)
	WHERE (c.fecha_pago != '0000-00-00' AND c.fecha_pago BETWEEN '" . date("Y-m-d", (mktime() - (60*60*24*365))) . "' AND '" . date("Y-m-d", (mktime() - (date("d")*60*60*24))) . "') AND 
		c.id_persona = $_SESSION[id_cuenta]
	ORDER BY c.id_compra";
		
	$result00 = mysql_query($query00) or die(mysql_error());
	$total_mis_ventas = mysql_num_rows($result00);	
	if ($total_mis_ventas && date("d") < 8) {
		$i = -1;
		/* contendra el total general de honorarios por cobrar */
		$TOTAL_PARCIAL = 0;
	
		/* almacena el descuento total de las boletas de pago */
		$TOTAL_GENERAL_RETENCION = 0;		
		$TOTAL_GENERAL_A_PAGAR = 0;
		
		/* arreglos que almacenan queries y totales por grupo, la finalidad es hacer inserciones y calculos mas adelante */
		$totales_por_grupo = array();
		$queries_por_grupo = array();
		
		/* recorro filas de la consulta */
		while($fila00 = mysql_fetch_array($result00)) {
			$i++;
			$TOTAL_GRUPO = 0;
			$id_compra = $fila00[id_compra];
			$monto = $fila00[precio];
			$id_grupo = $fila00[id_grupo];
			
			/* esta funcion contiene calcular los siguientes niveles */
			calcularNivelZero($id_compra);
			
			/* pusheo valores de cada grupo */
			array_push($totales_por_grupo, $TOTAL_PARCIAL);
			
			/* calculo los totales para imprimir */
			$TOTAL_GRUPO = ($TOTAL_PARCIAL - $totales_por_grupo[($i-1)]);
			$TOTAL_VENTA_NETA = $TOTAL_GRUPO;
			$TOTAL_RETENCION_LEGAL = ($TOTAL_VENTA_NETA * 0.1);
			$TOTAL_A_PAGAR = ($TOTAL_VENTA_NETA - $TOTAL_RETENCION_LEGAL);			
			
			$TOTAL_GENERAL_RETENCION += $TOTAL_RETENCION_LEGAL;
			$TOTAL_GENERAL_A_PAGAR += $TOTAL_A_PAGAR;
			
			
			/* creo arreglos para insertar en abla de grupos */
			if($TOTAL_GRUPO) {
				$SQL = "INSERT INTO cobro_grupo (id_cobro, id_compra, id_grupo, nivel, monto_grupo, monto_iva, monto_boletas, monto_neto, monto_retencion, monto_a_pagar) VALUES ('__ID_COBRO_REPLACE__', '$id_compra', '$id_grupo', '$nivel', '".number_format($TOTAL_GRUPO, 0, "", "")."', '".number_format($TOTAL_IVA, 0, "", "")."', '".number_format($TOTAL_COSTO_BOLETAS_PAGO, 0, "", "")."', '".number_format($TOTAL_VENTA_NETA, 0, "", "")."', '".number_format($TOTAL_RETENCION_LEGAL, 0, "", "")."', '".number_format($TOTAL_A_PAGAR, 0, "", "")."');";
				array_push($queries_por_grupo, $SQL);
			}
		}

		if($TOTAL_GENERAL_A_PAGAR && date("d") < 8) {
			/* registro el ingreso del cobro */
			mysql_query("INSERT INTO cobro (id_persona, fecha_cobro, monto_a_pagar) VALUES ('$id_persona', '".mktime()."', '".number_format($TOTAL_GENERAL_A_PAGAR, 0, "", "")."')");
			$id_cobro = mysql_insert_id();
			for($i=0;$i<sizeof($queries_por_grupo);$i++) {
				/* registro los ingresos de cada uno de los grupos */
				$q = str_replace("__ID_COBRO_REPLACE__", $id_cobro, $queries_por_grupo[$i]);
				mysql_query($q);
			}			
			
			/* procesar las compras a insertar */
			if(sizeof($compras_a_insertar)) {
				for($i=0;$i<sizeof($compras_a_insertar);$i++) {
					$q = "INSERT INTO cobro_compra (id_cobro, id_compra_padre, id_compra_hijo, monto, porcentaje_comision, monto_comision) VALUES ('$id_cobro', '".$compras_a_insertar[$i][0]."', '".$compras_a_insertar[$i][1]."', '".$compras_a_insertar[$i][2]."', '".$compras_a_insertar[$i][3]."', '".$compras_a_insertar[$i][4]."')";
					mysql_query($q) or die(mysql_error());
				}
			}
			
			enviarNotificacionCobro();
?>
			<h1>Comisiones Cobradas</h1>
            <div id="result">
              	Se ha generado un pago por  <b>$ <?=number_format($TOTAL_GENERAL_A_PAGAR, 0, ".", ".") ?></b>.<br />
                La retenci&oacute;n  de 10% equivalente a <b>$ <?=number_format($TOTAL_GENERAL_RETENCION, 0, ".", ".") ?></b> te ser&aacute; depositada    
                en Cuenta Corriente de la Tesorer&iacute;a General de la Rep&uacute;blica<span class="aviso" style="width:638px;">.<br />
                Pr&oacute;ximamente te enviaremos un correo electr&oacute;nico de confirmaci&oacute;n de su dep&oacute;sito o Vale Vista</span></p>
			</div>
            <br />
            <table border="0" width="100%" class="listados">
              <tr>
                <td width="32%">Tipo Comisiones</td>
                <td width="27%">Cobrado</td>
                <td width="18%">Fecha Cobro</td>
                <td width="23%">Balance Final despu&eacute;s del Cobro</td>
              </tr>
              <tr>
                <td>Totales</td>
                <td>$ <?=number_format($TOTAL_GENERAL_A_PAGAR, 0, ".", ".") ?></td>
                <td><?=date("d-m-Y") ?></td>
                <td>$ 0</td>
              </tr>
<?php
		} else {
			echo '<table border="0" cellspacing="0" cellpadding="0" width="100%" class="listados">
			<tr><td colspan="13">no hay registros para pagos</td></tr>';
		}
		
		/* muestro opcion para volver */
		echo '<tr><td colspan="14" align="center" style="padding-top:30px;"><input type="button" onclick="window.history.back();" value="Volver" /></td></tr>';
?>  
</table>
<?php
	}
?>
