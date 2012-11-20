	var id_directores = [];
	var nombre_directores = [];
	var id_reparto = [];
	var nombre_reparto = [];
	var cantidad_imagenes = 0;
	var ngeneros = 0;
	var gcontent_original = '';
	
	/* administracion de peliculas para artistas: directores y reparto */
	function seleccionarArtista(f, action) {
		var ff = document.form1;
		if(action=='director') {
			if(f.id_artista.length > 1) {
				for(var i =0; i < f.id_artista.length; i++) {
					if(f.id_artista[i].checked) {
						var id_artista = f.id_artista[i].value.split('||');
						id_directores.push(id_artista[0]);
						nombre_directores.push(id_artista[1]);
					}
				}
			} else {
				if(f.id_artista.checked) {
					var id_artista = f.id_artista.value.split('||');
					id_directores.push(id_artista[0]);
					nombre_directores.push(id_artista[1]);
				}
			}
			
			var ndirectores = (ff.id_director.value!='') ? ff.id_director.value + ',' + id_directores.join(',') : id_directores.join(',');
			ff.id_director.value = ndirectores;
			
			var tdirectores = (ff.id_director.value!='') ? $('#director').text() + ", " + nombre_directores.join(", ") : nombre_directores.join(", ");
			$('#director').text(tdirectores);
		} else {
			if(f.id_artista.length > 1) {
				for(var i =0; i < f.id_artista.length; i++) {
					if(f.id_artista[i].checked) {
						var t = f.id_artista[i].value.split('||');
						id_reparto.push(t[0]);
						nombre_reparto.push(t[1]);
					}
				}
			} else {
				if(f.id_artista.checked) {
					var t = f.id_artista.value.split('||');
					id_reparto.push(t[0]);
					nombre_reparto.push(t[1]);
				}
			}
			
			if(ff.id_reparto.value!='') alert(1)
			
			var nreparto = (ff.id_reparto.value!='') ? ff.id_reparto.value + ',' + id_reparto.join(',') : id_reparto.join(',');
			ff.id_reparto.value = nreparto;
			
			var treparto = (ff.id_reparto.value!='') ? $('#reparto').text() + ", " + nombre_reparto.join(", ") : nombre_reparto.join(", ");
			$('#reparto').text(treparto);
		}
	}
	
	/* funciones de administracion de peliculas */
	function buscarArtista(f) {
		$.ajax({
			  data: {
				words: f.words.value,
				action: f.action.value
			  },
			  url: 'paginas/seleccionar_dir_artista_listado.php',
			  success: function(data) {
				  $('#resultados').html(data);
			  }
		});
	}
	
	/* despliega opciones para upload masivo de imagenes de peliculas */
	function subirImagenesPeliculas(f) {
		var peliculas = [];
		if(f.id_pelicula_upload_file.length>1) {
			for(var i =0;i<f.id_pelicula_upload_file.length;i++) {
				if(f.id_pelicula_upload_file[i].checked && !f.id_pelicula_upload_file[i].disabled) {
					var t = f.id_pelicula_upload_file[i].value.split('||');
					peliculas.push([t[0], t[1]]);
				}
			}
		} else {
			if(f.id_pelicula_upload_file.checked && !f.id_pelicula_upload_file.disabled) {
				var t = f.id_pelicula_upload_file.value.split('||');
				peliculas.push([t[0], t[1]]);
			}	
		}
		
		if(peliculas.length) {
			var fupload = '<form name="fuploadimg" action="" method="post" enctype="multipart/form-data">'
			+ '<h1>Subir Imagenes</h1>'
			+ '<table width="400">'
			+ '<tr>'
			+ '<td><b>Nombre<//b></td>'
			+ '<td><b>Imagen</b></td>'
			+ '</tr>';
			for(var i = 0; i < peliculas.length; i++) {
				fupload += '<tr>'
				+ '<td>' + peliculas[i][1] + '</td>'
				+ '<td>'
					+ '<input type="hidden" name="id_pelicula[' + i + ']" value="' + peliculas[i][0] + '">'
					+ '<input type="file" name="imagen[' + i + ']">'
					+ '</td>'
				+ '</tr>';
			}
			fupload += '<tr><td colspan="2" align="center"><input type="submit" name="subir" value="subir imagenes"></td></tr>';
			fupload += '</table>';
			fupload += '<input type="hidden" name="action" value="subir_imagenes">';
			fupload += '<input type="hidden" name="action" value="subir_imagenes">';
			fupload += '<input type="hidden" name="total_peliculas" value="' + i + '">';
			fupload += '</form>';
			
			$.fancybox({
				content: fupload   
			});
		} else {
			alert("No has seleccionado peliculas para subir imagenes");
		}
	}
	
	/* muestra confirmacion de eliminacion */
	function eliminarPelicula(id_pelicula) {
		if(confirm("Estas seguro(a) que deseas eliminar definitivamente esta pelicula?")) {
			window.location = '?id_pagina=24&id_pelicula=' + id_pelicula + '&action=eliminar_pelicula';
		}
	}
	
	function eliminarRepartoPelicula(id_pelicula, id_artista) {
		if(confirm("Estas seguro(a) que deseas eliminar este Artista de la Pelicula?")) {
			window.location = '?id_pagina=35&id_pelicula=' + id_pelicula + '&action=update&id_artista_eliminar=' + id_artista;
		}
	}
	
	function eliminarDirectorPelicula(id_pelicula, id_director) {
		if(confirm("Estas seguro(a) que deseas eliminar este Director de la Pelicula?")) {
			window.location = '?id_pagina=35&id_pelicula=' + id_pelicula + '&action=update&id_director_eliminar=' + id_director;
		}
	}
	
	
	/******************************************************************************************************/
	function agregarInputDeImagen() {
		cantidad_imagenes++;		
		var imagen = '<input type="file" name="imagen[' + cantidad_imagenes + ']"><br>';		
		var contenido = ($("#nuevas_imagenes").html() != null) ? imagen : imagen;
		$("#nuevas_imagenes").append(contenido);
		document.form1.cantidad_imagenes.value = cantidad_imagenes;
	}
	
	function agregarGenero() {
		ngeneros++;		
		if(gcontent_original=='') gcontent_original = $("#ngeneros").html();	
		var contenido = ($("#ngeneros").html() != null) ? gcontent_original : gcontent_original;
		$("#ngeneros").append(contenido);
		document.form1.tgeneros.value = ngeneros;
	}