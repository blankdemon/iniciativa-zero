<?php
	define(__MAXIMO_INVITACIONES__, 20);
	
	// envio de invitaciones	
	function enviarInvitaciones() {
		global $aceptado, $rechazado, $MAIL_GLOBAL_PRUEBAS, $config;
		
		$dest_enviados = array();
		$dest_noenviados = array();
		
		require("clases/PHPMailer/class.phpmailer.php");
		
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
		$mail->Subject = "Invitation from " . $config["company_name"];
		
		for($i =0;$i<=(int)$_REQUEST[num_invitaciones];$i++) {
			if(!empty($_REQUEST["email_invitado_" . $i])) {
			
				/* asigno los datos de nombre y apellidos si no vienen vacios */
				if(!empty($_REQUEST["nombres_invitado_" . $i])) $destinatario = $_REQUEST["nombres_invitado_" . $i];
				if(!empty($_REQUEST["apellidos_invitado_" . $i])) $destinatario .= " " . $_REQUEST["apellidos_invitado_" . $i];
				
				/* asigno el mail de la invitacion */
				$email_invitado = $_REQUEST["email_invitado_" . $i];
				
				if(trim($_SESSION[correo])!=$email_invitado) {
					$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : $email_invitado;
				
					$body = "Dear <b>";
					$body .= (!empty($destinatario)) ? $destinatario : 'Friend';
					$body .= "</b>,<br><br>";
					$body .= "<b>" . $_SESSION[nombre_usuario] . "</b> invite you to be part of <b><a href=\"http://www.wiki-global.com\">" . $config["company_name"] . "</a></b> where you will be able to access a mutlilanguage Movie Database ";
					$body .= "with all the Movies you always like. Just sign up in the web site accepting the ";
					$body .= "invitation from <b>" . $_SESSION[nombre_usuario] . "</b> for access our Database.<br>";
					$body .= "<br>";
				
					$qq = "SELECT * FROM cuentas WHERE usuario LIKE '" . addslashes($email_invitado) . "'";
					$ss = mysql_query($qq) or die(mysql_error());
					if(!mysql_num_rows($ss)) {
						$body .= "Please use the same email link to the invitation you received. ";
						$body .= "Just click on the link below:<br>";
						$body .= "<a href=\"http://www.wiki-global.com/?id_pagina=40&emregister=" . $email_invitado . "\">http://www.wiki-global.com/?id_pagina=40&emregister=" . $email_invitado . "</a>";
					} else {
						$body .= "For check de invitacion received click the link below:<br>";
						$body .= "<a href=\"http://www.wiki-global.com\">http://www.wiki-global.com</a>";
					}
				
					$body .= "<br>";
					$body .= "<br>";
					$body .= "From now on, welcome to our Community!<br>";
					$body .= "<b> <a href=\"http://www.wiki-global.com\">" . $config["company_name"] . "</a></b><br>";
					$body .= "<a href=\"http://www.wiki-global.com\">www.wiki-global.com</a><br>";
					$body .= "<br>";
				
					$mail-> Body = $body;
				
					$mail->AddAddress($email, $destinatario);
				
					$q = "SELECT * FROM invitaciones WHERE correo LIKE '" . $_REQUEST["email_invitado_" . $i] . "' AND id_persona = " . (int) $_SESSION[id_cuenta];
					$s = mysql_query($q);
					if(!mysql_num_rows($s)) {
						if(!$mail->Send()) {
						  $rechazado = "An error has been detected while trying to send your invitation(s).</b><br> " . $mail->ErrorInfo . "<br>";
						} else {
							if(isset($_REQUEST[tercera]) && !empty($_REQUEST["email_invitado_3"])) {
								$qq = "INSERT INTO invitaciones (fecha_invitacion, hora_invitacion, id_compra, id_persona, nombre, apellido, correo, estado, orden) VALUES ('" . date("Y-m-d") . "', '" . date("H:i:s") . "', '$_REQUEST[id_compra]', '$_SESSION[id_cuenta]', '" . $_REQUEST["nombres_invitado_" . $i] . "', '" . $_REQUEST["apellidos_invitado_" . $i] . "', '" . trim($_REQUEST["email_invitado_" . $i]) . "', '1', '3')";
							} else {
								$qq = "INSERT INTO invitaciones (fecha_invitacion, hora_invitacion, id_compra, id_persona, nombre, apellido, correo, estado) VALUES ('" . date("Y-m-d") . "', '" . date("H:i:s") . "', '$_REQUEST[id_compra]', '$_SESSION[id_cuenta]', '" . $_REQUEST["nombres_invitado_" . $i] . "', '" . $_REQUEST["apellidos_invitado_" . $i] . "', '" . trim($_REQUEST["email_invitado_" . $i]) . "', '1')";
							}			
						
							mysql_query($qq);
						
							array_push($dest_enviados, $_REQUEST["email_invitado_" . $i]);
						}
					} else {
						array_push($dest_noenviados, $_REQUEST["email_invitado_" . $i]);
					}
				
					$mail->ClearAddresses();
					$mail->ClearAttachments();
				} else {
					$rechazado = 'You can not send you an invitation at yourself.';				
				}
			}
		}
		
		if(sizeof($dest_enviados)==1) {
			$aceptado = "An invitation was sent to <b>" . implode(", ", $dest_enviados) . "</b>.";
		} else if(sizeof($dest_enviados)>1) {
			$aceptado = "An invitation was sent to: <i>" . implode(", ", $dest_enviados) . "</i>.";
		
		}
		
		if(sizeof($dest_noenviados)==1) {
			$rechazado = "The invitation was already sent to <b>" . implode(", ", $dest_noenviados) . "</b>.";
		} else if(sizeof($dest_noenviados)>1) {
			$rechazado = "The invitation was already sent to: <i>" . implode(", ", $dest_noenviados) . "</i>.";
		}
	}
	
	function reenviarInvitaciones() {
		global $aceptado, $rechazado, $MAIL_GLOBAL_PRUEBAS, $config;
		
		$dest_enviados = array();		
		require("clases/PHPMailer/class.phpmailer.php");
		
		$q = "SELECT * FROM invitaciones WHERE id_persona=$_SESSION[id_cuenta] AND id_invitacion = " . (int)$_REQUEST[id_invitacion];
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			$qq = "SELECT nombre, apellido, correo FROM invitaciones WHERE id_persona=$_SESSION[id_cuenta] AND id_invitacion = " . (int)$_REQUEST[id_invitacion];
			$s = mysql_query($qq);
			$r = mysql_fetch_array($s);
			$email_invitado = $r[correo];
			
			if(!empty($email_invitado)) {				
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
				$mail->Subject = "Invitation from " . $config["company_name"];
				
				/* asigno los datos de nombre y apellidos si no vienen vacios */
				if(!empty($r[nombre])) $destinatario = $r[nombre] . " ";
				if(!empty($r[apellido])) $destinatario .= $r[apellido];
				
				/* asigno el mail de la invitacion */
				$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : $email_invitado;
				
				$body = "Dear <b>";
				$body .= (!empty($destinatario)) ? $destinatario : 'Friend';
				$body .= "</b>,<br><br>";
				$body .= "<b>" . $_SESSION[nombre_usuario] . "</b> send you again this message invite you to be part of <b><a href=\"http://www.wiki-global.com\">" . $config["company_name"] . "</a></b> where you will be able to access a mutlilanguage Movie Database ";
				$body .= "with all the Movies you always like. Just sing up in the web site accepting the ";
				$body .= "invitation from <b>" . $_SESSION[nombre_usuario] . "</b>, to access our Database.<br>";
				$body .= "<br>";
				
				$qq = "SELECT * FROM cuentas WHERE usuario LIKE '" . addslashes($email_invitado) . "'";
				$ss = mysql_query($qq) or die(mysql_error());
				if(!mysql_num_rows($ss)) {
					$body .= "Please use the same email link to the invitation you received. ";
					$body .= "Just click on the link below:<br>";
					$body .= "<a href=\"http://www.wiki-global.com/?id_pagina=40&emregister=" . $email_invitado . "\">http://www.wiki-global.com/?id_pagina=40&emregister=" . $email_invitado . "</a>";
				} else {
					$body .= "For check de invitacion received click the link below:<br>";
					$body .= "<a href=\"http://www.wiki-global.com\">http://www.wiki-global.com</a>";
				}
					
				$body .= "<br>";
				$body .= "<br>";
				$body .= "From now on, Welcome to our Community!<br>";
				$body .= "<b> <a href=\"http://www.wiki-global.com\">" . $config["company_name"] . "</a></b><br>";
				$body .= "<a href=\"http://www.wiki-global.com\">www.wiki-global.com</a><br>";
				$body .= "<br>";

				$mail-> Body = $body;
				
				$mail->AddAddress($email, $destinatario);
				
				if(!$mail->Send()) {
					  $rechazado = "An error has been detected while trying to send your invitation.</b><br> " . $mail->ErrorInfo . "<br>";
				} else {
					array_push($dest_enviados, $email);		
					$aceptado = "An invitation was sent to <b>" . implode(", ", $dest_enviados) . "</b>.";
				}
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			} else {
				$rechazado = 'The invitation that you are trying to send do not exist or you are not the owner.';
			}
		} else {
			$rechazado = 'The invitation that you are trying to send do not exist or you are not the owner.';
		}
		
		if(sizeof($dest_enviados)==1)
			$aceptado = "The invitation was forwarded to <b>" . implode(", ", $dest_enviados) . "</b> successful.";
	}
	
	function eliminarInvitaciones() {
		global $aceptado, $rechazado;
		$q = "SELECT * FROM invitaciones WHERE id_persona=$_SESSION[id_cuenta] AND id_invitacion = " . (int)$_REQUEST[id_invitacion];
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			$qq = "DELETE FROM invitaciones WHERE id_persona=$_SESSION[id_cuenta] AND id_invitacion = " . (int)$_REQUEST[id_invitacion];
			if(mysql_query($qq)) {
				$rechazado = 'Invitation deleted successful.';
			}
		} else {
			$rechazado = 'The invitation that you are trying to delete do not exist or you are not the owner.';
		}
	}
	
	if(isset($_REQUEST[enviarinv])) enviarInvitaciones();
	if(eregi("^reenviar$", $_REQUEST[action])) reenviarInvitaciones();
	if(eregi("^eliminar$", $_REQUEST[action])) eliminarInvitaciones();

	/* funcion para reasignar orden de las invitaciones, pone las pagadas primero enseguida las demas */
	function ordenarInvitaciones() {
		$orden_pagados = array();
		
		$q = "SELECT 
			c.id_compra, c.orden, c.estado
		FROM compras c
		WHERE c.id_compra_padre=" . (int) $_REQUEST[id_compra] . "
		ORDER BY estado DESC";
		$s = mysql_query($q) or die(mysql_error());			
		
		while($r = mysql_fetch_array($s)) {
			array_push($orden_pagados, $r[id_compra]);
		}
		
		if(sizeof($orden_pagados)) {
			$valor_update = 0;
			for($i=0;$i<mysql_num_rows($s);$i++) {
				if($orden_pagados[$i]) {
					$valor_update++;
					if($valor_update != 3) {
						$q = "UPDATE compras SET orden='$valor_update' WHERE id_compra = " . $orden_pagados[$i];
						mysql_query($q);
						//echo $q . '<br>';
					}				
				}	 
			}
		}
	}
	
	/* desplegar el input de nombres */
	function mostrarInputsNombres($y, $nombres, $c, $ninvitaciones) {
		global $compras_pagadas;
		$max = 9;
		echo '<span class="nombres"';
		if(empty($c) || ($y==3 && empty($c))) {
			echo '><input';
			echo ' type="text"';
			echo ' class="inv_nombre"';
			echo ' name="nombres_invitado_' . $y . '"';
			
			// mientras no se paguen las primeras dos compras el casillero queda deshabilitado
			if($compras_pagadas < 2  && $ninvitaciones == 3) echo ' disabled="disabled" title="This option will be enabled when you have the first 2 purchases paid"';
			
			echo ' value="' . $nombres . '">';
		} else {
			echo ' title="' . $nombres . '">' , (!empty($nombres)) ? trim(substr($nombres, 0, $max)) : '--';
			if(strlen($nombres)>$max) echo '...';
		}
		echo '</span>';
	}
	
	/* desplegar el input de apellidos */
	function mostrarInputsApellidos($y, $apellidos, $c, $ninvitaciones) {
		global $compras_pagadas;
		$max = 10;
		echo '<span class="apellidos"';
		if(empty($c) || ($y==3 && empty($c))) {
			echo '><input';
			echo ' type="text"';
			echo ' class="inv_apellido"';
			echo ' name="apellidos_invitado_' . $y . '"';
			echo ' value="' . $apellidos . '"';
			
			// mientras no se paguen las primeras dos compras el casillero queda deshabilitado
			if($compras_pagadas < 2  && $ninvitaciones == 3) echo ' disabled="disabled" title="This option will be enabled when you have the first 2 purchases paid"';
			
			echo '>';
		} else {
			echo ' title="' . $apellidos . '">' , (!empty($apellidos)) ? trim(substr($apellidos, 0, $max)) : '--';
			if(strlen($apellidos)>$max) echo '...';			
		}
		echo '</span>';
	}
	
	/* desplegar el input de los correos */
	function mostrarInputsCorreos($y, $correo, $c, $ninvitaciones) {
		global $compras_pagadas, $no_hay_compras;
		$max = 17;
		echo '<span class="correo"';
		if(empty($c) || ($y==3 && empty($c))) {
			echo '><input';
			echo ' type="text"';
			echo ' class="inv_correo"';
			echo ' name="email_invitado_' . $y . '"';
			echo ' value="' . $correo . '"';
			
			// mientras no se paguen las primeras dos compras el casillero queda deshabilitado
			if($compras_pagadas < 2 && $ninvitaciones == 3) echo ' disabled="disabled" title="This option will be enabled when you have the first 2 purchases paid"';
			
			//echo ' onkeyup="this.value=this.value.split(\' \').join(\'\');"';
			echo '>';
		} else {
			echo ' title="' . $correo . '">' . $correo;
		}
		echo '</span>';
	}
	
	function retornarBotonAcciones($e, $idinv) {
		switch($e) {
			case 'enviada':
				$accion = '<input type="button" onclick="reenviarInvitacion(' . $idinv . ')" value="forward">';
				break;
			case 'aprobada':
				$accion = '<a class="pinvitaciones" href="paginas/formas_pago.php?id_invitacion=' . $idinv . '">Pay</a>';
				break;
		}
	
		echo $accion;
	}
	
	function retornarBotonEstado($e) {
		switch($e) {
			case 'enviada':
				$color = 'FF9900';
				$status = 'Sent';
				break;
			case 'aprobada':
				$color = '00CC33';
				$status = 'Sent';
				break;
			case 'rechazada':
				$color = 'ff0000';
				$status = 'Rejected';
				break;
		}
	
		echo '<span class="estado" style="background:#' . $color . ';display:block">' . $status . '</span>';
	}
	
	function desplegarTerceraInvitacion($orden) {
		global $compras_pagadas;
			
		$max = 10;
		$q = "SELECT * FROM invitaciones WHERE id_compra=" . (int)$_REQUEST[id_compra] . " AND orden=3";
		$s = mysql_query($q);
		
		if(mysql_num_rows($s)) {
			$r = mysql_fetch_array($s);
			echo '<li onmouseover="uno(this, \'ccf3f4\')" onmouseout="dos(this, \'ffffff\')">
				<a title="Delete this invitation." onclick="if (confirm(\'¿Are you sure do you want delete this Invitation?\')) { eliminarInvitacionFromBd(' . $r[id_invitacion] . ') }" href="javascript:;" id="eliminar"></a>
				<div>
					<span class="numero">' . $r[orden] . '</span>
					<span class="fecha">' . $r[fecha_invitacion] . '</span>
					<span title="diego" class="nombres">' , (!empty($r[nombre])) ? trim(substr($r[nombre], 0, $max)) : '--';
						if(strlen($r[nombre])>$max) echo '...';
					echo '</span>
					<span title="' . $r[apellido] . '" class="apellidos">' , 
						(!empty($r[apellido])) ? trim(substr($r[apellido], 0, $max)) : '--';
						if(strlen($r[apellido])>$max) echo '...';
					echo '</span>
					<span title="' . $r[correo] . '" class="correo">' . $r[correo] . '</span>
					<span class="pagar_reenviar">' , retornarBotonAcciones($r[estado], $r[id_invitacion]) , '</span>
					' ,  retornarBotonEstado($r[estado]) , '
				</div>
			</li>';
		} else {
			echo '<li onmouseover="uno(this, \'ccf3f4\')" onmouseout="dos(this, \'ffffff\')">
			<div>
				<span class="numero">' . $orden . '</span>
				<span class="fecha">' . date("Y-m-d") . '</span>
				<span class="nombres"><input type="hidden" name="tercera" value="1"><input type="text" value="" name="nombres_invitado_3"';
			
			// mientras no se paguen las primeras dos compras el casillero queda deshabilitado
			if($compras_pagadas < 2) echo ' disabled="disabled" title="This option will be enabled when you have the first 2 purchases paid"';
			
			echo ' class="campo_listados"></span>
				<span class="apellidos"><input type="text" value="" name="apellidos_invitado_3"';
			
			// mientras no se paguen las primeras dos compras el casillero queda deshabilitado
			if($compras_pagadas < 2) echo ' disabled="disabled" title="This option will be enabled when you have the first 2 purchases paid"';
			
			echo ' class="campo_listados"></span>
			<span class="correo"><input type="text" value="" name="email_invitado_3"';
			
			// mientras no se paguen las primeras dos compras el casillero queda deshabilitado
			if($compras_pagadas < 2) echo ' disabled="disabled" title="This option will be enabled when you have the first 2 purchases paid"';
			
			echo ' class="campo_listados"></span>
				</div>
			</li>';
		}
	}
	
	function cargarLinea($row) {
		global $invitaciones_contador, $compras_pagadas, $no_hay_compras;
		
		// flag for compras done
		$no_hay_compras = true;
		
		$c = $invitaciones_contador;

		if($row) {
			$condicion_eliminar = $c - 2;
			$total_invitaciones = mysql_num_rows($row);
			while($r = mysql_fetch_array($row)) {
				$c++;
				
				// poner compras flag en true si hay compras
				if($r[orden]>0) $no_hay_compras = false;
				
				// desplegar la tercera linea
				if($c==3 && $r[orden] != 3) {
					desplegarTerceraInvitacion($c);
					$c++;
				}
				
				// para mostrar si es una compra o no, desplegar una fila adicional
				if($r[es_compra]==1) $es_compra = true;
				 
				echo '<li onmouseover="uno(this, \'ccf3f4\')" onmouseout="dos(this, \'ffffff\')">';
				
					if(!$es_compra && !eregi("^aprobada$", $r[estado])) {
						echo '<a id="eliminar" href="javascript:;" onclick="';
						echo 'if (confirm(\'¿Are you sure do you want delete this Invitation?\')) { eliminarInvitacionFromBd(' . $r[id_invitacion] . ') }';
						echo '" title="Delete this invitation."></a>';
					}				
				
					echo '<div>
						<span class="numero">' . $c . '</span>
						<span class="fecha">' . date("Y-m-d", strtotime($r[fecha_invitacion])) . '</span>';
						
						/* cargo los input de los nombres, apellidos y correos */
						mostrarInputsNombres($y, $r[nombre], $r[correo], $c);
						mostrarInputsApellidos($y, $r[apellido], $r[correo], $c);
						mostrarInputsCorreos($y, $r[correo], $r[correo], $c);
						
						echo '<span class="pagar_reenviar">' , retornarBotonAcciones($r[estado], $r[id_invitacion]) , '</span>' 
						, retornarBotonEstado($r[estado]) 
					, '</div>       
				</li>';
    			}
			
			/* incremento para añadir el contador correctamente a la fila siguiente en vacio */
			$invitaciones_contador = $c;		
		}

		/* despliega los inputs de ingreso invitaciones en vacio */
		if(!$es_compra) {
			$limite_mostrar_inputs = (!$row) ? 2 : 1;
			
			for($i=0;$i<$limite_mostrar_inputs;$i++) {
				$c++;
				echo '<li id="li_inv_' . $invitaciones_contador . '">';
			
					if($i==($limite_mostrar_inputs-1)) echo '<a id="agregar" href="javascript:;" onclick="agregarInvitacion(this, \'' . date("d/m/Y") . '\', ' . $i . ');" title="Add more invitations."></a>';
					echo '<div>
						<span class="numero">' . $c . '</span>
						<span class="fecha">' . date("Y-m-d") . '</span>';
					
						/* cargo los input de los nombres, apellidos y correos */
						mostrarInputsNombres($i, '', '', $c);
						mostrarInputsApellidos($i, '', '', $c);
						mostrarInputsCorreos($i, '', '', $c);
					
					echo '</div>       
				</li>';
			}
			
			$invitaciones_contador = $c;
		}
	}
	
	/* ordenar las invitaciones */
	ordenarInvitaciones();
?>





<form name="linvitaciones" action="" method="post">
	<h1>Working Downline</h1>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado)) echo '<div id="rechazado">' . $rechazado . '</div>';
?>
    <span style="margin:10px 0 0 20px;display:block"><img src="imagenes/yo.png" align="absmiddle" /><b> I</b></span>
    <ul id="tree">
    	<ul id="invitados">
		<li id="primer" style="margin-left:20px;">Your Level Zero</li>
		<li class="invitaciones" id="titulos">
			<div>
				<span class="numero">N&ordm;</span>
				<span class="fecha">Date</span>
				<span class="nombres" title="First Name">First Name</span>
				<span class="apellidos" title="Last Name">Last Name</span>
				<span class="correo" title="E-Mail">E-Mail</span>
				<span class="pagar_reenviar" style="text-align:center">Action</span>
			</div> 
		</li>
<?php	
			$invitaciones_contador = 0;
			
			// selecciono las compras
			$query = "SELECT cu.nombres AS nombre, cu.apellidos AS apellido, cu.email AS correo,
				c.orden, c.fecha_pago AS fecha_invitacion, '1' AS es_compra
			FROM compras AS c
			INNER JOIN cuentas AS cu ON (c.id_persona = cu.id_cuenta)
			WHERE id_compra_padre=" . (int) $_REQUEST[id_compra] . "
			ORDER BY c.orden";
			//echo $query;
			$result = mysql_query($query) or die(mysql_error());
			$compras_pagadas = mysql_num_rows($result);
			
			if ($compras_pagadas) 				
				cargarLinea($result);
			
			// selecciono las invitaciones
			$query = "SELECT *
			FROM invitaciones
			WHERE id_persona = $_SESSION[id_cuenta] AND orden != 3 AND id_compra=" . (int) $_REQUEST[id_compra];
			//echo $query;
			$result = mysql_query($query) or die(mysql_error());
		
			if (mysql_num_rows($result) > 0) {
				cargarLinea($result);
			} else {
				cargarLinea(false);
			}
?>
		</ul>
	</ul>

    <div id="foot_invitaciones">
    	<input name="enviarinv" type="submit" value="Send invitations">
        <input type="button" onclick="window.location='?id_pagina=21&id_compra=<?=(int) $_REQUEST[id_compra] ?>';" value="Tree View">
        <input type="hidden" name="num_invitaciones" value="4" />
        <input type="hidden" name="cpagadas" id="cpagadas" value="<?=$compras_pagadas ?>" />
    </div>
</form>

<form name="accionInvitaciones" action="" method="post">
	<input type="hidden" name="id_invitacion" value="0" />
    <input type="hidden" name="action" value="" />
</form>
