<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();

	if($_REQUEST[id_movie] > 0) {
		$arr = array();

		// update ranking
		$q = "SELECT id_pelicula, num_votos, suma_votos, puntaje FROM cart_pelicula WHERE id_pelicula = " . (int) $_REQUEST[id_movie];
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			list($id_pelicula, $num_votos, $suma_votos, $puntaje) = mysql_fetch_array($s);
			
			$num_votos = $num_votos + 1;
			$suma_votos = $suma_votos + (int)$_REQUEST[rank];
			$puntaje = ($suma_votos / $num_votos);
			
			$qq = "UPDATE cart_pelicula SET num_votos='$num_votos', suma_votos='$suma_votos', puntaje='$puntaje' WHERE id_pelicula = " . (int) $_REQUEST[id_movie];
			if(mysql_query($qq)) {
				$arr = array("msg" => "Your rank was added successful.");
			} else {
				$arr = array("msg" => "Your rank was not added successful, please try again later. An error has been found.");
			}
		} else {
			$arr = array("msg" => "The movie was not found, so the information was not added.");
		}
		
		echo json_encode($arr);		
	}
?>
