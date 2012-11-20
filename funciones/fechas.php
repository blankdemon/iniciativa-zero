<?php
	///para saber el año actual para calcular si es bisiesto con la funcion date(L) si es bisiesto entrega un 1 y si no un 0.
	$ahora_es = date(L);
	/////////////////
	
	$dias_mes[0] = "31";
	$dias_mes[1] = "31";

	if($ahora_es==1){
		$dias_mes[2] = "29";
	}else{
		$dias_mes[2] = "28";
	}

	$dias_mes[3] = "31";	
	$dias_mes[4] = "30";	
	$dias_mes[5] = "31";	
	$dias_mes[6] = "30";	
	$dias_mes[7] = "31";	
	$dias_mes[8] = "31";	
	$dias_mes[9] = "30";	
	$dias_mes[10] = "31";
	$dias_mes[11] = "30";
	$dias_mes[12] = "31";

	$meses[1] = "Enero";	
	$meses[2] = "Febrero";
	$meses[3] = "Marzo";
	$meses[4] = "Abril";
	$meses[5] = "Mayo";
	$meses[6] = "Junio";
	$meses[7] = "Julio";
	$meses[8] = "Agosto";
	$meses[9] = "Septiembre";
	$meses[10] = "Octubre";
	$meses[11] = "Noviembre";
	$meses[12] = "Diciembre";

	$dias_semana[0] = "Domingo";
	$dias_semana[1] = "Lunes";
	$dias_semana[2] = "Martes";
	$dias_semana[3] = "Mi&eacute;rcoles";
	$dias_semana[4] = "Jueves";
	$dias_semana[5] = "Viernes";
	$dias_semana[6] = "S&aacute;bado";


function fecha_dmy($f) { // OTRA FUNCION DE CAMBIO DE FECHA
	if($f != "") {
		$fecha = explode("-", $f);
		$ano = $fecha[0];
		$mes = $fecha[1];
		$dia = $fecha[2];
		if(strlen($mes) == 1) { $mes = "0" . $mes; }
		if(strlen($dia) == 1) { $dia = "0" . $dia; }
		return $dia . "-" . $mes . "-" . $ano;	
	} else {
		return $f;	
	}
}

function fecha_ymd($f) { // OTRA FUNCION DE CAMBIO DE FECHA
	if($f != "") {
		$fecha = explode("-", $f);
		$dia = $fecha[0];
		$mes = $fecha[1];
		$ano = $fecha[2];
		if(strlen($mes) == 1) { $mes = "0" . $mes; }
		if(strlen($dia) == 1) { $dia = "0" . $dia; }
		return $ano . "-" . $mes . "-" . $dia;	
	} else {
		return $f;	
	}
}

function UNIX_TIME($f) { // obtenemos el epoch
	$fecha = explode("-", $f);
	$ano = $fecha[0];
	$mes = $fecha[1];
	$dia = $fecha[2];
	return date("U", mktime(0,0,0,$mes, $dia, $ano));
}

function fecha_larga($f) { // PARA CREAR FECHAS LARGAS
	if($f != "") {
		global $dias_mes; 
		global $meses;
		$fecha = explode("-", $f);
		$ano = $fecha[0];
		$mes = $fecha[1];
		$dia = $fecha[2];
		return strtoupper(abs($dia) . " de " . $meses[abs($mes)] . " de " . abs($ano));
	} else {
		return "";	
	}
}

function CalculaEdad($date, $cuando) {
	global $dias_mes; 
	$fecha = explode("-", $date);
	$ano = abs($fecha[0]);
	$mes = abs($fecha[1]);
	$dia = abs($fecha[2]);
	if ($cuando == "") {
		$now=localtime();
		if ($ano_rim_hoy =='' || $ano_rim_hoy=='0') {$ano_rim_hoy = 1900 + $now[5];}
		if ($mes_rim_hoy =='' || $mes_rim_hoy=='0') {$mes_rim_hoy = 1 + $now[4];}
		if ($dia_rim_hoy =='' || $dia_rim_hoy=='0') {$dia_rim_hoy =  $now[3];}
	} else {
		$fecha2 = explode("-", $cuando);
		$ano_rim_hoy = $fecha2[0];
		$mes_rim_hoy = $fecha2[1];
		$dia_rim_hoy = $fecha2[2];	
	}

	$edad_meses = 0;
	$edad_dias = 0;
	$edad_anos = $ano_rim_hoy - $ano - 1;

	if ($dia_rim_hoy > $dia) {
		$edad_dias = $dia_rim_hoy - $dia;	
	}

	if ($dia_rim_hoy < $dia) {
		$edad_dias = ($dias_mes[$mes] + $dia_rim_hoy) - $dia;
	} 

  if ($mes_rim_hoy > $mes) {
		$edad_meses = $mes_rim_hoy - $mes;
		if ($dia_rim_hoy > $dia) {
			$edad_meses = $mes_rim_hoy - $mes;
		}
		if ($dia_rim_hoy < $dia) {
			$edad_meses = $mes_rim_hoy - $mes - 1;
		}		
		$edad_anos = $edad_anos + 1;
	} elseif ($mes_rim_hoy < $mes) {
				$edad_meses = (11 + $mes_rim_hoy) - $mes - 1;    	
				if ($dia_rim_hoy > $dia) {
					$edad_meses = (12 + $mes_rim_hoy) - $mes;	
				}
				if ($dia_rim_hoy < $dia) {
					$edad_meses = (12 + $mes_rim_hoy) - $mes - 1;	
				}
		} else {
					if ($dia_rim_hoy < $dia) {
						$edad_meses = (12 + $mes_rim_hoy) - $mes - 1;	
					}
					if ($dia_rim_hoy >= $dia) {
						$edad_anos = $edad_anos + 1;
					}
				}

	if ($edad_anos > 1) { $as = "S"; }
	if ($edad_meses > 1) { $ms = "ES"; }
	if ($edad_dias > 1) { $ds = "S"; }
	return $edad_anos . " AÑO" . $as . ", " . $edad_meses . " MES" . $ms . ", $edad_dias DIA" . $ds;

}


function TiempoPasado($date, $cuando) {
	global $dias_mes; 
	$fecha = explode("-", $date);
	$ano = abs($fecha[0]);
	$mes = abs($fecha[1]);
	$dia = abs($fecha[2]);

	if ($cuando == "") {
		$now=localtime();
		if ($ano_rim_hoy =='' || $ano_rim_hoy=='0') {$ano_rim_hoy = 1900 + $now[5];}
		if ($mes_rim_hoy =='' || $mes_rim_hoy=='0') {$mes_rim_hoy = 1 + $now[4];}
		if ($dia_rim_hoy =='' || $dia_rim_hoy=='0') {$dia_rim_hoy =  $now[3];}
	} else {
		$fecha2 = explode("-", $cuando);
		$ano_rim_hoy = $fecha2[0];
		$mes_rim_hoy = $fecha2[1];
		$dia_rim_hoy = $fecha2[2];	
	}

    $edad_meses = 0;
    $edad_dias = 0;

	$edad_anos = $ano_rim_hoy - $ano - 1;

	if ($dia_rim_hoy > $dia) {
		$edad_dias = $dia_rim_hoy - $dia;	
	}



	if ($dia_rim_hoy < $dia) {

		$edad_dias = ($dias_mes[$mes] + $dia_rim_hoy) - $dia;

	} 



    if ($mes_rim_hoy > $mes) {

    	$edad_meses = $mes_rim_hoy - $mes;

		if ($dia_rim_hoy > $dia) {

			$edad_meses = $mes_rim_hoy - $mes;

		}

		if ($dia_rim_hoy < $dia) {

			$edad_meses = $mes_rim_hoy - $mes - 1;

		}		

		$edad_anos = $edad_anos + 1;

	} elseif ($mes_rim_hoy < $mes) {

    	$edad_meses = (11 + $mes_rim_hoy) - $mes - 1;    	

		if ($dia_rim_hoy > $dia) {

			$edad_meses = (12 + $mes_rim_hoy) - $mes;	

		}

		if ($dia_rim_hoy < $dia) {

			$edad_meses = (12 + $mes_rim_hoy) - $mes - 1;	

		}

	} else {

		if ($dia_rim_hoy < $dia) {

			$edad_meses = (12 + $mes_rim_hoy) - $mes - 1;	

		}

		if ($dia_rim_hoy >= $dia) {

			$edad_anos = $edad_anos + 1;

		}

	}



	if ($edad_anos > 1) { $as = "S"; }

	if ($edad_meses > 1) { $ms = "ES"; }

	if ($edad_dias > 1) { $ds = "S"; }



	if ($edad_anos > 2) { return "Vencida"; } else { return "Vigente"; }

//	return $edad_anos . " AÑO" . $as . ", " . $edad_meses . " MES" . $ms . ", $edad_dias DIA" . $ds;



}



function dia_semana($f) {
	global $dias_semana; 
	$fecha = explode("-", $f);
	$ano = $fecha[0];
	$mes = $fecha[1];
	$dia = $fecha[2];
	return $dias_semana[date("w", mktime(0, 0, 0, $mes, $dia, $ano))];
}



//
// TODAS LAS COMPENSACIONES DE + O - 3600 SON PARA LAS SEMANAS EN QUE SE EFECTUA EL CAMBIO DE HORA
// SI TIENES UNA MEJOR IDEA DE COMO OBTENER LAS FECHAS DE INICIO Y FIN DE SEMANAS... APLICALAS!!!!
// ESTO ES LO MEJOR QUE SE LE PUDO OCURRIR A UN PERIODISTA (:p)

	function semana_anterior_inicio($f) {
		global $dias_mes; 
		$fecha = explode("-", $f);
		$ano = abs($fecha[0]);
		$mes = abs($fecha[1]);
		$dia = abs($fecha[2]);
		$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!
		$sunday_offset = ($dayOfWeek + 7) * 60 * 60 * 24;
	
		$dia_semana_fd = date("w", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
	
		if($dia_semana_fd != 1) {
			if($dia_semana_fd == 0) {
				$sunday_offset = (($dayOfWeek + 7) * 60 * 60 * 24) - 3600;
			}
			if($dia_semana_fd == 2) {
				$sunday_offset = (($dayOfWeek + 7) * 60 * 60 * 24) + 3600;
			}
		}	
	
		$fd = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
		return $fd;
	}

	function semana_anterior_fin($f) {
		$fecha = explode("-", $f);
		$ano = $fecha[0];
		$mes = $fecha[1];
		$dia = $fecha[2];
		$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!
		$saturday_offset = ($dayOfWeek + 1) * 60 * 60 * 24 ;
	
		$dia_semana_ld = date("w", mktime(0,0,0,$mes,$dia + 1,$ano) - $saturday_offset);
	
		if($dia_semana_ld != 0) {
			if($dia_semana_ld == 6) {
				$saturday_offset = (($dayOfWeek + 1) * 60 * 60 * 24) - 3600;
			}
			if($dia_semana_ld == 1) {
				$saturday_offset = (($dayOfWeek + 1) * 60 * 60 * 24) + 3600;
			}
		}	
		$ld  = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $saturday_offset);
		return $ld;
	}

	function semana_actual_inicio($f) {
		global $dias_mes; 
		$fecha = explode("-", $f);
		$ano = abs($fecha[0]);
		$mes = abs($fecha[1]);
		$dia = abs($fecha[2]);
		$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!
		$sunday_offset = $dayOfWeek * 60 * 60 * 24;
	
		$dia_semana_fd = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
	
		if($dia_semana_fd != 1) {
			if($dia_semana_fd == 0) {
				$sunday_offset = ($dayOfWeek * 60 * 60 * 24) - 3600;
			}
			if($dia_semana_fd == 2) {
				$sunday_offset = ($dayOfWeek * 60 * 60 * 24) + 3600;
			}
		}	
	
		$fd = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
		return $fd;
	}



	function semana_proceso_actual_fin($f) {
		$ld = semana_siguiente_inicio($f);
		return $ld;
	}



function semana_actual_fin($f) {
	$fecha = explode("-", $f);
	$ano = $fecha[0];
	$mes = $fecha[1];
	$dia = $fecha[2];
	$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));
	if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!
	$saturday_offset = (6 - $dayOfWeek) * 60 * 60 * 24 ;

	$dia_semana_ld  = date("w", mktime(0,0,0,$mes,$dia + 1,$ano) + $saturday_offset);

	if($dia_semana_ld != 0) {
		if($dia_semana_ld == 6) {
			$saturday_offset = ((6 - $dayOfWeek) * 60 * 60 * 24) - 3600;
		}
		if($dia_semana_ld == 1) {
			$saturday_offset = ((6 - $dayOfWeek) * 60 * 60 * 24) + 3600;
		}
	}	

	$ld  = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) + $saturday_offset);

	return $ld;
}


function semana_siguiente_inicio($f) {


	global $dias_mes; 

	$fecha = explode("-", $f);

	$ano = abs($fecha[0]);

	$mes = abs($fecha[1]);

	$dia = abs($fecha[2]);

	$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));

	if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!

	$sunday_offset = ($dayOfWeek - 7) * 60 * 60 * 24;

	$dia_semana_fd = date("w", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);


	if($dia_semana_fd != 1) {

		if($dia_semana_fd == 0) {

			$sunday_offset = (($dayOfWeek - 7) * 60 * 60 * 24) - 3600;

		}

		if($dia_semana_fd == 2) {

			$sunday_offset = (($dayOfWeek - 7) * 60 * 60 * 24) + 3600;

		}

	}	



	$fd = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
	return $fd;

}



	function semana_siguiente_fin($f) {
		$fecha = explode("-", $f);
		$ano = $fecha[0];
		$mes = $fecha[1];
		$dia = $fecha[2];
		$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!
		$saturday_offset = (13 - $dayOfWeek) * 60 * 60 * 24;
		$dia_semana_ld  = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) + $saturday_offset);
	
		if($dia_semana_ld != 0) {
			if($dia_semana_ld == 6) {
				$saturday_offset = ((13 - $dayOfWeek) * 60 * 60 * 24) - 3600;
			}
			if($dia_semana_ld == 1) {
				$saturday_offset = ((13 - $dayOfWeek) * 60 * 60 * 24) + 3600;
			}
		}	
	
		$ld  = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) + $saturday_offset);
		return $ld;
	}

	function semana_final_inicio($f, $c) {
		if(abs($c) == 0) { $c = 6; }
	
		global $dias_mes; 
		$fecha = explode("-", $f);
		$ano = abs($fecha[0]);
		$mes = abs($fecha[1]);
		$dia = abs($fecha[2]);
		$dias_sumados = ($c + 1) * 7;
	
		$dayOfWeek=date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		if($dayOfWeek == 0) {$dayOfWeek = 7; } // MALDITO DOMINGO!!!
		$sunday_offset = ($dayOfWeek - $dias_sumados) * 60 * 60 * 24;
	
		$dia_semana_fd = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
		if($dia_semana_fd != 1) {
			if($dia_semana_fd == 0) {
				$sunday_offset = (($dayOfWeek - $dias_sumados) * 60 * 60 * 24) - 3600;
			}
	
			if($dia_semana_fd == 2) {
				$sunday_offset = (($dayOfWeek - $dias_sumados) * 60 * 60 * 24) + 3600;
			}
		}	
	
		$fd = date("Y-m-d", mktime(0,0,0,$mes,$dia + 1,$ano) - $sunday_offset);
		return $fd;
	}

	function contar_semanas($f) {
		// Buscar una forma mas elegante de hacer esta función. Asi como está es MUY fea
		global $fecha_actual;
		$fecha = explode("-", $fecha_actual);
			$ano_D = abs($fecha[0]);
			$mes_D = abs($fecha[1]);
			$dia_D = abs($fecha[2]);
		
		$fecha = explode("-", semana_actual_inicio($f));
			$ano = abs($fecha[0]);
			$mes = abs($fecha[1]);
			$dia = abs($fecha[2]);
		
		$fecha_a = explode("-", semana_actual_inicio($fecha_actual));
			$ano_a = abs($fecha_a[0]);
			$mes_a = abs($fecha_a[1]);
			$dia_a = abs($fecha_a[2]);
	
		$dia_actual = date( mktime(0, 0, 0, $mes_a, $dia_a, $ano_a));
		$dia_inicial = date( mktime(0, 0, 0, $mes, $dia, $ano));
		$dayOfWeek = date("w", mktime(0, 0, 0, $mes_D, $dia_D, $ano_D));
	
		if($dayOfWeek == 0) $dayOfWeek == 7;
		
		$semanas = round(($dia_actual -$dia_inicial ) / (60 * 60 * 24*7));
		return $semanas;
	}

	function suma_fechas($fecha,$ndias) {
		if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha)) {
			list($dia,$mes,$ano)=split("/", $fecha);
		}
	
		if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha)) {
			list($dia,$mes,$ano)=split("-",$fecha);
		}
	
		$nueva = mktime(0,0,0, $mes,$dia,$ano) + $ndias * 24 * 60 * 60;
		$nuevafecha=date("d-m-Y",$nueva);
		return ($nuevafecha);
	} 



	function hora_ceros($hora) {
		$_hora = explode(":", $hora);
		$hora = $_hora[0];
		$minuto = $_hora[1];
		$segundo = $_hora[2];
	
		if(strlen($hora) == 1) { $hora = "0" . $hora; }
		if(strlen($minuto) == 1) { $minuto = "0" . $minuto; }
		if(strlen($segundo) == 1) { $segundo = "0" . $segundo; }
		$hora_nueva = $hora . ":" . $minuto . ":" . $segundo;
		return($hora_nueva);
	}



	function DiasEntre($endDate, $beginDate) {
	   //explode the date by "-" and storing to array
	   $date_parts1=explode("-", $beginDate);
	   $date_parts2=explode("-", $endDate);
	
	   //gregoriantojd() Converts a Gregorian date to Julian Day Count
	   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
	   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
	   return $end_date - $start_date;
	}

	function comparar_fechas($fi,$ff){
		//echo $fi."<br>";
		list($año1,$mes1,$dia1)=split("-",$fi);
		list($año2,$mes2,$dia2)=split("-",$ff);
		$dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
		
		if($dif>=0) $xdif=0; else $xdif=1;
			return ($xdif);    
	}

	function ordenar_fecha($a, $b) {
		 $a = strtotime($a);
		 $b = strtotime($b);
		 return strcmp($a, $b);
	}
?>