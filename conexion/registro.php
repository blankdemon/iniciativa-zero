<?php
	function comprobar_email($email){ 
		$mail_correcto = 0; 
		//compruebo unas cosas primeras 
		if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
			if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
				//miro si tiene caracter . 
				if (substr_count($email,".")>= 1){ 
					//obtengo la terminacion del dominio 
					$term_dom = substr(strrchr ($email, '.'),1); 
					//compruebo que la terminación del dominio sea correcta 
					if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
						//compruebo que lo de antes del dominio sea correcto 
						$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
						$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
						if ($caracter_ult != "@" && $caracter_ult != "."){ 
							$mail_correcto = 1; 
						} 
					} 
				} 
			} 
		} 
		if ($mail_correcto) 
			return 1; 
		else 
			return 0; 
	}

	function iniciarSesion($usuario, $password, $primerInicioSession=false) {
		global $error_login;
		
		$query = "SELECT * FROM cuentas WHERE usuario='$usuario' AND password='$password';";
		$result = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($result)) {
			$r = mysql_fetch_array($result);
			$_SESSION[id_cuenta] = $r[id_cuenta];
			$_SESSION[nombre_usuario] = $r[nombres] . ' ' . $r[apellidos];
			$_SESSION[usuario] = $r[usuario];
			$_SESSION[correo] = $r[email];
			$_SESSION[nivelAcceso] = $r[permisos];
			
			$_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s");
			
			// redirige al usuario hacia la pagina principal
			echo '<script language="javascript">window.location = \'?id_pagina=1';
			if($primerInicioSession==true) echo '&primerInicioSesion=true';
			echo '\'</script>';
		} else {
			$error_login = "Invalid Data";
		}
	}
	
	function verificarSesion() {
		if($atr_02 == 1) { $timeout = 7200; } else { $timeout = 900; }
		$fechaGuardada = $_SESSION["ultimoAcceso"];
		$ahora = date("Y-n-j H:i:s");
		$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
		if($tiempo_transcurrido >= $timeout) { //si pasaron 10 minutos o mÃ¡s
			session_destroy(); // destruyo la sesiÃ³n
			$interfaz_login = 1; 
			$sesion_vencida = 1;
		} else {
			$_SESSION["ultimoAcceso"] = $ahora;
		}
	}

	function verificarSiCorreoExiste() {
		$q = "SELECT * FROM cuentas WHERE usuario LIKE '" . addslashes($_REQUEST[correo]) . "' OR email LIKE '" . addslashes($_REQUEST[correo]). "'";
		$s = mysql_query($q) or die(mysql_error());
		if(mysql_num_rows($s)) return true;
		
		return false;
	}
	
	/* envio de correo electronico de registro */
	function enviarCorreoDeRegistro() {
		global $aceptado, $rechazado;
		
		require("clases/PHPMailer/class.phpmailer.php");
		
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
		$mail->Subject = "Confirmación de Registro";
				
		/* asigno los datos de nombre y apellidos si no vienen vacios */
		$destinatario = $_REQUEST[correo];
				
		/* asigno el mail de la invitacion */
		$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : $_REQUEST[correo];
				
		$body .= "Estimado(a) $_REQUEST[nombre] $_REQUEST[apellidos],<br><br>";
		$body .= "Bienvenido a Wiki-Global.com<br><br>Inscripción  procesada en forma exitosa.<br>";
		$body .= "<br>";
		$body .= "En este correo encontrará un enlace a Términos y Condiciones del sitio para su archivo.<br>";
		$body .= "<br>";
		$body .= "<a href=\"http://www.wikiglobal.com/?id_pagina=7\">http://www.wikiglobal.com/?id_pagina=7</a><br>";
		$body .= "<br>";
		$body .= "Su nombre de usuario es $_REQUEST[correo]<br>";
		$body .= "Su clave de acceso es $_REQUEST[password]<br>";
		$body .= "<br>";
				
		$mail-> Body = $body;
		
		$mail->AddAddress($email, $destinatario);
		
		if(!$mail->Send()) {
			$rechazado = "Ha ocurrido un error registrando tu usuario.</b><br> " . $mail->ErrorInfo . "<br>";
			return false;
		} else {
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			return true;
		}
	}

	function registro() {
		global $rechazado, $registrado;
		if(empty($_REQUEST[nombre])) $rechazado .= 'Nombre no ingresado<br>';
		if(empty($_REQUEST[apellidos])) $rechazado .= 'Apellido paterno no ingresado<br>';
		if(empty($_REQUEST[correo])) $rechazado .= 'Correo no ingresado<br>';
		if($_REQUEST[correo] != $_REQUEST[recorreo]) $rechazado .= 'Los correos ingresados no coinciden<br>';
		if(empty($_REQUEST[password])) $rechazado .= 'Password no ingresado<br>';
		if(verificarSiCorreoExiste()) $rechazado .= 'No te puedes registrar, el correo ya existe.<br>';
		if(!comprobar_email($_REQUEST[correo])) $rechazado .= 'La direcci&oacute;n de correo es inválida.';
		
		if(empty($rechazado)) {
			if (isset($_REQUEST[login])) iniciarSesion(mysql_real_escape_string(trim($_POST["usuario"])), mysql_real_escape_string(trim($_POST["password"])));
			if ($_SESSION["usuario"] != "") verificarSesion();
			
			// se envia un correo electronico sobre el registro
			if(enviarCorreoDeRegistro()) {			
				// se hace la insercion en la base de datos del registro de usuario
				$q = "INSERT INTO cuentas (usuario, nombres, apellidos, password, email) VALUES ('$_REQUEST[correo]', '$_REQUEST[nombre]', '$_REQUEST[apellidos]', '$_REQUEST[password]', '$_REQUEST[correo]')";
				mysql_query($q) or die(mysql_error());
				
				// se inicia sesion una vez registrado el usuario
				iniciarSesion(mysql_real_escape_string(trim($_POST[correo])), mysql_real_escape_string(trim($_POST[password])), true);
			}
		}
	}

	if(isset($_REQUEST[registrate])) registro();
?>