	$(document).ready(function() { 
		// cargando contenido con ajax
		$(".pinvitaciones").fancybox({ ajax: { type: "POST" } });
		$(".cproducto").fancybox({ ajax: { type: "POST" } });
		$(".dproducto").fancybox({ ajax: { type: "POST" } });
		$(".destacar").fancybox({ ajax: { type: "POST" } });
		$("#condiciones").fancybox({ ajax: { type: "POST" } });
		$("#ing-new").fancybox({ ajax: { type: "POST" } });		
		$("#retrieve-password").fancybox({ 'titleShow': false,'autoScale': false,'scrolling': 'no','autoDimensions': false,'width': 500,'height': 160});
		
		$("#dlink").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'autoScale'     	: false,
			'type'			: 'iframe',
			'width'			: 500,
			'height'		: 200,
			'scrolling'   		: 'no'
		});
		
		$("#admRussia").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'autoScale'     	: false,
			'type'			: 'iframe',
			'width'			: 780,
			'height'		: 400,
			'scrolling'   		: 'yes'
		});

		$("a[rel=example_group]").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over',
			'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
			    return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
			}
		});

		$(".selDirector").fancybox({
			content: '<h1>Seleccionar Director(es)</h1>' +
			'<form name="formartista" method="get" action="" onsubmit="buscarArtista(document.formartista); return false;">' +
				'<input type="hidden" name="action" value="director">' +
				'<input type="text" style="width:350px;" name="words">' +
				'<input type="button" onClick="buscarArtista(document.formartista)" name="buscar" value="buscar">' +
			'</form><br />' +
			'<div id="resultados">para seleccionar un artista debes buscarlo</div>'
		});

		$(".selReparto").fancybox({
			content: '<h1>Seleccionar Reparto</h1>' +
			'<form name="formartista" method="get" action="" onsubmit="buscarArtista(document.formartista); return false;">' +
				'<input type="hidden" name="action" value="reparto">' +
				'<input type="text" style="width:350px;" name="words">' +
				'<input type="button" onClick="buscarArtista(document.formartista)" name="buscar" value="buscar">' +
			'</form><br />' +
			'<div id="resultados">para seleccionar un artista debes buscarlo</div>'
		});
	});
		
	// despliegue de arboles de comisiones
	$(function() {
		$("#tree").treeview({
			collapsed: true,
			animated: "medium",
			control:"#sidetreecontrol",
			persist: "location"
		});
	});
	
	// recuperar contraseñas
	function retrievePassword(f) {
		var cargaComparacion = $.ajax({
			type	: "POST",
			cache	: false,
			url	: "paginas/recoverPasswordJson.php",
			data	: { email: f.email.value },
			dataType: 'json',
			beforeSend: function(){
			 	jQuery.fancybox.showActivity();
	  		},
			success: function(data) {
				jQuery.fancybox.hideActivity();
				if(data!=null) {
					if(data.rechazado!=null && data.rechazado!="") jQuery.fancybox({ content: '<h1>Warning!</h1><div id="rechazado-nb">' + data.rechazado + '</div><div style="text-align: center;"><input type="button" value="Close" onclick="jQuery.fancybox.close();"></div>' });
					if(data.msg!=null && data.msg!="") jQuery.fancybox({ content: '<h1>Information...</h1><div id="aceptado-nb">' + data.msg + '</div><div style="text-align: center;"><input type="button" value="OK" onclick="jQuery.fancybox.close();"></div>' });
				}			
			},
			error: function () {
				jQuery.fancybox({ content: '<h1>Warning!</h1><div id="rechazado-nb">An error was ocurred. The operation was not completed.</div><div style="text-align: center;"><input type="button" value="Close" onclick="jQuery.fancybox.close();"></div>' });
				jQuery.fancybox.hideActivity();
			}
		});	

		$(".blue-with-image-2").click(function() {				
			$.loader('close');
			cargaComparacion.abort();				
		});
	}

	function displayMessageOfContentLoggedRestricted() {
		jQuery.fancybox({
			content: '<h1 style="color:#ff0000;">Warning!</h1><div id="rechazado-nb">Content restricted for only users that have theirs Invitations paid or have enabled Distribution Groups</div>'
		});
	}

	function displayMessageOfContentNotLoggedRestricted() {
		jQuery.fancybox({
			content: '<h1 style="color:#ff0000;">Content restricted!</h1><div id="rechazado-nb">Sorry, this content is only available for registered users.</div>'
		});
	}





	/******************************************************************/
	/* funciones de registro de usuarios y aceptacion de invitaciones */
	
	/* store form values */
	var nombre = '';
	var apellidos = '';
	var diafecha_nac = '';
	var mesfecha_nac = '';
	var yearfecha_nac = '';
	var id_pais = '';

	/* open new window that display terms of use */
	function displayTermsAndContitions() {
		window.open("paginas/condicionesGenerales.php", "Terms and Conditions", "width=620, height=500, scrollbars=yes, left=" + (screen.width/2-310) + ", top=" + (screen.height/2-250));
	}
	
	/* obtener parametro desde url */
	function gup(name){
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp ( regexS );
		var tmpURL = window.location.href;
		var results = regex.exec( tmpURL );
		if( results == null )
			return"";
		else
			return results[1];
	}

	/* display the register form for users that has been invited */
	function displayRegisterForm(a) {
		var v = '';
		var m = gup('emregister');

		if(m!='') v += 'emregister=' + m;
		if(a!=undefined&&a!='') {
			if(v!='') v += '&';
			v += 'vars=' + a;
		}

		var register = $.ajax({
			type	: "POST",
			cache	: false,
			url	: "paginas/invitacionRegistroFormulario.php?" + v,
			beforeSend: function(){
			 	jQuery.fancybox.showActivity();
	  		},
			success: function(html) {
				jQuery.fancybox.hideActivity();
				if(html!=null) {
					jQuery.fancybox({
						'content': html,
						'titleShow': false,
						'autoScale': false,
						'scrolling': 'no',
						'showCloseButton': false,
						'enableEscapeButton': false,
						'autoDimensions': true,
						onClosed: function () {
							window.location='?id_pagina=1';
						},
						onComplete: function () {
							rescueFormRegisterValuesFromVars(document.fregister);
						}
					});
				}			
			},
			error: function () {
				jQuery.fancybox({ content: '<h1>Warning!</h1><div id="rechazado-nb">An error was ocurred. The operation was not completed.</div><div style="text-align: center;"><input type="button" value="Close" onclick="window.location=\'?id_pagina=1\'"></div>' });
				jQuery.fancybox.hideActivity();
			}

		});	

		$(".blue-with-image-2").click(function() {				
			$.loader('close');
			register.abort();				
		});
	}
	
	/* store form register values in temporal variables */
	function rescueFormRegisterValuesToVars(f) {
		nombre = f.nombre.value;
		apellidos = f.apellidos.value;
		diafecha_nac = f.diafecha_nac.value;
		mesfecha_nac = f.mesfecha_nac.value;
		yearfecha_nac = f.yearfecha_nac.value;
		id_pais = f.id_pais.value;
	}

	/* rescue from store values an set in the form */
	function rescueFormRegisterValuesFromVars(f) {
		f.nombre.value = nombre;
		f.apellidos.value = apellidos;
		f.diafecha_nac.value = diafecha_nac;
		f.mesfecha_nac.value = mesfecha_nac;
		f.yearfecha_nac.value = yearfecha_nac;
		f.id_pais.value = id_pais;
	}
	
	/* do the register showing the information resultant */
	function registerUserAcceptInvitation(f) {
		var error = '';
		
		/* rescue inputs values to temporal vars */
		rescueFormRegisterValuesToVars(f);		

		if(f.conditions && !f.conditions.checked) error += '<li>Conditions not accepted.';
		if(f.nombre.value=='') error += '<li>Name empty.';
		if(f.apellidos.value=='') error += '<li>Last name empty.';
		if(f.diafecha_nac.value=='') error += '<li>Birthday not selected.';
		if(f.mesfecha_nac.value=='') error += '<li>Birthmonth not selected.';	
		if(f.yearfecha_nac.value=='') error += '<li>Bithyear empty.';
		if(f.id_pais.value=='') error += '<li>Country not selected.';
		if(f.email.value=='') error += '<li>E-mail account empty.';
		if(f.email.value != f.email2.value) error += '<li>E-mail accounts do not match.';
		if(f.password.value=='') error += '<li>Password empty.';

		if(error!='') {
			error  = "Errors was ocurred while permorming the operation. Please check the information...<br><br>" + error;
			jQuery.fancybox({
				'content': '<div id="rechazado-nb"><h1>Warning!</h1><div>' + error + '</div><br><div style="text-align: center;"><input type="button" value="back" onclick="displayRegisterForm();"></div></div>',
				'titleShow': false,
				'autoScale': false,
				'scrolling': 'no',
				'autoDimensions': true
			});
		} else {
			var fecha_nacimiento = yearfecha_nac + '-' + mesfecha_nac + '-' + diafecha_nac;
			var register = $.ajax({
				type	: "POST",
				cache	: false,
				url	: "paginas/invitacionRegistroJson.php",
				dataType: 'json',
				data	: {
					nombre		: f.nombre.value,
					apellidos	: f.apellidos.value,
					fecha_nac	: fecha_nacimiento,
					id_pais		: f.id_pais.value,
					email		: f.email.value,
					password	: f.password.value,
					action		: 'register'
				},
				beforeSend: function(){
				 	jQuery.fancybox.showActivity();
		  		},
				success: function(data) {
					jQuery.fancybox.hideActivity();
					if(data!=null) {
						if(data.error!=null && data.error!="") $("#rechazado-nb").html(data.error);

						if(data.msga!=undefined&&data.msga!='') {
							var html = '<div id="aceptado-nb"><h1>Information</h1>' + data.msga + '</div><div style="text-align: center;"><input type="button" value="Close" onclick="window.location=\'?id_pagina=41\'"></div>';					
							jQuery.fancybox({
								'content': html,
								'titleShow': false,
								'autoScale': false,
								'scrolling': 'no',
								'autoDimensions': false,
								'width': 400,
								'height': 160,
								'onClosed': function () {window.location='?id_pagina=41';}
							});
						} else if(data.msg!=null && data.msg!='') {
							var html = '<div id="aceptado-nb"><h1>Information</h1>' + data.msg + '</div><div style="text-align: center;"><input type="button" value="sign up" onclick="window.location=\'?id_pagina=1\'"></div>';					
							jQuery.fancybox({
								'content': html,
								'titleShow': false,
								'autoScale': false,
								'scrolling': 'no',
								'autoDimensions': false,
								'width': 400,
								'height': 160
							});								
						}
					}			
				},
				error: function () {
					jQuery.fancybox({ content: '<h1>Warning!</h1><div id="rechazado-nb">An error was ocurred. The operation was not completed, please try in a few minutes.</div><div style="text-align: center;"><input type="button" value="back" onclick="displayRegisterForm();"></div>' });
					jQuery.fancybox.hideActivity();
				}

			});	

			$(".blue-with-image-2").click(function() {
				$.loader('close');
				register.abort();
			});
		}
	}
