<?php
	function retornarEstados($estado) {
		switch($estado) {
			case 'habilitada':				
				$color = '00ff00';
				$status = 'Enabled';
			break;
			case 'cerrada':
				$color = 'ff0000';
				$status = 'Closed';
			break;
			/*
			case 'enviada':
				$color = 'FF9900';
				$status = 'Sent';
				break;
			*/
			case 'aprobada':
				$color = '00CC33';
				$status = 'Sent';
				break;
			case 'rechazada':
				$color = 'ff0000';
				$status = 'Rejected';
				break;
		}
		return '<span style="background:#' . $color . ';">' . $status . '</span>';
	}
		
	/*************************
		desplegar primer nivel
		*********************/
	function comprasDeNivelZero($id_compra = 0) {
		global $nivel;
		
		/* obtengo cada uno de mis hijos */
		
		$q01 = "SELECT 
			cu.id_cuenta, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, c.orden, c.estado
		FROM compras AS c
		INNER JOIN cuentas AS cu ON (c.id_persona = cu.id_cuenta)
		WHERE c.id_compra_padre=" . (int) $id_compra . " ORDER BY c.orden ASC";
		$s01 = mysql_query($q01);
		$total_ventas_nivel_1 = mysql_num_rows($s01);	
		
		/* recorro cada una de mis ventas */
		echo '<ul id="tree">
		<li id="primer">Your Level Cero</li>';			
			if($total_ventas_nivel_1) {
				while($r = mysql_fetch_array($s01)) {
					echo '<li><div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';					
						//if($r[orden]==3) echo '(100%) ';
						if($r[orden]>3) echo '(10%) ';
						$nombre = trim($r[nombre]);
						echo (!empty($nombre)) ? ucfirst($nombre) : 'Name has not been entered yet...';
						if($r[estado]==6) echo '<span class="vender" onclick="vende(' . $r[id_compra] . ');" title="Add referrals ' . $r[nombre] . '">sell</span>';
						echo retornarEstados($r[estado]);
						echo '</div>';	
		
						echo arbolDeSegundoNivel($r[id_cuenta], $r[id_compra]);	
				
					echo '</li>';	
				}
				mysql_free_result($s01);
			}
			
			/* obtengo las invitaciones realizadas por esta persona y esta compra */
			$q01 = "SELECT 
				CONCAT(i.nombre , ' ' , i.apellido) nombre, i.id_invitacion id_compra, i.estado
			FROM invitaciones i
			WHERE i.id_persona = $_SESSION[id_cuenta] AND id_compra=$id_compra
			ORDER BY i.id_invitacion";			
			$s01 = mysql_query($q01);
			$total_invitaciones_nivel_1 = mysql_num_rows($s01);	
			if($total_invitaciones_nivel_1) {
				while($r = mysql_fetch_array($s01)) {
					echo '<li>
						<div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';
							$nombre = trim($r[nombre]);
							echo (!empty($nombre)) ? ucfirst($nombre) : 'Name has not been entered yet...';
							//echo '<span><a class="pinvitaciones" href="paginas/formas_pago.php?id_invitacion=' . $r[id_compra] . '&pag_invitacion=true">Pay</a></span>';
							echo retornarEstados($r[estado]);
						echo '</div>
					</li>';				
				}					
				mysql_free_result($s01);
			}
		echo '</ul>';
	}
	
	
	/*************************
		desplegar segundo nivel
		*********************/
	function arbolDeSegundoNivel($id_cuenta, $id_compra) {
		global $nivel;
		
		/* obtengo cada uno de mis hijos */
		$q01 = "SELECT 
			cu.id_cuenta, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, c.orden, c.estado
		FROM compras AS c
		INNER JOIN cuentas AS cu ON (c.id_persona = cu.id_cuenta)
		WHERE c.id_compra_padre=$id_compra
		ORDER BY c.orden ASC";		
		$s01 = mysql_query($q01);
		$total_ventas_nivel_1 = mysql_num_rows($s01);	
		
		/* recorro cada una de mis ventas */				
		if($total_ventas_nivel_1) {
			while($r = mysql_fetch_array($s01)) {
				$lcomp .= '<li>';
					$lcomp .= '<div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';				
						//if($r[orden]==3) $lcomp .= '(100%) ';
						if($r[orden]>3) $lcomp .= '(10%) ';
						$lcomp .= (!empty($r[nombre])) ? ucfirst($r[nombre]) : 'Name has not been entered yet...';
						if($r[estado]==6) $lcomp .= '<span class="vender" onclick="vende(' . $r[id_compra] . '); " title="Agregar ventas a ' . $r[nombre] . '">sell</span>';
						$lcomp .= retornarEstados($r[estado]);					
					$lcomp .= '</div>';

				/* retorna la informacion del siguiente nivel*/					
				$lcomp .= arbolDeTercerNivel($r[id_cuenta], $r[id_compra]);				
				$lcomp .= '</li>';				
			}
			mysql_free_result($s01);
		}
		
		/* obtengo las invitaciones realizadas por esta persona y esta compra */
		$q01 = "SELECT 
			CONCAT(i.nombre , ' ' , i.apellido) nombre, i.id_invitacion id_compra, i.estado
		FROM invitaciones i
		WHERE i.id_persona = $id_cuenta AND id_compra=$id_compra
		ORDER BY i.id_invitacion";			
		$s01 = mysql_query($q01);
		$total_invitaciones_nivel_1 = mysql_num_rows($s01);	
		if($total_invitaciones_nivel_1) {
			while($r = mysql_fetch_array($s01)) {
				$linv .= '<li>';
					$linv .= '<div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';
						$nombre = trim($r[nombre]);
						$linv .= (!empty($nombre)) ? ucfirst($nombre) : 'Name has not been entered yet...';
						$linv .= retornarEstados($r[estado]);
						//$linv .= '<span><a class="pinvitaciones" href="paginas/formas_pago.php?id_invitacion=' . $r[id_compra] . '&pag_invitacion=true">Pay</a></span>';
					$linv .= '</div>';				
				$linv .= '</li>';				
			}					
			mysql_free_result($s01);
		}
		
		if($total_ventas_nivel_1 > 0 || $total_invitaciones_nivel_1 > 0) {
			$rt = '<ul><li id="segundo">First Level</li>';	
			$rt .= $lcomp;
			$rt .= $linv;
			$rt .= '</ul>';
			
			return $rt;
		}
	}
	
	/***********************
		desplegar tercer nivel
		*******************/
	function arbolDeTercerNivel($id_cuenta, $id_compra) {
		global $nivel;
		
		/* obtengo cada uno de mis hijos */
		$q01 = "SELECT 
			cu.id_cuenta, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, c.orden, c.estado
		FROM compras AS c
		INNER JOIN cuentas AS cu ON (c.id_persona = cu.id_cuenta)
		WHERE c.id_compra_padre=$id_compra ORDER BY c.orden ASC";		
		$s01 = mysql_query($q01);
		$total_ventas_nivel_1 = mysql_num_rows($s01);	
		
		/* recorro cada una de mis ventas */
		if($total_ventas_nivel_1) {
			while($r = mysql_fetch_array($s01)) {
				$lcomp .= '<li>';
					$lcomp .= '<div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';				
						//if($r[orden]==3) $lcomp .= '(100%) ';
						if($r[orden]>3) $lcomp .= '(10%) ';
						$lcomp .= (!empty($r[nombre])) ? ucfirst($r[nombre]) : 'Name has not been entered yet...';
						if($r[estado]==6) $lcomp .= '<span class="vender" onclick="vende(' . $r[id_compra] . '); " title="Agregar ventas a ' . $r[nombre] . '">Sell</span>';
						$lcomp .= retornarEstados($r[estado]);	
					$lcomp .= '</div>';

					/* se depliega la informacion del siguiente nivel */	
					$lcomp .= arbolDeCuartoNivel($r[id_cuenta], $r[id_compra]);				
				$lcomp .= '</li>';				
			}
			mysql_free_result($s01);
		}
		
		/* obtengo las invitaciones realizadas por esta persona y esta compra */
		$q01 = "SELECT 
			CONCAT(i.nombre , ' ' , i.apellido) nombre, i.id_invitacion id_compra, i.estado
		FROM invitaciones i
		WHERE i.id_persona = $id_cuenta AND id_compra=$id_compra
		ORDER BY i.id_invitacion";			
		$s01 = mysql_query($q01);
		$total_invitaciones_nivel_1 = mysql_num_rows($s01);	
		if($total_invitaciones_nivel_1) {
			while($r = mysql_fetch_array($s01)) {
				$linv .= '<li>';
				$linv .= '<div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';
				$nombre = trim($r[nombre]);
				$linv .= (!empty($nombre)) ? ucfirst($nombre) : 'Name has not been entered yet...';
				//$linv .= '<span><a class="pinvitaciones" href="paginas/formas_pago.php?id_invitacion=' . $r[id_compra] . '&pag_invitacion=true">Pay</a></span>';
				$linv .= retornarEstados($r[estado]);
				$linv .= '</div>';					
				$linv .= '</li>';				
			}					
			mysql_free_result($s01);
		}
			
		if($total_ventas_nivel_1 > 0 || $total_invitaciones_nivel_1 > 0) {
			$rt .= '<ul><li id="tercer">Second Level</li>';
			$rt .= $lcomp;
			$rt .= $linv;
			$rt .= '</ul>';
						
			return $rt;
		}
	}
	
	/***********************
		desplegar cuarto nivel
		*******************/
	function arbolDeCuartoNivel($id_cuenta, $id_compra) {
		global $nivel;
		
		/* obtengo cada uno de mis hijos */
		$q01 = "SELECT 
			cu.id_cuenta, c.id_compra, CONCAT(cu.nombres , ' ' , cu.apellidos) nombre, c.orden, c.estado
		FROM compras AS c
		INNER JOIN cuentas AS cu ON (c.id_persona = cu.id_cuenta)
		WHERE c.id_compra_padre=$id_compra
		ORDER BY c.orden ASC";		
		$s01 = mysql_query($q01);
		$total_ventas_nivel_1 = mysql_num_rows($s01);	
		
		/* recorro cada una de mis ventas */		
		if($total_ventas_nivel_1) {
			while($r = mysql_fetch_array($s01)) {
				$lcomp .= '<li>';
					$lcomp .= '<div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';				
						if($r[orden]==3) $lcomp .= '(100%) ';
						if($r[orden]>3) $lcomp .= '(20%) ';
						$lcomp .= (!empty($r[nombre])) ? ucfirst($r[nombre]) : 'Name has not been entered yet...';
						if($r[estado]==6) $lcomp .= '<span class="vender" onclick="vende(' . $r[id_compra] . '); " title="Add referrals to ' . $r[nombre] . '">Sell</span>';
						$lcomp .= retornarEstados($r[estado]);
					$lcomp .= '</div>';
				$lcomp .= '</li>';				
			}
			mysql_free_result($s01);
		}
		
		/* obtengo las invitaciones realizadas por esta persona y esta compra */
		$q01 = "SELECT 
			CONCAT(i.nombre , ' ' , i.apellido) nombre, i.id_invitacion id_compra, i.estado
		FROM invitaciones i
		WHERE i.id_persona = $id_cuenta AND id_compra=$id_compra
		ORDER BY i.id_invitacion";
		$s01 = mysql_query($q01);
		$total_invitaciones_nivel_1 = mysql_num_rows($s01);	
		if($total_invitaciones_nivel_1) {
			while($r = mysql_fetch_array($s01)) {
				$linv .= '<li><div onMouseOver="uno(this,\'ccf3f4\');" onMouseOut="dos(this,\'FFFFFF\');">';
				$nombre = trim($r[nombre]);
				$linv .= (!empty($nombre)) ? ucfirst($nombre) : 'Name has not been entered yet...';
				//$linv .= '<span><a class="pinvitaciones" href="paginas/formas_pago.php?id_invitacion=' . $r[id_compra] . '&pag_invitacion=true">Pay</a></span>';
				$linv .= retornarEstados($r[estado]);
				$linv .= '</div>';						
				$linv .= '</li>';
			}					
			mysql_free_result($s01);
		}		
		
		if($total_ventas_nivel_1 > 0 || $total_invitaciones_nivel_1 > 0) {
			$rt .= '<ul><li id="cuarto">Thirt Level</li>';
			$rt .= $lcomp;
			$rt .= $linv;
			$rt .= '</ul>';
						
			return $rt;
		}		
	}
	
	/* 
		CADA COMPRA que hace esta persona se contempla como GRUPO
		$id_persona viene definido con el identificador de quien inicia sesion
	*/	
	function listarArbol() {
		global $id_compra, $nivel;
		$query00 = "SELECT 
			CONCAT(cu.nombres , ' ' , cu.apellidos) nombre,
			c.id_compra, c.estado
		FROM compras AS c
		INNER JOIN cuentas AS cu ON (c.id_persona = cu.id_cuenta)
		WHERE c.id_compra_padre=" . (int) $_REQUEST[id_compra];
		
		//echo $query00;

		$result00 = mysql_query($query00) or die(mysql_error());
		$total_mis_ventas = mysql_num_rows($result00);

		echo '<h1>Tree View</h1>';
	
		if ($total_mis_ventas) {
			$fila00 = mysql_fetch_array($result00);
			$nivel = $fila00[nivel];
			
			echo 'Your profit is indicated as percentage (%) on every sale paid (10, 20 or 100%).
			<div id="sidetreecontrol" align="right">
				<a href="?#" title="close tree">Close tree</a> | <a href="?#" title="expand all tree">Expand tree</a>
			</div>
			<span style="margin-left:30px;"><img src="imagenes/yo.png" align="absmiddle" /> <b>I</b></span>';
			
			comprasDeNivelZero((int) $_REQUEST[id_compra]);
		} else {
			echo '<div id="rechazado">You have not enough accepted invitations for generate a tree view.</div>';
		}
		
		echo '<div align="center" style="margin-top:20px;">
			<input onclick="window.location=\'?id_pagina=20&id_compra=' . (int) $_REQUEST[id_compra] . '\';" value="Back" type="button">
		</div>';
	}
	
	listarArbol();
?>
