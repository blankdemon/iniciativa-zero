<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();
?>
<div id="fpagos">
	<h1>Pay options</h1>
	<div>
        <span id="image">
<?php
			$q01 = "SELECT 
				CONCAT(i.nombre , ' ' , i.apellido) nombre,
				i.id_invitacion id_compra, i.estado
			FROM invitaciones i
			WHERE i.id_invitacion=" . (int) $_REQUEST[id_invitacion];	
			$s01 = mysql_query($q01);
			$r = mysql_fetch_array($s01);
			$nombre = $r[nombre];
			
			$q02 = "SELECT nombre, precio FROM productos WHERE id_producto = " . (int) $_REQUEST[id_producto];
			$s02 = mysql_query($q02) or die(mysql_error());
			$rr = mysql_fetch_array($s02);
			$nproducto = $rr[nombre];
			$precio = $rr[precio];
			// $precio
			$_SESSION[id_invitacion_pagar] = (int) $_REQUEST[id_invitacion];
			
			/*
				<input type="hidden" name="ap_purchasetype" value="item-goods"/>  
				<input type="hidden" name="ap_merchant" value="erich@chile.com"/>  
				<input type="hidden" name="ap_itemname" value="<?=$nproducto ?>"/>  
				<input type="hidden" name="ap_currency" value="USD"/>  
				<input type="hidden" name="ap_returnurl" value="http://www.wiki-global.com/?id_pagina=17&id_invitacion=<?=(int) $_REQUEST[id_invitacion] ?>&id_producto=<?=$_REQUEST[id_producto] ?>&aceptado=true"/>  
				<input type="hidden" name="ap_quantity" value="1"/> 
				<input type="hidden" name="ap_amount" value="1"/>  
				<input type="hidden" name="ap_cancelurl" value="http://www.wiki-global.com/?id_pagina=12&id_invitacion=<?=(int) $_REQUEST[id_invitacion] ?>&rechazado=true"/>  
				<input type="image" name="ap_image" src="https://www.alertpay.com//PayNow/2BEFC7FEE1D04AE5BB46E04787630682a.gif"/>  
				<input type="hidden" name="apc_1" value="<?=$_SESSION[id_cuenta] ?>"/>  
				<input type="hidden" name="apc_2" value="<?=$_SESSION[usuario] ?>"/>  
				<input type="hidden" name="apc_3" value="<?=date("d/m/Y") ?>, <?=date("H:i:s") ?>"/>  
				<input type="hidden" name="apc_4" value="<?=$nombre ?>"/>
			*/
?> 
			<a href="?id_pagina=17&id_invitacion=<?=(int) $_REQUEST[id_invitacion] ?>&id_producto=<?=$_REQUEST[id_producto] ?>&aceptado=true"><b>Click para Generar compra</b></a>
			

			<form method="post" action="https://www.alertpay.com/checkout" >
				<input type="hidden" name="ap_alerturl" value="http://www.wiki-global.com/?id_pagina=17"/>
				<input type="hidden" name="ap_productid" value="vbkRfmBs16tZD+GiTU2+tg=="/>
				<input type="hidden" name="ap_quantity" value="1"/>
				<input type="image" name="ap_image" src="https://www.alertpay.com/PayNow/2BEFC7FEE1D04AE5BB46E04787630682b0en.gif"/>
			</form>
        </span>
    </div>
</div>
