<?php
/*
 * Created on 18-11-2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 	class Utils {

		/* retorna el nombre de un mes o la opcion de restornarlos todos con mes seleccionado */
		function nombreMes($idmes=0, $all=false, $retornar=false) {
			$mes = array(
						  1 => "enero",
						  2 => "febrero",
						  3 => "marzo",
						  4 => "abril",
						  5 => "mayo",
						  6 => "junio",
						  7 => "julio",
						  8 => "agosto",
						  9 => "septiembre",
						  10 => "octubre",
						  11 => "noviembre",
						  12 => "deciembre",
						  0 => "todos"
						  );
			if(!eregi("^\*$", $all)) {
				foreach($mes as $id => $nombre) {
					if($idmes == $id) return $nombre;					
				}
			} else {
				if(empty($idmes)) $r.= '<option value="">month';
				foreach($mes as $id => $nombre) {
					$r.= '<option value="';
					$r.=  ($id<10) ? '0' . $id : $id;
					$r.= '"';
					if($idmes == $id) $r.= ' selected';
					$r.= '>' . $nombre;
				}
				if($retornar) {
					return $r;
				} else {
					echo $r;
				}
			}
		}
		
		/* muestra inputs para fecha: dia, mes, aÃ±o y nombre que pueda tener para diferenciar fechas */
		function inputsFecha($dia=0, $mes=0, $year=0, $name="", $retornar=false) {
			$r = '<select name="dia'.$name.'">';
			if(empty($dia)) $r .= '<option value="">day';
			for($i=1;$i<32;$i++) {
				$r .= '<option value="'.$i.'"';
				if($i == $dia) $r .= ' selected';
				$r .= '>'.$i;
			}
			$r .= '</select> ';
			$r .= '<select name="mes'.$name.'">';
			$r .= $this->nombreMes($mes, "*", true);
			$r .= '</select> ';
			$r .= '<input name="year'.$name.'" type="text" size="4" maxlength="4" value="';
			if($year) $r .= $year;
			$r .= '" />';
			if($retornar) {
				return $r;
			} else {
				echo $r;
			}
		}
		
		function reemplazarCaracteres($c) {
			$vocalti= array ("Ã¡","Ã©","Ã­","Ã³","Ãº","Ã¶","Ã�","Ã‰","Ã�","Ã“","Ãš", "Ã±", "Ã‘");
			$vocales= array ("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;", "&ouml;", "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;", "&ntilde;", "&Ntilde;");
			for($i=0;$i<sizeof($vocalti);$i++) {
				$c = str_replace($vocalti[$i], $vocales[$i], $c);
			}
			return $c;
		}

		function retornarPaises($id_pais) {
			$q = "SELECT * FROM pais ORDER BY nombre";
			$s = mysql_query($q);
			$rr = '<select name="id_pais">';
			if($id_pais<1) $rr .= '<option value="">select...';
			while($r = mysql_fetch_array($s)) {
				$rr .= '<option value="' . $r[id_pais] . '"';
				if($id_pais == $r[id_pais]) $rr .= ' selected';
				$rr .= '>' . htmlentities($r[nombre]);
			}
			$rr .= '</select>';
			return $rr;
		}
 	};
?>
