<?php
	/* genero los radios de las ultimas dos filas */
	function mostrarCantidadCarritos($nombre_input, $maximo=20) {
		$value = 0;
		echo '<input size="2" style="text-align:center;" name="'.$nombre_input.'" value="';		
		if(eregi("^directo$", $nombre_input)) $value = (int) $_REQUEST[directo];
		if(eregi("^grupo$", $nombre_input)) $value = (int) $_REQUEST[grupo];		
		echo ($value > 0) ? $value : 5;
		echo '">';
	}
	
	function mostrarProductos() {
		$q = "SELECT nombre, precio FROM productos ORDER BY precio";
		$s = mysql_query($q);
		while($r = mysql_fetch_array($s)) {
			echo '<input name="producto" value="' . $r[precio] . '" type="radio"';
			if(mysql_num_rows($s)==1) echo ' checked';
			echo '> ' . $r[nombre] .  '<br />';
		}
	}
?>
<script language="javascript" type="text/javascript">
	function validarSimulador(f) {
		var error = '';
		var pchecked = false;
		if(f.producto.length) {
			for(var i=0;i<f.producto.length;i++) {
				if(f.producto[i].checked == true) {
					pchecked = true;
					var producto = f.producto[i].value;
				}
			}
		} else {
			if(f.producto.checked == true) {
				pchecked = true;
				var producto = f.producto.value;
			}
		}
		
		if(pchecked == false) error += "Tipo de cuenta no seleccionada\n";
		if(f.directo.value == '') error += "Cantidad de tus compras no ingresadas\n";
		if(f.directo.value > 20 || f.directo.value < 1) error += "Valor de tus compras fuera de rango, debe estar entre 1 y 20\n";
		if(f.grupo.value == '') error += "Numero de compras por grupo no ingresadas";
		if(f.grupo.value > 20 || f.grupo.value < 1) error += "Valor de compras fuera de rango, debe estar entre 1 y 20";
		if(error=='') {
			$.get(
				"paginas/simulador_resultados.php",
				{
					producto: producto,
					directo: f.directo.value,
					grupo: f.grupo.value
				},
				function(data) { 
					$.fancybox(data);
				},
				"html"
			);
		} else {
			alert("Han ocurrido errores:\n" + error);
		}
	}
</script>
<h1>Real Simulation</h1>
<div id="simulador">
    <form id="simulador" name="simulador" method="post" action="">
    <table id="simulador" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td>1.- <b>1 year premiunm membership</b>: With your <span id="result_box2" lang="en" xml:lang="en">3rd</span> sale you <span id="result_box" lang="en" xml:lang="en">recover your initial purchase of  15 &euro;. (Choose your currency)</span></td> 
          <td width="60%"><?php mostrarProductos(); ?></td>
        </tr>
        <tr>
          <td>2.- <b>&iquest;How many of yours direct<span id="result_box3" lang="en" xml:lang="en"> referrals</span>,  buy a &quot;1 year premium membership&quot;  (15 &euro;) - Level 0.</b></td>
          <td nowrap><?php mostrarCantidadCarritos('directo', 20) ?></td>
        </tr>
        <tr>
          <td>3.- <b>&iquest;How many <span id="result_box4" lang="en" xml:lang="en">referrals</span> in yours downlines will buy a &quot;1 year premium membership? - Levels 1 to 4.</b></td>
          <td><?php mostrarCantidadCarritos('grupo', 20) ?></td>
      </tr>
    </table>
    <div align="center"><input type="button" name="simular" onclick="validarSimulador(document.simulador);" value="Real Simulation" />
    </div>
</form>
