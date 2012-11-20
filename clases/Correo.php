<?php
	class envioMails() {
		var $mail = "";
		var $asunto = "";
		var $contenido = "";
				
		function enviarMail() {
			require("mails/PHPMailer/class.phpmailer.php");
			$mail = new phpmailer();
			$mail->From     = "webmaster@wiki-global.com";	
			$mail->FromName = "Wiki-global.com";	
			$mail->Host     = "mail.wiki-global.com";	
			$mail->Mailer   = "smtp";	
			$mail->Username = "webmaster@wiki-global.com";	
			$mail->Password = "80695500";
			$mail->WordWrap = 50;
			$mail->SMTPAuth = true;
			$mail->IsHTML(true);
			$mail->SetLanguage("es", "mails/PHPMailer/language/");
			$mail->Subject = $this->asunto;
			
			$mail->Body = $this->contenido;
				
				$m_error = "Ha ocurrido un error enviando el mail a <b>$_REQUEST[email]</b>";
				$m_aviso = "Los datos de tu nombre de usuario y clave de acceso han sido enviados a tu email registrado.";
				
				$mail->AddAddress($mail, $email);
	
				if(!$mail->Send()) {
				  $mensaje = $mensaje . "$m_error<br><b>Error de mail:</b> " . $mail->ErrorInfo . "<br>";
				  $clase_m = "error";
				} else {
					$mensaje = $mensaje . "$m_aviso";
					$clase_m = "aviso";
				}
				
				
				${"mensaje" . $clave} = $mensaje;
				$mail->ClearAddresses();
				$mail->ClearAttachments();
		}
	};
?>
