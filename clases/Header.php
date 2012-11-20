<?php
	class Header {
		var $num_invitaciones = 0;
		var $tiene_referidos = false;
	
		function desplegarInvitaciones() {
			// ve si tiene grupos de referidos
			$q = "SELECT 
				c.*,
				CONCAT(cu.nombres , ' ' , cu.apellidos) AS nombre
			FROM compras c
			LEFT JOIN compras cp ON (c.id_compra_padre = cp.id_compra)
			LEFT JOIN cuentas cu ON (cp.id_persona = cu.id_cuenta)
			WHERE c.id_persona = " . (int) $_SESSION[id_cuenta]
			. " ORDER BY c.id_compra DESC";
			$s = mysql_query($q);
			if(mysql_num_rows($s)) $this->tiene_referidos = true;
		
			// selecciono las invitaciones
			$query = "SELECT 
				i.id_invitacion, i.fecha_invitacion, i.hora_invitacion, 
				CONCAT(ci.nombres) AS nombre
			FROM invitaciones AS i
			INNER JOIN cuentas AS c ON (i.correo = c.email)
			INNER JOIN cuentas AS ci ON (i.id_persona = ci.id_cuenta)
			WHERE c.id_cuenta = " . (int) $_SESSION[id_cuenta] . " AND i.estado = 'enviada'
			ORDER BY i.id_invitacion";
			
			//echo $query;
			$result = mysql_query($query) or die(mysql_error());
			$this->num_invitaciones = mysql_num_rows($result);
		}
		
		function generarHeader() {
			global $fecha_actual, $error_login, $menu;
			
			$this->desplegarInvitaciones();
			
			echo '<div id="header">
				<div id="left"><a href="?id_pagina=1"><img alt="WikiGlobal.com" src="imagenes/wikiglobal.jpg" border="0" align="top"></a></div>
				<div id="right">';
				
				if($_SESSION[id_cuenta] > 0) {
					$query = "SELECT 
						CONCAT(nombres) AS nombre
					FROM cuentas
					WHERE id_cuenta = " . (int) $_SESSION[id_cuenta];
					$result = mysql_query($query) or die(mysql_error());
					$r = mysql_fetch_array($result);
			
					echo '<ul>';
						if(!empty($r[nombre])) echo '<li>Welcome <b>' . $r[nombre] . '</b></li>';
						
						echo '<li>Today is ' . date("Y/m/d") . '</li>';
						
						if($this->num_invitaciones > 0 || $this->tiene_referidos == true) echo '<li><a href="?id_pagina=12" id="ninvitaciones" title="invitaciones recibidas">Invitations (' . $this->num_invitaciones . ')</a></li>';
						
						echo '<li><a href="?id_pagina=1&action=salir" id="csesion" title="Salir">Sign out</a></li>
					</ul>';
				} else {
					echo '<form id="login" name="login" method="post" action="">
						<div id="usuario">
							E-mail:
							<input name="usuario" type="text" size="16"><br />';
					
					if(!empty($error_login)) echo '<div id="rechazado" style="border:0 !important;padding:0 !important">' . $error_login . '</div>';
					
					echo '</div>
						<div id="password">
							Password:
							<input name="password" type="password" size="16">
							<a href="paginas/recuperarPassword.php" id="retrieve-password">&iquest;Forgot your Password?</a>					
						</div>
						<div id="submit"><input type="submit" name="login" value="Login"></div>
					</form>';
				}
					
				echo '</div>
			</div>';
		}
		
	}
?>
