<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();

	function reorganizarIdPeliculas() {
		$q = "SELECT * FROM cart_pelicula";
		$s = mysql_query($q);
		$i =0;
		while($r = mysql_fetch_array($s)) {
			$i++;
			mysql_query("INSERT INTO cart_genero_pelicula (id_genero, id_pelicula) VALUES ('$r[id_genero]', '$r[id_pelicula]')");
		}
		
		echo $i . ' peliculas con generos insertados.<br>';
	}
	//reorganizarIdPeliculas();
	
	function reorganizarDirectoresDePeliculas() {
		$q = "SELECT * FROM cart_peli_director";
		$s = mysql_query($q);
		$i =0;
		while($r = mysql_fetch_array($s)) {
			$qq = "SELECT * FROM cart_peli_artista WHERE id_artista = $r[id_director] AND id_pelicula = $r[id_pelicula] AND id_tipo_artista = 2";
			$ss = mysql_query($qq);
			if(!mysql_num_rows($ss)) {
				$i++;
				mysql_query("INSERT INTO cart_peli_artista (id_artista, id_pelicula, id_tipo_artista) VALUES ('$r[id_director]', '$r[id_pelicula]', '2')");
			}
		}
		
		echo $i . ' directores insertados insertados.<br>';
	}
	
	//reorganizarDirectoresDePeliculas()
	
	
	/* funciones de actualizacion de peliculas desde tabla general */
	function prepararActoresParaInsercion($r) {	
		/* hacer la insercion de peliculas en la tabla peliculas */
		//$qqq = "UPDATE cart_pelicula SET cast='" . str_replace("Cast: ", "", $r[cast]) . "' WHERE id_pelicula = " . (int) $r[id_pelicula];
		//if(mysql_query($qqq)) echo 'Pelicula ' . $r[id_pelicula] . ' actualizada correctamente.<br />';
		
		$trozos = split(",", $r[cast]);
		for($i=0;$i<sizeof($trozos);$i++) {
			if(trim($trozos[$i]) != "") {	
				$qq = "SELECT id_artista FROM cart_artista WHERE nombre LIKE '" . trim(addslashes($trozos[$i])) . "'";
				$s = mysql_query($qq) or die(mysql_error());
				if(!mysql_num_rows($s)) {
					$qq = "INSERT INTO cart_artista (date_inserted, date_updated, nombre) VALUES ('".mktime()."', '".mktime()."', '" . trim(addslashes($trozos[$i])) . "')";
					mysql_query($qq) or die(mysql_error());
					$id_artista = mysql_insert_id();
					
					$qqq = "INSERT INTO cart_peli_artista (id_pelicula, id_artista) VALUES (" . $r[id_pelicula] . ", $id_artista)";
					mysql_query($qqq) or die(mysql_error());
				}
			}
		}
		
		echo 'Pelicula ' . $r[id_pelicula] . ' actualizada<br />';
	}
		
	function crearDirectores($r) {
		$trozos = split(",", $r[director]);
		for($i=0;$i<sizeof($trozos);$i++) {
			if(trim($trozos[$i]) != "") {	
				$qq = "SELECT id_artista FROM cart_artista WHERE nombre LIKE '" . trim(addslashes($trozos[$i])) . "'";
				$s = mysql_query($qq) or die(mysql_error());
				if(!mysql_num_rows($s)) {
					$qq = "INSERT INTO cart_artista (date_inserted, date_updated, nombre) VALUES ('".mktime()."', '".mktime()."', '" . trim(addslashes($trozos[$i])) . "')";
					mysql_query($qq) or die(mysql_error());
					$id_artista = mysql_insert_id();
					
					if($id_artista > 0) {
						$qqq = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES (" . $r[id_pelicula] . ", '$id_artista', '2')";
						mysql_query($qqq) or die(mysql_error());
					}
				} else {
					list($id_artista) = mysql_fetch_array($s);
					if($id_artista > 0) {
						$qqq = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES (" . $r[id_pelicula] . ", '$id_artista', '2')";
						mysql_query($qqq) or die(mysql_error());
					}
				}
				
				echo 'Pelicula ' . $r[id_pelicula] . ' actualizada<br />';	
			}
		}
	}
	
	function corregirValoresDeMinutos($r) {
		$qqq = "UPDATE cart_pelicula SET duracion='" . trim(str_replace("min", "", $r[duracion])) . "' WHERE id_pelicula = " . (int) $r[id_pelicula];
		if(mysql_query($qqq)) echo 'Pelicula ' . $r[id_pelicula] . ' actualizada correctamente.<br />';
	}
	
	function corregirValoresDeSubtitulos($r) {
		$qqq = "UPDATE cart_pelicula SET subtitulo='" . trim(str_replace("Sub: ", "", $r[subtitulo])) . "' WHERE id_pelicula = " . (int) $r[id_pelicula];
		if(mysql_query($qqq)) echo 'Pelicula ' . $r[id_pelicula] . ' actualizada correctamente.<br />';
	}
	
	function corregirDecimalesPuntaje($r) {
		$qqq = "UPDATE cart_pelicula SET audience_that_like_it_dec='" . trim(number_format($r[audience_that_like_it],2,".","")) . "', puntaje_dec='" . trim(number_format($r[puntaje],2,".","")) . "' WHERE id_pelicula = " . (int) $r[id_pelicula];
		if(mysql_query($qqq) or die(mysql_error())) echo 'Pelicula ' . $r[id_pelicula] . ' actualizada correctamente.<br />';
	}
	
	function actualizarEnlaces($r) {
		if(!empty($r[Bitshare])) {
			$qq = "INSERT INTO cart_enlace (id_pelicula, direccion) VALUES (" . $r[id_pelicula] . ", '$r[Bitshare]')";
			mysql_query($qq);
		}
		if(!empty($r[Filestube])) {
			$qq = "INSERT INTO cart_enlace (id_pelicula, direccion) VALUES (" . $r[id_pelicula] . ", '$r[Filestube]')";
			mysql_query($qq);
		}
		if(!empty($r[Filefactory])) {
			$qq = "INSERT INTO cart_enlace (id_pelicula, direccion) VALUES (" . $r[id_pelicula] . ", '$r[Filefactory]')";
			mysql_query($qq);
		}
		
		if(!empty($r[U])) {
			$qq = "INSERT INTO cart_enlace (id_pelicula, direccion) VALUES (" . $r[id_pelicula] . ", '$r[U]')";
			mysql_query($qq);
		}
		
		echo 'Enlace de Pelicula ' . $r[id_pelicula] . ' actualizada<br />';
	}
	
	function actualizarDuracion($r) {
		$q = "SELECT Duracion FROM general WHERE name LIKE '$r[nombre]'";
		$s = mysql_query($q);
		if(mysql_num_rows($s)==1) {
			list($duracion) = mysql_fetch_array($s);
		
			$qqq = "UPDATE cart_pelicula SET duracion='" . str_replace(" min", "", $duracion) . "' WHERE duracion=0 AND id_pelicula = " . (int) $r[id_pelicula];
			if(mysql_query($qqq) or die(mysql_error())) echo 'Pelicula ' . $r[id_pelicula] . ' actualizada correctamente.<br />';
		}
	}

	function actualizarGeneros() {
		$q = "SELECT * FROM cart_genero ORDER BY nombre";
		$s = mysql_query($q);
		while($r = mysql_fetch_array($s)) {
			$id_genero = $r[id_genero];
			$nombre = $r[nombre];
			$trozos_nombre = explode(",", $nombre);
			if(sizeof($trozos_nombre) > 0) {
				for($i=0;$i<sizeof($trozos_nombre);$i++){
					$genero_a_insertar = trim($trozos_nombre[$i]);
					$qq = "SELECT * FROM cart_genero WHERE nombre LIKE '$genero_a_insertar'";
					$ss = mysql_query($qq);
					
					if(mysql_num_rows($ss)) {
						// con el indice que se selecciona actualizar todas las peliculas con el indica ya creado
						$rr = mysql_fetch_array($ss);
						$genero_ya_creado = $rr[id_genero];
						
						$qqq = "SELECT * FROM cart_genero_pelicula WHERE id_genero=$genero_ya_creado";
						$sss = mysql_query($qqq);
						
						while($row = mysql_fetch_array($sss)) {
							$query = "INSERT INTO cart_genero_pelicula (id_genero, id_pelicula) VALUES ('$genero_ya_creado', '$row[id_pelicula]')";
							mysql_query($query);
							
							echo $query . '<br>';
						}					
					} else {
						//
						mysql_query("INSERT INTO cart_genero (nombre) VALUES ('$genero_a_insertar')");
						$id_genero_insertado = mysql_insert_id();
						
						$qqq = "SELECT * FROM cart_genero_pelicula WHERE id_genero=$id_genero";
						$sss = mysql_query($qqq);
						
						while($row = mysql_fetch_array($sss)) {
							$query = "INSERT INTO cart_genero_pelicula (id_genero, id_pelicula) VALUES ('$id_genero_insertado', '$row[id_pelicula]')";
							mysql_query($query);
							
							echo $query . '<br>';
						}
					}
				}
				
			}
		}
	}
	
	function eliminarGeneros() {
		$q = "SELECT * FROM cart_genero ORDER BY id_genero";
		$s = mysql_query($q);
		while($r = mysql_fetch_array($s)) {
			$id_genero = $r[id_genero];
			$nombre = $r[nombre];
			$trozos_nombre = explode(",", $nombre);
			if(sizeof($trozos_nombre) > 1) {
				$q = "DELETE FROM cart_genero WHERE id_genero = $id_genero";
				mysql_query($q);
				echo $q . '<br>';
				
				$q = "DELETE FROM cart_genero_pelicula WHERE id_genero = $id_genero";
				mysql_query($q);
				echo $q . '<br>';
			}
		}
	}
	
	//DELETE FROM `cart_genero_pelicula` WHERE id_genero IN ()
	
	//eliminarGeneros();
	//actualizarGeneros();

	
	function prepararGenerosParaInsercion($r) {
		$id_genero = $r[id_genero];
		$trozos_nombre = explode(",", $id_genero);
		
		for($i=0;$i<sizeof($trozos_nombre);$i++){
			$genero_a_insertar = trim(addslashes($trozos_nombre[$i]));
	
			$qq = "SELECT id_genero FROM cart_genero WHERE nombre LIKE '$genero_a_insertar'";
			$s = mysql_query($qq) or die(mysql_error());
			if(!mysql_num_rows($s)) {
				$qq = "INSERT INTO cart_genero (nombre) VALUES ('$genero_a_insertar')";
				mysql_query($qq) or die(mysql_error());
				$id_genero = mysql_insert_id();
	
				$qqq = "INSERT INTO cart_genero_pelicula (id_pelicula, id_genero) VALUES (" . $r[id_pelicula] . ", $id_genero)";
				mysql_query($qqq) or die(mysql_error());
			} else {
				list($id_genero) = mysql_fetch_array($s);
				
				$qqq = "SELECT * FROM cart_genero_pelicula WHERE id_pelicula = $r[id_pelicula] AND id_genero = $id_genero";
				$sss = mysql_query($qqq);
				
				if(!mysql_num_rows($sss)) {				
					$qqq = "INSERT INTO cart_genero_pelicula (id_pelicula, id_genero) VALUES (" . $r[id_pelicula] . ", $id_genero)";
					mysql_query($qqq) or die(mysql_error());
				}
			}

			echo 'Pelicula ' . $r[id_pelicula] . ' actualizada<br />';
		}
	}
	
	function actualizarImagenes($r) {		
		if($r[id_imagen]>0) {
			$q = "SELECT * FROM imagen_pelicula WHERE id_pelicula=$r[id_pelicula] AND id_imagen=$r[id_imagen]";
			$s = mysql_query($q) or die(mysql_error());
			
			if(!mysql_num_rows($s)) {
				$q = "INSERT INTO imagen_pelicula (id_pelicula, id_imagen) VALUES ('$r[id_pelicula]', '$r[id_imagen]')";
				$s = mysql_query($q);
				echo $q . '<br>';
			}
		}
	}
	
	
	
	/*
		this update the mpaa rating from text to number indexes
		actualizarMPAA_Ratings();
	*/
	function actualizarMPAA_Ratings() {
		$q = "SELECT * FROM cart_mpaa_rating";
		$s = mysql_query($q);
		while($r = mysql_fetch_array($s)) {
			mysql_query("UPDATE cart_pelicula SET mpaa_rating = '$r[id_mpaa_rating]' WHERE mpaa_rating = '$r[nombre]'");
		}
	}
	
	
	$q = "SELECT * FROM cart_pelicula";
	$s = mysql_query($q);
	while($r = mysql_fetch_array($s)) {
		// OK
		// prepararActoresParaInsercion($r);
		
		// OK
		//crearDirectores($r);
		
		// OK
		//corregirValoresDeMinutos($r);
		
		// OK
		//corregirValoresDeSubtitulos($r);
		
		// OK
		//prepararGenerosParaInsercion($r);
		
		// OK
		//corregirDecimalesPuntaje($r);
		
		// OK
		//actualizarEnlaces($r);
		
		// OK
		//actualizarDuracion($r);

		//actualizarImagenes($r);
	}
?>
