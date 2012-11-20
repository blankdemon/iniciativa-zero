<h1>Ingreso de Noticias</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1">
  <table>
    <tr>
      <td valign="top">Tipo de Noticia:</td>
      <td>
      	<input name="tipo_noticia" type="radio" value="1" checked> Noticia General<br>
        <input name="tipo_noticia" type="radio"  value="2"> Noticia Nuestra      </td>
    </tr>
    <tr>
      <td>Titulo:</td>
      <td><input name="titulo" type="text" style="width:445px"></td>
    </tr>
    <tr>
      <td valign="top">Noticia:</td>
      <td><textarea name="noticia" rows="8" style="width:450px"></textarea></td>
    </tr>
    <tr>
      <td>Imagen:</td>
      <td><input type="file" name="imagen" id="imagen"></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input type="submit" name="guardarNoticia" id="guardarNoticia" value="Guardar Noticia"></td>
    </tr>
  </table>
</form>
