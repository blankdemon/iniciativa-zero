<?php
function enlace($pagina, $tipo) {
	switch($tipo) {
		case 1:
			$root = "?pagina=";
			break;
		case 2:
			$root = "?panel=";
			break;
		case 3:
			$root = "?panel=admin/";
			break;
	}

	echo $root . $pagina . ".php";
}

function InStr($String, $Find, $CaseSensitive = false){
	$i=0;
	while (strlen($String)>=$i) {

		unset($substring);

		if ($CaseSensitive) {

			$Find=strtolower($Find);

			$String=strtolower($String);

		}

		$substring=substr($String,$i,strlen($Find));

		if ($substring==$Find) return true;

		$i++;

	}

	return false;

}



function DatoTabla($que, $donde, $quien, $cual) { // BUSCA DATOS EN LA BASE

	$query_dato = "SELECT $que AS dato FROM $donde WHERE $quien='$cual'";

	$result_dato = mysql_query($query_dato) or die(mysql_error());

	$linea_dato = mysql_fetch_array($result_dato);

	$p = $linea_dato["dato"];

//	print $query_dato;

	return $p;

}



// FUNCION Boton

// (Crea botones a partir de las variables entregadas)

// ===================================================

// $etiqueta = Texto que aparecerá en el boton

// $nombre = Nombre del botón e icono correspondiente ("img_" + $nombre)

// $acceso = Atajo de teclado que activa el botón

// $funcion = Función que se activará al hacer click 

// $desactivado = 0: Activo - 1: Desactivado

// $tooptip = Texto que se mostrará como tooltip

// $tipo = 1: boton de barra - 2: boton lateral izquierdo - 3: boton lateral derecho - 

//         4: boton de asistente - 5: boton de asistente alterno - 6: boton invertido

// $ancho = Ancho en pixeles



function Boton($etiqueta, $nombre, $acceso, $funcion, $desactivado, $tooltip, $tipo, $ancho)

{

	if ($tipo == 1) {

		$mouseover = "img_$nombre.src='iconos/" . $nombre . "si.gif';this.className='bot1';";

		$mousedown = "window.focus(); this.className='bot2';";

		$mouseout = "img_$nombre.src='iconos/" . $nombre . "no.gif';this.className='bot0';";

		$align="left"; $separa=""; $estilo="bot0"; $nesp = "no";

	}

	if ($tipo == 2) {

		$align="center"; $separa="</tr><tr>"; $estilo="botonlateral";

	}

	if ($tipo == 3) {

		$align="left";	$separa=""; $estilo="botonlateral";

	}

	if ($tipo == 4) {

		$align="center"; $separa="</tr><tr>"; $estilo="botonasiste"; $st = "_4";

	}

	if ($tipo == 5) {

		$align="center"; $separa="</tr><tr>"; $estilo="botonasiste2"; $st = "_5";

	}

	$icono = "22";
	$clas="rojoc";

	if ($ancho > 120) { $align="left"; $separa=""; $icono="28";}
	
if($tipo == 6){
	$align="center"; $separa="</tr><tr>"; $estilo="botonlateral";
	print "<input type=\"button\" class=\"rojomc\" onmouseover=\"this.className='verdemc'\" onmouseout=\"this.className='rojomc'\" value=\"$etiqueta\" onclick=\"$funcion;return false;\">";
}else{
	print "<input type=\"button\" class=\"rojoc\" onmouseover=\"this.className='verdec'\" onmouseout=\"this.className='rojoc'\" value=\"$etiqueta\" onclick=\"$funcion;return false;\">";
	}

}

function vigencia_ayuda($compra){

	global $fecha_actual;
	
	$sqldatos="select * from compras c,tipo_compra t, categorias cat where c.id_tipo_compra=t.id_tipo_compra and t.id_categoria=cat.id_categoria and id_compra='$compra'";
	//echo $sqldatos;
	//break;
	$resdatos=mysql_query($sqldatos) or die(mysql_error());
	$rowdatos=mysql_fetch_array($resdatos);
	$nivel=$rowdatos['id_nivel'];
	$vigencia=$rowdatos['vigencia'];
	
	//echo $nivel;
	
		 switch($nivel){
		 
				 case 1:{
				 
				 //esta consulta es para generación 1, pero como se cambio el periodo de tiempo en que se termina una compra, con esta otro consulta de generación 3 no toma valores hasta las casillas 10 para que no desaparezca el boton arbol.
				
				$sql="select * from 
					(select * from 
					(select * from 
					(select id_invitacion as invita2,id_persona as persona2,id_compra as compra1 from invitaciones where id_compra='$compra' and id_persona!=0 and estado not in (2,3,4)) as a
					left join  (select id_invitacion as inv1, id_compra as compra2,fecha_compra as fecha2 from compras ) as b on a.invita2=b.inv1 ) as c 
					left join (select id_invitacion as invita3,id_persona as persona3,orden as orden3,invitaciones.* from invitaciones where id_persona!=0  and estado not in (2,3,4)) as d on d.id_compra=c.compra2)as e 
					left join (select id_invitacion as inv, id_compra as compra3,fecha_compra as fecha3 from compras )as f on e.invita3=f.inv";
							
				//echo $sql;
				
						$result = mysql_query($sql) or die(mysql_error());
							while($row = mysql_fetch_array($result)){
							
											if($fi=='' and $row['fecha2']!=''){
												$fi=$row['fecha2'];
											}
											
											if($fi!='' and $row['fecha2']!=''){
											
													 if(comparar_fechas($row['fecha2'],$fi)==0){
														$fi=$row['fecha2'];
														
													 }
											}
							}
							//echo $fi."<br>";
				 
				 break;}
				 
				 case 2:{
				 $sql="select * from(
								select * from (
								select * from (
								select * from (
								select * from (
								select id_invitacion as invita2,id_persona as persona2,estado as estado2,id_compra as compra1 from invitaciones where id_compra='$compra' and id_persona!=0 and estado not in (2,3,4) order by orden asc ) as a 
								left join (select id_invitacion as inv1, id_compra as compra2,fecha_compra as fecha2 from compras ) as b on a.invita2=b.inv1 ) as c 
								left join (select id_invitacion as invita3,id_persona as persona3,estado as estado3,orden as orden3,id_compra as com1 from invitaciones where id_persona!=0 and estado not in (2,3,4) order by orden asc ) as d on d.com1=c.compra2)as e 
								left join (select id_invitacion as inv, id_compra as compra3,fecha_compra as fecha3 from compras )as f on e.invita3=f.inv)as g 
								left join (select id_invitacion as invita4,id_persona as persona4,estado as estado4,orden as orden4,id_compra as com2 from invitaciones where id_persona!=0 and estado not in (2,3,4))as h on g.compra3=h.com2) as i
								left join (select id_invitacion as inv3, id_compra as compra4,fecha_compra as fecha4 from compras )as j on i.invita4=j.inv3";
					
								$result = mysql_query($sql) or die(mysql_error());
							while($row = mysql_fetch_array($result)){
							
											if($fi=='' and $row['fecha2']!=''){
											$fi=$row['fecha2'];
											}
											
											if($fi!='' and $row['fecha2']!=''){
													 if(comparar_fechas($row['fecha2'],$fi)==0){
													 $fi=$row['fecha2'];
													 
													 }
											}
											
											if($fi!='' and $row['fecha3']!=''){
													 if(comparar_fechas($row['fecha3'],$fi)==0){
													 $fi=$row['fecha3'];
													 
													 }
											}
							}
				 
				 break;
				 }
				 
				 case 3:{
				 
						$sql="select * from(
								select * from(
								select * from(
								select * from (
								select * from (
								select * from (
								select * from (
								select id_invitacion as invita2,id_persona as persona2,estado as estado2,id_compra as compra1 from invitaciones where id_compra='$compra' and id_persona!=0 and estado not in (2,3,4) order by orden asc ) as a 
								left join (select id_invitacion as inv1, id_compra as compra2,fecha_compra as fecha2 from compras ) as b on a.invita2=b.inv1 ) as c 
								left join (select id_invitacion as invita3,id_persona as persona3,estado as estado3,orden as orden3,id_compra as com1 from invitaciones where id_persona!=0 and estado not in (2,3,4) order by orden asc ) as d on d.com1=c.compra2)as e 
								left join (select id_invitacion as inv, id_compra as compra3,fecha_compra as fecha3 from compras )as f on e.invita3=f.inv)as g 
								left join (select id_invitacion as invita4,id_persona as persona4,estado as estado4,orden as orden4,id_compra as com2 from invitaciones where id_persona!=0 and estado not in (2,3,4) order by orden asc )as h on g.compra3=h.com2) as i
								left join (select id_invitacion as inv3, id_compra as compra4,fecha_compra as fecha4 from compras )as j on i.invita4=j.inv3) as k
								left join (select id_invitacion as invita5,id_persona as persona5,estado as estado5,orden as orden5,id_compra as com3 from invitaciones where id_persona!=0 and estado not in (2,3,4))as l on k.compra4=l.com3) as m
								left join (select id_invitacion as inv4, id_compra as compra5,fecha_compra as fecha5 from compras )as n on m.invita5=n.inv4 ";
					
								$result = mysql_query($sql) or die(mysql_error());
							while($row = mysql_fetch_array($result)){
							
											if($fi=='' and $row['fecha2']!=''){
											$fi=$row['fecha2'];
											}
											
											if($fi!='' and $row['fecha2']!=''){
													 if(comparar_fechas($row['fecha2'],$fi)==0){
													 $fi=$row['fecha2'];
													 
													 }
											}
											
											if($fi!='' and $row['fecha3']!=''){
													 if(comparar_fechas($row['fecha3'],$fi)==0){
													 $fi=$row['fecha3'];
													 
													 }
											}
											
											if($fi!='' and $row['fecha4']!=''){
													 if(comparar_fechas($row['fecha4'],$fi)==0){
													 $fi=$row['fecha4'];
													 
													 }
											}
									}
						break;
						}
				}
		//echo $fi."<br>";	
		 for($i=1;$i<$vigencia;$i++){
		
		 $fi=semana_siguiente_inicio($fi);
		 
		 }
		//echo $fi."<br>";
		$final=comparar_fechas($fi,$fecha_actual);
		//echo $final;
		return $final;
}

function vigencia_compra($compra){

global $fecha_actual;
	$sqldatos="select * from compras c,tipo_compra t, categorias cat where c.id_tipo_compra=t.id_tipo_compra and t.id_categoria=cat.id_categoria and id_compra='$compra'";
	$resdatos=mysql_query($sqldatos) or die(mysql_error());
	$rowdatos=mysql_fetch_array($resdatos);
	$nivel=$rowdatos['id_nivel'];
	$vigencia=$rowdatos['vigencia'];
	$fecha_fatal=$rowdatos['fecha_compra'];
	for($i=0;$i<$vigencia;$i++){
			
		 $fecha_fatal=semana_siguiente_inicio($fecha_fatal);
			 
	}

	$final=comparar_fechas($fecha_fatal,$fecha_actual);
	//echo $final."<br>";
 if($final==1){

	return 0;
 
 }
 
	$sqldatosv="select count(*)  from invitaciones where id_compra='$compra' and id_persona!=0 and estado not in (2,3,4) and orden!=3 and estado=6 ";
 	
	$resdatosv=mysql_query($sqldatosv) or die(mysql_error());
	$rowdatosv=mysql_fetch_array($resdatosv);
	
if($rowdatosv[0]>=9){

	return 0;
	}
	return 1;
}

function nombre_persona($id_persona){

$sqlper="select * from personas where id_persona='$id_persona'";
$resper=mysql_query($sqlper) or die(mysql_error());
$rowdatosper=mysql_fetch_array($resper);
return $rowdatosper['nombres']." ".$rowdatosper['apellidos'];

}
// funcion para el archivo listado_invitar, busca en el arreglo un resultado y muestra verdadero, parametros recibidos 1) lo que se busca 2) el arreglo

function array_in_array($needles, $haystack) {

    foreach ($needles as $haystack) {

        if ( in_array($needle, $haystack) ) {
            return true;
        }
    }

    return false;
}
?>