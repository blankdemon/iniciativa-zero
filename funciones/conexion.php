<?php
	function conectar(){	
		global $config,$conn;
		
		$conn = mysql_connect($config["url"], $config["dbuser"], $config["dbpass"]);
		if (!$conn) {
			echo "Lo sentimos, pero no es posible conectar con nuestra base de datos";
			session_start;
			session_destroy();
			session_unset();
			$_SESSION = array();
			unset($_SESSION["usuario"]);
			unset($_SESSION["permisos"]);
			exit();
		}
		$db = mysql_select_db($config["dbname"], $conn);
		if (!$db) {
			echo "Error en la conexion";
			session_start;		
			session_destroy();		
			session_unset();		
			$_SESSION = array();		
			unset($_SESSION["usuario"]);		
			unset($_SESSION["permisos"]);		
			exit();	
		}
		return $conn;
	}
	
	function execstmt($conn,$query) {	
		$result=mysql_query($query,$conn);	
		if (!$result) { 
			echo "Búsqueda no válida $query". mysql_error(); 
			}	
			return $result;
	}
	function is_sel($a,$b) {
		if ($a==$b) return(" selected");
		return('');
	}
	
	function mostrar_error($err) {	
		print "Hubo problemas al insertar los datos en la base de datos.";	
	}
	
	function status() {
	
	}
?>