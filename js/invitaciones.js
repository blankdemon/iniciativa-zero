// JavaScript Document
	var cantidad_inv_crear = 1;
	
	function setearNuevoCorrelativo() {
		var cpagadas = document.linvitaciones.cpagadas.value;
		var inputs = $('.numero').get();
		var lis = $("#tree li").get();
		
		// asigna los numeros de invitaciones por fila	
		for (var i=1;i<inputs.length;i++) 			
			inputs[i].innerHTML = i;			

		
		for (var i=0;i<lis.length;i++) {
			var lcontent = lis[i];
			var itfila = $(lcontent).find("input");
			
			if(i==4 && cpagadas < 2) {				
				for(var c=0;c<itfila.length;c++)
					itfila[c].disabled = true;
			} else {
				for(var c=0;c<itfila.length;c++)
					itfila[c].disabled = false;	
			}					
		}		
	}
	
	function eliminarInvitacionFromBd(i) {
		var f = document.accionInvitaciones;
		f.id_invitacion.value = i;
		f.action.value = 'eliminar';
		f.submit();
	}	
	
	function reenviarInvitacion(i) {
		if(confirm("¿Are you sure do you want forward this invitation?")) {
			var f = document.accionInvitaciones;
			f.id_invitacion.value = i;
			f.action.value = 'reenviar';
			f.submit();
		}
	}
	
	function pagarInvitacion(i) {
		
		
		//var pagina = "paginas/pagina_pago.php?id_invitacion=" + i;
		//window.open(pagina, 'pagarInvitacion', "width=600,height=300,left=" + (screen.width/2-300) + ",top=" + (screen.height/2-150));
	}
	
	function eliminarInvitacion(i) {
		var i = (i-1);	
		var anchor = document.getElementById ("li_inv_" + i);
				
		anchor.parentNode.removeChild (anchor);
		
		setearNuevoCorrelativo();
	}	
	
	function agregarInvitacion(obj, fecha, numinv) {
		cantidad_inv_crear++;
		var total_invitaciones = (cantidad_inv_crear + numinv);
		
		/* cambio el contenido de el enlace y el id para poner eliminar*/
		obj.id = 'eliminar';
		obj.title = 'Delete';
		obj.onclick = function() { 
			eliminarInvitacion(total_invitaciones);
		};
		
		/* contenido de la fila invitacion */		
		var contenido_fila = '<a id="agregar" href="javascript:;" onclick="agregarInvitacion(this, \'' + fecha + '\', ' + numinv + ');" title="Add more invitations."></a>';
		contenido_fila += '<div>';
		contenido_fila += '<span class="numero">' + total_invitaciones + '</span>';
		contenido_fila += '<span class="fecha">' + fecha + '</span>';
		contenido_fila += '<span class="nombres"><input type="text" class="inv_nombre" name="nombres_invitado_' + cantidad_inv_crear + '" value=""></span>';
		contenido_fila += '<span class="apellidos"><input type="text" class="inv_apellido" name="apellidos_invitado_' + cantidad_inv_crear + '" value=""></span>';
		contenido_fila += '<span class="correo"><input type="text" class="inv_correo" name="email_invitado_' + cantidad_inv_crear + '" value=""></span>';
		contenido_fila += '</div>';
		
		var nfila = document.createElement('li');
			nfila.setAttribute('id', 'li_inv_' + total_invitaciones);			
			nfila.innerHTML = contenido_fila;
		
		$("#invitados").append(nfila);
		
		setearNuevoCorrelativo();
		
		document.linvitaciones.num_invitaciones.value = cantidad_inv_crear+1;
	}
	
	function rechazarInvitacion(i) {
		if(confirm("¿Are you sure do you want reject this invitation?")) {
			var f = document.accionInvitaciones;
			f.id_invitacion.value = i;
			f.action.value = 'rechazar';
			f.submit();
		}
	}
	
	function aceptarInvitacion(i) {
		window.location = '?id_pagina=19&id_invitacion=' + i;
	}
