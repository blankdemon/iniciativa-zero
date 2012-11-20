<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();
	
	if(eregi("^(director|reparto)$", $_REQUEST[action]) && !empty($_REQUEST[words])) {
		$q = "SELECT * FROM cart_artista WHERE nombre LIKE '%$_REQUEST[words]%' ORDER BY nombre";
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			echo '<form name="lartista" method="post" action="">
			  <table width="418" border="1">
				<tr>
				  <td width="33">ID</td>
				  <td width="326">Nombre Artista</td>
				  <td width="19">Sel</td>
				</tr>';
				while($r = mysql_fetch_array($s)) {
					echo '<tr>
					  <td>' . $r[id_artista] . '</td>
					  <td>' . $r[nombre] . '</td>
					  <td><input type="checkbox" value="' . $r[id_artista] . '||' . $r[nombre] . '" name="id_artista"></td>
					</tr>';
				}
			echo '<tr>
				  <td colspan="3" align="right">
				  	<input type="button" onclick="$.fancybox.close();" value="cerrar" /> 
					<input type="button" name="button" onclick="seleccionarArtista(document.lartista, \'' . $_REQUEST[action] . '\');" value="seleccionar">
				  </td>
				</tr>
			  </table>
			</form>';
		} else {
			echo 'sin resultados de busqueda';
		}
	} else {
		echo 'debes buscar por algun nombre';
	}
?>

    
    
