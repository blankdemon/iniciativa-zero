<?php
	if(isset($_SESSION["estoy_invitado"])) {
		include("paginas/invitacion.php");
	} else {
?>
		<img src="imagenes/menu_contenido/titulos_inscripcion_usuarios_nuevos.jpg">
		<br><br>
<?php
		include("formas/inscripcion.php");
		if($_SERVER["REMOTE_ADDR"] == "200.104.73.762") {
?>
		<p class="error">No estamos recibiendo inscripciones nuevas por el momento.<br>S�lo podr� inscribirse si es beneficiario de una invitaci�n.<br>
		Para hacerlo, haga click en el enlace adjunto en el mail que recibir� al momento de ser invitado.<br>
		Despues de loguearse no olvide aceptar la invitaci�n en la secci�n "Invitaciones".<br>
		Muchas Gracias !.</p>
<?php
		}
	}
?>