<?php
	class Configuracion {
		var $nombreSitio = 'Wiki-Global';
		var $Slogan = '';
		
		// invitaciones
		var $maximaCantidadInvitaciones = 20;

		/* retorna el nombre de un mes o la opcion de restornarlos todos con mes seleccionado */
		function nombreMes($idmes=0, $all=false, $retornar=false) {
			$mes = array(
						  1 => "january",
						  2 => "february",
						  3 => "march",
						  4 => "april",
						  5 => "may",
						  6 => "june",
						  7 => "july",
						  8 => "agoust",
						  9 => "september",
						  10 => "october",
						  11 => "november",
						  12 => "december",
						  0 => "all"
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
		
		/* muestra inputs para fecha: dia, mes, año y nombre que pueda tener para diferenciar fechas */
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
			$vocalti= array ("á","é","í","ó","ú","ö","Á","É","Í","Ó","Ú", "ñ", "Ñ");
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
