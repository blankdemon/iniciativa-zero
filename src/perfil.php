<?php
	function verificarPasswordAnterior() {
		$q = "SELECT * FROM cuentas WHERE id_cuenta = " . (int) $_SESSION[id_cuenta] . " AND password LIKE '" . addslashes($_REQUEST[password_anterior]) . "'";
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			return true;
		} else {
			return false;
		}
	}

	function actualizarDatos() {
		global $error, $aceptado;
		
		if(empty($_REQUEST[nombre])) $error .= 'Debes ingresar tu nombre<br />';
		if(empty($_REQUEST[apellidos])) $error .= 'Debes ingresar tus apellidos<br />';
		if(empty($_REQUEST[diafecha_nac])) $error .= 'D&iacute;a de nacimiento no ingresado<br />';
		if(empty($_REQUEST[mesfecha_nac])) $error .= 'Mes de nacimiento no seleccionado<br />';
		if(empty($_REQUEST[yearfecha_nac])) $error .= 'Año de nacimiento no ingresado<br />';
		if(empty($_REQUEST[id_pais])) $error .= 'Pa&iacute;s no seleccionado<br />';
		if(empty($_REQUEST[correo])) $error .= 'Correo no ingresado<br />';
		if(!empty($_REQUEST[password_dos]) && $_REQUEST[password] != $_REQUEST[password_dos]) $error .= 'Las passwords no coinciden.<br />';
		
		/* verificar el cambio de correo y de passwords */
		$q = "SELECT * FROM cuentas WHERE id_cuenta = " . (int) $_SESSION[id_cuenta];
		$s = mysql_query($q);
		$r = mysql_fetch_array($s);
		if($r[email] != $_REQUEST[correo] && !verificarPasswordAnterior()) $error .= 'Tu correo principal no ha sido cambiado. Tu password anterior no coincide.<br />';
		if($r[email_dos] != $_REQUEST[correo_dos] && !verificarPasswordAnterior()) $error .= 'Tu correo adicional no ha sido cambiado. Tu password anterior no coincide.<br />';
		if(!empty($_REQUEST[password]) && !empty($_REQUEST[password_dos]) && !verificarPasswordAnterior()) $error .= 'Tu password no ha sido cambiada. Tu password anterior no coincide.<br />';
		
		if(empty($error)) {
			if(!empty($_REQUEST[password_dos])) {
				$q = "UPDATE cuentas SET password = '$_REQUEST[password]' WHERE id_cuenta = $_SESSION[id_cuenta]";
				if(mysql_query($q)) $aceptado = 'Tu password ha sido actualizada correctamente.<br/>';
			}
			
			$q = "UPDATE cuentas SET ";
			$q .= "nombres = '$_REQUEST[nombre]', ";
			$q .= "apellidos = '$_REQUEST[apellidos]', ";
			$q .= "fnacimiento = '$_REQUEST[yearfecha_nac]-$_REQUEST[mesfecha_nac]-$_REQUEST[diafecha_nac]', ";
			$q .= "email = '$_REQUEST[correo]', ";
			$q .= "email_dos = '$_REQUEST[correo_dos]', ";
			$q .= "id_pais = '$_REQUEST[id_pais]' ";
			$q .= "WHERE id_cuenta = $_SESSION[id_cuenta]";
			if(mysql_query($q)) $aceptado .= 'Tus datos han sido actualizados correctamente.';
		}
	}

	if(eregi("^save$", $_REQUEST[action])) actualizarDatos();

	$q = "SELECT * FROM cuentas WHERE id_cuenta = $_SESSION[id_cuenta]";
	$s = mysql_query($q);
	$r = mysql_fetch_array($s);
	$nombres = $r[nombres];
	$apellidos = $r[apellidos];
	list($year, $mes, $dia) = explode("-", $r[fnacimiento]);
		
	$id_pais = $r[id_pais];
	$correo = $r[email];
	$correo_dos = $r[email_dos];
?>
<h1>Information of my Account</h1>
<?php
	if(!empty($error)) echo '<div id="rechazado">' . $error . '</div>';
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
?>
<form name="form1" method="post" action="">
  <table width="100%">
    <tr>
      <td width="23%">Name: 
        <input name="action" type="hidden" id="action" value="save" /></td>
      <td width="77%"><input type="text" name="nombre" value="<?=$nombres ?>"></td>
    </tr>
    <tr>
      <td>Las Name: </td>
      <td><input type="text" name="apellidos" value="<?=$apellidos ?>"></td>
    </tr>
    <tr>
      <td>BirthDate:</td>
      <td><?=$configuracion->inputsFecha($dia, $mes, $year, $name="fecha_nac", true) ?></td>
    </tr>
    <tr>
      <td>Country</td>
      <td><?=$configuracion->retornarPaises($id_pais); ?></td>
    </tr>
    <tr>
      <td>Email: </td>
      <td><input type="text" name="correo" value="<?=$correo ?>"></td>
    </tr>
    <tr>
      <td>Aditional E-mail:</td>
      <td><input type="text" name="correo_dos" value="<?=$correo_dos ?>" /></td>
    </tr>
    <tr>
      <td>Last Password:</td>
      <td><input type="password" name="password_anterior" id="password_anterior" /> 
        If you want change your e-mail or password you need type your last password.</td>
    </tr>
    <tr>
      <td>New Password: </td>
      <td><input type="password" name="password"></td>
    </tr>
    <tr>
      <td>Re-type new password:</td>
      <td><input type="password" name="password_dos"></td>
    </tr>
  </table>
  <div align="right">
  	<input type="submit" name="actualizar" value="update information">
  </div>
</form>
<script language="javascript">
	
</script>
