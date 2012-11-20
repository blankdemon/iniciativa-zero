<form action="" method= "post" name="forma_contacto" id="forma_contacto">
<?
	if($estoy_adentro == 1) {
		$query = "SELECT CONCAT(nombres, ' ', apellidos) AS nombre, email FROM inscripciones,personas,emails WHERE inscripciones.id_persona=personas.id_persona AND inscripciones.id_email1=emails.id_email AND personas.id_persona='" . $_SESSION["id_persona"] . "';";
		$result = mysql_query($query) or die(mysql_error());
		$linea = mysql_fetch_array($result);
		$nombre = $linea["nombre"];
		$email = $linea["email"];
		$mensaje = mysql_real_escape_string($_POST['mensaje']);
	} else {
		$nombre = mysql_real_escape_string($_POST['nombre']);
		$email = mysql_real_escape_string($_POST['email']);
		$mensaje = mysql_real_escape_string($_POST['mensaje']);
	}
?>
<p class="subtitulos">Formulario de Contacto</p>

<p align="justify">Toda la informaci&oacute;n solicitada en &eacute;ste	 formulario, es totalmente confidencial, WikiGlobal.com bajo ninguna circunstancia,	 distribuir&aacute;, vender&aacute;, ni enviar&aacute; su informaci&oacute;n de contacto a ning&uacute;n tercero. La	 &uacute;nica funcionalidad de &eacute;ste, es conocer mejor sus opiniones y consultas, y as&iacute; poder ofrecerle un mejor servicio.</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr style="height:28px">
		<td class="etiqueta">Nombre</td>
		<td class="campo">
			<?	if($estoy_adentro == 1) { ?><b>
			<? echo $nombre ?>
			<? } else { ?>
			<input name="nombre" type="text" class="campo" id="nombre" style="width:300px" size="40" value="<? echo $nombre ?>">
			<? } ?>
		</td>
	</tr>
	<tr style="height:28px">
		<td class="etiqueta">e-mail</td>
		<td class="campo">
			<?	if($estoy_adentro == 1) { ?><b>
			<? echo $email ?>
			<? } else { ?>
			<input name="email" type="text" class="campo" id="email" style="width:300px" size="40"  value="<? echo $email ?>">
			<? } ?>
		</td>
	</tr>
	<tr>
		<td class="etiqueta" style="vertical-align:top; padding-top:10px">Mensaje</td>
		<td class="campo">
			<textarea name="mensaje" cols="39" style="width:302px" rows="6" class="texto01" id="mensaje"></textarea>
		</td>
	</tr>
<? if($area != "administracion") { ?>
	<tr height="24">
		<td class="etiqueta"><input type="checkbox" name="copia" class="radio" onclick="if(document.forma_contacto.enviar_copia.value==0) { document.forma_contacto.enviar_copia.value=1; } else {document.forma_contacto.enviar_copia.value=0; } "></td>
		<td class="campo">Enviar una copia de este mensaje a mi casilla de correo electrónico.</td>
	</tr>
<? } ?>
</table>
<p style="text-align:center"><input name="enviar" type="button" value="Enviar Consulta" class="rojo" onmouseover="this.className='verde'" onmouseout="this.className='rojo'" onclick="Contacto<?	if($estoy_adentro == 1) { ?>Interno<? } ?>()"></p>
<? if($_POST["paso"] == 1) { 
	$ip = $_SERVER['REMOTE_ADDR'];	
	$mensaje = nl2br($mensaje);
	$query = "INSERT INTO consultas(nombre,email,consulta,fecha,hora,ip) VALUES('$nombre', '$email', '$mensaje', '$fecha_actual', '$hora_actual', '$ip')";
	$result = mysql_query($query) or die(mysql_error());
	include("escrituras/registro.php");
	if($_POST["enviar_copia"] == 1) {
		$consulta = $mensaje;
		$mensaje = "";
		include("mails/copia_parametros.php");
		include("mails/copia.php");
	}
?>
<p class="aviso"><b>Confirmacion de envio</b><br>
  Los datos enviados desde nuestro formulario han sido recepcionados correctamente</font><br>
	<? echo $mensaje ?><br><br>
</p>
<? } ?>
<input type="hidden" name="paso" value="<? echo $_POST["paso"] ?>">
<input type="hidden" name="enviar_copia" value="0">
</form>
<? if($panel == "") { ?>
<hr style="height:1px" color="#33cccc">
<p class="subtitulos">Informaci&oacute;n de Contacto</p>
<p align="justify">Si desea enviar un adjunto con su mensaje, utilice la casilla de correo electr&oacute;nico a continuaci&oacute;n</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr height="30">
		<td class="etiqueta">Empresa</td>
		<td class="campo" nowrap="nowrap">WikiGlobal.com <em>Multinivel</em> </td>
	</tr>
	<tr height="30">
		<td class="etiqueta">e-mail</td>
	  <td class="campo"><a href="mailto:info@wikiglobal.com">info@wikiglobal.com<br>		</td>
	</tr>
	<tr height="30">
		<td class="etiqueta">Direcci&oacute;n</td>
		<td class="campo" nowrap="nowrap">Suite 102, Saffrey Square, Nassau, Bahamas</td>
	</tr>
</table>
<? } ?>