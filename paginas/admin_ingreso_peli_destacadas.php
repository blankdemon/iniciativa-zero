<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();

	$q01 = "SELECT nombre, trama FROM cart_pelicula WHERE id_pelicula=" . (int) $_REQUEST[id_pelicula];	
	$s01 = mysql_query($q01);
	$r = mysql_fetch_array($s01);
?>
<form action="" method="post" enctype="multipart/form-data" name="form1">
	<input type="hidden" name="action" value="guardar_destacada" />
    <input type="hidden" name="id_pelicula" value="<?=$_REQUEST[id_pelicula] ?>" />
  <table width="200">
    <tr>
      <td>Nombre:</td>
      <td><?=$r[nombre] ?></td>
    </tr>
    <tr>
      <td valign="top">Descripción:</td>
      <td><textarea name="descripcion" id="textarea" cols="45" rows="5"></textarea></td>
    </tr>
    <tr>
      <td>Imagen: </td>
      <td><input type="file" name="imagen" id="fileField"></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input type="submit" name="g" value="guardar"></td>
    </tr>
  </table>
</form>
