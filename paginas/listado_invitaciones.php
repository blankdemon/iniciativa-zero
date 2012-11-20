<?php
	function rechazarInvitacion() {
		global $aceptado, $rechazado, $MAIL_GLOBAL_PRUEBAS;
			
		require("clases/PHPMailer/class.phpmailer.php");
		
		/* selecciona los datos de la persona invitada para el envio de notificacion de correo */
		$query = "SELECT 
			i.id_invitacion,
			CONCAT(ci.nombres , ' ' , ci.apellidos) AS nombre
        FROM invitaciones AS i
		INNER JOIN cuentas AS c ON (i.correo = c.email)
		INNER JOIN cuentas AS ci ON (i.id_persona = ci.id_cuenta)
        WHERE c.id_cuenta = $_SESSION[id_cuenta] AND i.estado = 'enviada' AND id_invitacion = " . (int)$_REQUEST[id_invitacion] . "
		ORDER BY i.id_invitacion";
        $s = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($s)) {
			list($id_invitacion, $nombre_invitador) = mysql_fetch_array($s);
			
			/* seleccionar correo para envio de notificacion */
			$qq = "SELECT 
				CONCAT(c.nombres , ' ' , c.apellidos) AS nombre, c.email 
			FROM invitaciones i
			INNER JOIN cuentas c ON (i.id_persona = c.id_cuenta)
			WHERE i.id_invitacion = " . (int)$_REQUEST[id_invitacion];
			$ss = mysql_query($qq);
			$r = mysql_fetch_array($ss);
				
			if(!empty($r[email])) {						
				/* envio de correos electronicos */
				$mail = new phpmailer();
				$mail->From     = "noreply@wiki-global.com";
				$mail->FromName = "Wiki-Global";
				$mail->Host     = "mail.wiki-global.com";
				$mail->Mailer   = "smtp";
				$mail->Username = "noreply@wiki-global.com";
				$mail->Password = "1585602106";
				$mail->WordWrap = 50;
				$mail->SMTPAuth = true;
				$mail->IsHTML(true);
				$mail->SetLanguage("es", "clases/PHPMailer/language/");
				$mail->Subject = "Invitation rejected.";
				
				/* asigno los datos de nombre y apellidos si no vienen vacios */
				if(!empty($r[nombre])) $destinatario = $r[nombre];
				
				/* asigno el mail de la invitacion */
				$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : $r[email];
				
				/* Erich, aca falta un poco de informacion */
				$body .= "Dear User,<br><br>";
				$body .= "Your invitation number " . $id_invitacion . " has been rejected.<br>";
				$body .= "<br>";
								
				$mail-> Body = $body;
				
				//$mail->AddAddress("blankdemon@gmail.com", $destinatario);	
				$mail->AddAddress($email, $destinatario);

				$qq = "UPDATE invitaciones SET estado=3 WHERE id_invitacion = " . (int)$_REQUEST[id_invitacion];
				if(mysql_query($qq)) {
					if($mail->Send()) {
						$aceptado = "The invitation has been rejected. A notification has been sent to your host telling about it.";
					}
				} else {
					$rechazado = "An unexpected error has been detected. The invitation was not rejected.";
				}
				
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			} else {
				$rechazado = "The invitation that you are trying to send do not exist, or you are not their owner.";
			}
		} else {
			$rechazado = "The invitation that you are trying to send do not exist, or you are not their owner.";
		}
	}
	
	if(eregi("^rechazar$", $_REQUEST[action])) rechazarInvitacion();
?>
<h1>Invitations List</h1>
<?php
	function cargarLinea($row) {
		global $invitaciones_contador;
		if($row) {
			$c = $invitaciones_contador;
			$condicion_eliminar = $c - 2;
			$total_invitaciones = mysql_num_rows($row);
			
			while($r = mysql_fetch_array($row)) {
				$c++;
				
				// para mostrar si es una compra o no, desplegar una fila adicional
				if($r[es_compra]==1) $es_compra = true;
				echo '<li class="linvitaciones" onmouseover="uno(this, \'DFEFFF\')" onmouseout="dos(this, \'ffffff\')">
					<span id="numero" class="numero">' . $c . '</span>
					<span id="ninvitacion">' . $r[id_invitacion] . '</span>
					<span id="fecha">' . date("d/m/Y", strtotime($r[fecha_invitacion])) . '</span>
					<span id="hora">' . $r[hora_invitacion] . ' hrs.</span>
					<span id="nombre">' . $r[nombre] . '</span>
					<span id="accion">
						<a href="javascript:aceptarInvitacion(' . $r[id_invitacion] . ');" id="aceptar" title="aceptar invitacion">accept</a>
						<a href="javascript:rechazarInvitacion(' . $r[id_invitacion] . ');" id="rechazar" title="rechazar invitacion">reject</a>
					</span>
				</li>';
    		}
			
			
			/* incremento para añadir el contador correctamente a la fila siguiente en vacio */
			$invitaciones_contador = $c;
			$c++;			
		}
	}

	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado) && !isset($_REQUEST[id_invitacion])) echo '<div id="rechazado">' . $rechazado . '</div>';
	if(!empty($_REQUEST[rechazado]) && isset($_REQUEST[id_invitacion])) {
		echo '<div id="rechazado">
			The invitation was not paid. Your sell was not generated. Please try again.
		</div>';
	}
	
	// selecciono las invitaciones
	$query = "SELECT 
		i.id_invitacion, i.fecha_invitacion, i.hora_invitacion, 
		CONCAT(ci.nombres , ' ' , ci.apellidos) AS nombre
	FROM invitaciones AS i
	INNER JOIN cuentas AS c ON (i.correo = c.email)
	INNER JOIN cuentas AS ci ON (i.id_persona = ci.id_cuenta)
	WHERE c.id_cuenta = $_SESSION[id_cuenta] AND i.estado = 'enviada'
	ORDER BY i.id_invitacion";
	
	//echo $query;
	$result = mysql_query($query) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		echo '
		<ul id="invitaciones">
			<li id="tinvitaciones">
				<span id="numero">N&ordm;</span>
				<span id="ninvitacion" title="Invitation Number">N&ordm; Inv.</span>
				<span id="fecha">Date</span>
				<span id="hora">Hour</span>
				<span id="nombre">Invited by...</span>
				<span id="accion">Action</span>
			 </li>'
			 , cargarLinea($result)
		, '</ul>
				
		<form name="accionInvitaciones" action="" method="post">
			<input type="hidden" name="id_invitacion" value="0" />
			<input type="hidden" name="action" value="" />
		</form>';
	} else {
		echo '<div id="rechazado">No invitations left to accept.</div>';
	}
?>
    
