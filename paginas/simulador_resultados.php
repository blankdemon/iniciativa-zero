<?php
	/* defino una constante de 20 ventas como maximo */
	define(__MAX_VTAS__, 20);
	
	/* totales residuales nivel 1 */
	$comision = 0.1;
	$cantidad_vtas_1 = ($_REQUEST[directo] != 2) ? ($_REQUEST[directo]-3) : 0;
	$total_vtas_1 = ($cantidad_vtas_1 > 0) ? ($cantidad_vtas_1 * $_REQUEST[producto] * $comision) : 0;
	$total_vres_1 = ($cantidad_vtas_1 > 0) ? ($total_vtas_1 - ($total_vtas_1/ 1.1) * 0.1) : 0;
	$total_vcc_1 = $_REQUEST[producto];
							
	/* totales residuales nivel 2 */
	$cantidad_vtas_2 = ($_REQUEST[grupo] != 2) ? ($_REQUEST[directo] * ($_REQUEST[grupo]-3)) : 0;
	$total_vtas_2 = ($cantidad_vtas_2 > 0) ? ($cantidad_vtas_2 * $_REQUEST[producto] * $comision) : 0;
	$total_vres_2 = ($cantidad_vtas_2 > 0) ? ($total_vtas_2 - ($total_vtas_2/ 1.1) * 0.1) : 0;			
							
	/* totales residuales nivel 3 */
	$cantidad_vtas_3 = ($_REQUEST[grupo] != 2) ? ($_REQUEST[directo] * ($_REQUEST[grupo]  * ($_REQUEST[grupo]-3))) : 0;
	$total_vtas_3 = ($cantidad_vtas_3 > 0) ? ($cantidad_vtas_3 * $_REQUEST[producto] * $comision) : 0;
	$total_vres_3 = ($cantidad_vtas_3 > 0) ? ($total_vtas_3 - ($total_vtas_3/ 1.1) * 0.1) : 0;			

	/* totales residuales nivel 4 */
	$cantidad_vtas_4 = ($_REQUEST[grupo] != 2) ? ($_REQUEST[directo] * ($_REQUEST[grupo]  * $_REQUEST[grupo] * ($_REQUEST[grupo]-3))) : 0;
	$total_vtas_4 = ($cantidad_vtas_4 > 0) ? ($cantidad_vtas_4 * $_REQUEST[producto] * $comision) : 0;
	$total_vres_4 = ($cantidad_vtas_4 > 0) ? ($total_vtas_4 - ($total_vtas_4/ 1.1) * 0.1): 0;
	
	/* totales residuales nivel 4 */
	$comision = 0.2;
	$cantidad_vtas_5 = ($_REQUEST[grupo] != 2) ? ($_REQUEST[directo] * ($_REQUEST[grupo] * $_REQUEST[grupo]  * $_REQUEST[grupo] * ($_REQUEST[grupo]-3))) : 0;
	$total_vtas_5 = ($cantidad_vtas_5 > 0) ? ($cantidad_vtas_5 * $_REQUEST[producto] * $comision) : 0;
	$total_vres_5 = ($cantidad_vtas_5 > 0) ? ($total_vtas_5 - ($total_vtas_5/ 1.1) * 0.1): 0;
		
	/* totales calificadas de comision nivel 2*/
	$cantidad_vtas_5 = ($_REQUEST[directo] * ($_REQUEST[grupo] * $_REQUEST[grupo]  * $_REQUEST[grupo]));
	$total_vtas_5 = ($cantidad_vtas_5 * $_REQUEST[producto]);
	$total_vcc_5 = ($total_vtas_5 - ($total_vtas_5/ 1.1) * 0.1);
?>
    <h1><span id="result_box4" lang="en" xml:lang="en">With the data entered your  commission would be:</span> € <?=number_format(($total_vcc_1 +$total_vcc_2 + $total_vcc_3 + $total_vcc_4 + $total_vcc_5  + $total_vres_1 + $total_vres_2 + $total_vres_3 + $total_vres_4 + $total_vres_5), "0", "", ".") ?>
      in 1 year.</h1>

<table border="1" cellspacing="0" cellpadding="0" width="100%" id="rsimulador">
   <tr>
	<td></td>
    <td colspan="5">MULTIPLE INCOMES FROM MULTIPLE LEVELS</td>
    <td></td>
   </tr>
   <tr>
	<td></td>
    <td>LEVEL O - Your direct referrals</td>
    <td>LEVEL 1</td>
    <td>LEVEL 2</td>
    <td>LEVEL 3</td>
    <td>LEVEL 4</td>
    <td>Total</td>
  </tr>  
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
    <td nowrap>100% commission from your 3th sale in LEVEL 0</td>
    <td align="right">€ <?=number_format($total_vcc_1, "0", "", ".") ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">€ <?='$ ' . number_format($total_vcc_1, "0", "", ".") ?></td> 
  </tr>        
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
	<td nowrap>10% commission of the remaining sales in  LEVEL 0 (4 to 
    <?=__MAX_VTAS__ ?>)</td>
    <td align="right">€ <?=number_format($total_vres_1, "0", "", ".") ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_1, "0", "", ".") ?></td>	
  </tr>
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
	<td nowrap>10% commission of the remaining sales in  LEVEL 1 (4 to 
    <?=__MAX_VTAS__ ?>)</td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_2, "0", "", ".") ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_2, "0", "", ".") ?></td>	
  </tr>
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
    <td nowrap>10% commission of the remaining sales in  LEVEL 2 (4 to 
    <?=__MAX_VTAS__ ?>)</td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_3, "0", "", ".") ?></td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_3, "0", "", ".") ?> </td>
  </tr> 
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
    <td nowrap>10%  commission of the remaining sales in LEVEL 3 (4 to 
    <?=__MAX_VTAS__ ?>    ) </td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_4, "0", "", ".") ?> </td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_4, "0", "", ".") ?> </td> 
  </tr>
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
    <td nowrap>100% commission of any 2nd sale in LEVEL 4. </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vcc_5, "0", "", ".") ?></td>
    <td align="right" nowrap="nowrap">€ <?=number_format($total_vcc_5, "0", "", ".") ?></td> 
  </tr>
  <tr onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
    <td nowrap>20%  of the remaining sales in LEVEL 4 (4 to 
    <?=__MAX_VTAS__ ?>    ) </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">€ <?=number_format($total_vres_5, "0", "", ".") ?></td>
    <td align="right">€ <?=number_format($total_vres_5, "0", "", ".") ?></td> 
  </tr>
  <tr>
    <td colspan="6" align="right"><b></b>Estimate commission for you:</td>
    <td nowrap><b>€ <?=number_format(($total_vcc_1 + $total_vcc_2 + $total_vcc_3 + $total_vcc_4 + $total_vcc_5 + $total_vres_1 + $total_vres_2 + $total_vres_3 + $total_vres_4 + $total_vres_5), "0", "", ".") ?></b></td>
  </tr>
</table>
    <br />

	<div id="condiciones"><b>MONTHLY COLLECTION OF COMMISSIONS FROM 1st to 7th OF EVERY MONTH</b><br />
    	* <span id="result_box2" lang="en" xml:lang="en">Simulation values ​​are already <span id="result_box3" lang="en" xml:lang="en">include</span>  10% discount credit card fees</span>.<br />
      *  Remaining sales: Sales  4 to 20 in any level.</div>
