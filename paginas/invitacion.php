<script src="_js/validar.js" type="text/javascript"></script>
<? 

	if(isset($_SESSION["estoy_invitado"])) {
		$id_invitacion04 = $_SESSION["estoy_invitado"];	
	}


	if(isset($_GET["id_invitacion"])) { 
		$id_invitacion04 = $_GET["id_invitacion"];
		$_SESSION["estoy_invitado"] = $id_invitacion04;
	}

	if(isset($_GET["id"])) { 
		$clave = $_GET["id"];
	}

	if(isset($_POST["id_invitacion"])) { 
		$id_invitacion04 = $_POST["id_invitacion"];
		$_SESSION["estoy_invitado"] = $id_invitacion04;
	}

	if (isset($id_invitacion04)) {
		$query = "SELECT personas.*,emails.*,CONCAT(invitadores.nombres, ' ', invitadores.apellidos) AS invitador FROM invitaciones,personas,emails,personas AS invitadores,compras WHERE invitaciones.id_compra=compras.id_compra AND compras.id_persona=invitadores.id_persona AND invitaciones.id_persona=personas.id_persona AND invitaciones.id_email=emails.id_email AND invitaciones.id_invitacion='$id_invitacion04' AND estado = 0";
		$result = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {
			$linea = mysql_fetch_array($result);
			$id_persona = $linea["id_persona"];
			$nombres = $linea["nombres"];
			$apellidos = $linea["apellidos"];
			$invitador = $linea["invitador"];
			$email1 = $linea["email"];
			$query = "SELECT * FROM cuentas WHERE id_persona='$id_persona'";
			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) {
?>				<span class="noticia"><b>NOTICIAS</b></span><br>
				<p class="aviso">
				<b>Sr(a) <? echo $nombres . " " . $apellidos ?></b><br>
				Ha recibido una invitación de <b><? echo $invitador ?></b> para participar en su grupo de distribuci&oacute;n.<br>
				Ingrese al sistema con su nombre de usuario y clave de acceso usando el formulario de ingreso en la parte superior derecha de esta p&aacute;gina o haga click en el botón a continuación.<br><br>
				<input type="button" class="rojo" name="descargar_manual" onclick="location.href='<? enlace("invitaciones_vigentes", 2) ?>';" onmouseover="this.className='verde'" onmouseout="this.className='rojo'" value="Ver Invitaciones">
				</p>
<?
			} else {
				echo "<img src=\"imagenes/menu_contenido/titulos_inscripcion_usuarios_nuevos.jpg\">";
				if(!isset($_POST["paso"])) {
				echo "<p class=\"subtitulos\">Bienvenido a WikiGlobal.com</p>Has recibido una invitación de <b>$invitador</b> para participar en el sistema de venta y distribucion de música y libros WikiGlobal.com.<br>
						<br>
						Para aceptarla primero debes inscribirte usando el formulario a continuacion. Luego de loguearte en el sistema podras aceptar dicha invitacion desde tu panel de control para asi poder participar en el grupo de distribucion de $invitador.<br>";
				}
				include("formas/inscripcion.php");
			}			
		}	else {
			include("paneles/inicio.php");			
		}
	}	
?>
