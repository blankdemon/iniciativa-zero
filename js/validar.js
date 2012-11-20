
function Validar(que) {



	if (que == "forma_contacto") {

		if (ValidaEscrito(document.forma_contacto.nombre, "su nombre") == false) { return false; }

		if (ValidaEscrito(document.forma_contacto.email, "una casilla de email") == false) { return false; }

		if (ValidaMail(document.forma_contacto.email) == false) { return false; }

		if (ValidaEscrito(document.forma_contacto.mensaje, "una consulta") == false) { return false; }

	}



	if (que == "forma_contacto_interno") {

		if (ValidaEscrito(document.forma_contacto.mensaje, "una consulta") == false) { return false; }

	}


	if (que == "password") {

		if (ValidaEscrito(document.inscripcion.password_original, "su clave de acceso actual") == false) { return false; }

		if (ValidaLongitud(document.inscripcion.password, 6) == false) { return false; }

		if (ValidaEscrito(document.inscripcion.password, "una clave de acceso") == false) { return false; }

		if (ValidaCaracteres(document.inscripcion.password, "su clave de inscripcion") == false) { return false; }

		if (ValidaEscrito(document.inscripcion.password_repite, "de nuevo la misma clave") == false) { return false; }

		if (ValidaIguales(document.inscripcion.password, document.inscripcion.password_repite, "Las claves de acceso ") == false) { return false; }

	}



	if (que == "banco") { 



		if (document.inscripcion.tipopago[0].checked == false && document.inscripcion.tipopago[1].checked == false && document.inscripcion.tipopago[2].checked == false) {

			alert("Debe seleccionar una forma en la cual se pagaran tus honorarios!");

			return false;

		}



		if (document.inscripcion.tipopago[1].checked) {

			if (ValidaEscrito(document.inscripcion.transBCI, "su numero de cuenta corriente BCI") == false) { return false; }			

			if (ValidaCaracteres(document.inscripcion.transBCI, "su numero de cuenta corriente") == false) { return false; }

		}



		if (document.inscripcion.tipopago[2].checked) {

			if (ValidaSeleccionado("otro", "un banco") == false) { return false; }

			if (ValidaEscrito(document.inscripcion.transotro, "su numero de cuenta corriente") == false) { return false; }			

			if (ValidaCaracteres(document.inscripcion.transotro, "su numero de cuenta corriente") == false) { return false; }

		}



	}



	if (que == "cuenta") { 



		if (ValidaSeleccionado("pregunta", "una pregunta de seguridad") == false) { return false; }

		if (ValidaEscrito(document.inscripcion.respuesta, "una respuesta secreta") == false) { return false; }

		if (ValidaEscrito(document.inscripcion.email1, "una casilla de email") == false) { return false; }

		if (ValidaMail(document.inscripcion.email1) == false) { return false; }

		if (ValidaMail(document.inscripcion.email2) == false) { return false; }

		if (ValidaCaracteresMail(document.inscripcion.email1) == false) { return false; }

		if (ValidaCaracteresMail(document.inscripcion.email2) == false) { return false; }



	}





	if (que == "usuario") { 

		if (ValidaEscrito(document.olvido.email1, "una casilla de email") == false) { return false; }

		if (ValidaMail(document.olvido.email1) == false) { return false; }

		if (ValidaCaracteresMail(document.olvido.email1) == false) { return false; }

		if (ValidaSeleccionado("pregunta_usuario", "una pregunta de seguridad") == false) { return false; }

		if (ValidaEscrito(document.olvido.respuesta_usuario, "una respuesta secreta") == false) { return false; }

	}





	if (que == "clave") { 

		if (ValidaEscrito(document.olvido.usuario, "un nombre de usuario") == false) { return false; }

		if (ValidaSeleccionado("pregunta_clave", "una pregunta de seguridad") == false) { return false; }

		if (ValidaEscrito(document.olvido.respuesta_clave, "una respuesta secreta") == false) { return false; }

	}







	if (que == "inscripcion" && typeof(document.inscripcion.confirmacion) == "undefined"){ 
		if (ValidaEscrito(document.inscripcion.usuario, "un nombre de usuario") == false) { return false; }
		if (ValidaLongitud(document.inscripcion.password, 6) == false) { return false; }
		if (ValidaEscrito(document.inscripcion.password, "una clave de acceso") == false) { return false; }
		if (ValidaCaracteres(document.inscripcion.password, "su clave de inscripcion") == false) { return false; }
		if (ValidaEscrito(document.inscripcion.password_repite, "de nuevo la misma clave") == false) { return false; }
		if (ValidaIguales(document.inscripcion.password, document.inscripcion.password_repite, "Las claves de acceso ") == false) { return false; }

		//if (ValidaSeleccionado("pregunta", "una pregunta de seguridad") == false) { return false; }
		//if (ValidaEscrito(document.inscripcion.respuesta, "una respuesta secreta") == false) { return false; }
		if (ValidaEscrito(document.inscripcion.email1, "una casilla de email") == false) { return false; }
		if (ValidaMail(document.inscripcion.email1) == false) { return false; }
		if (ValidaCaracteresMail(document.inscripcion.email1) == false) { return false; }
		//if (ValidaCaracteresMail(document.inscripcion.email2) == false) { return false; }

		//if (ValidaEscrito(document.inscripcion.nombres, "su nombre o nombres") == false) { return false; }
		//if (ValidaEscrito(document.inscripcion.apellidos, "sus apellidos") == false) { return false; }
		//if (ValidaSeleccionado("sexo", "sexo") == false) { return false; }
		//if (ValidaRUT() == false) { return false; }
		//if (ValidaFecha(document.inscripcion.fec_nacim, 1) == false) { return false; }

		//if (ValidaEscrito(document.inscripcion.direccion, "su direccion") == false) { return false; }
		//if (ValidaNumeros(document.inscripcion.codigo_postal, "El código postal") == false) { return false; }
		//if (ValidaSeleccionado("id_comuna", "una comuna") == false) { return false; }
		//if (ValidaEscrito(document.inscripcion.ciudad, "una ciudad") == false) { return false; }
		//if (ValidaSeleccionado("id_pais", "un país") == false) { return false; }

		//if (ValidaEscrito(document.inscripcion.telefono, "un numero de telefono fijo") == false) { return false; }
		//if (ValidaNumeros(document.inscripcion.telefono, "El numero de telefono") == false) { return false; }
		//if (ValidaEscrito(document.inscripcion.celular, "un numero de celular") == false) { return false; }
		//if (ValidaNumeros(document.inscripcion.celular, "El numero de celular") == false) { return false; }
		
		if (document.inscripcion.acepto[1].checked == true) {
			if(confirm('¿Confirmar anulación de la inscripción?')) { 				
				alert("Debe aceptar los términos y condiciones de uso para inscribirse!");
			}else{ 
				return false;
			}
		}else{
				if (document.inscripcion.acepto[0].checked == false) {
					alert("Debe aceptar los términos y condiciones de uso para inscribirse!");
					return false;
				}
			}	
	}


	if (que == "distribuidor") { 



		if (ValidaEscrito(document.inscripcion.direccion, "su direccion") == false) { return false; }

		if (ValidaNumeros(document.inscripcion.codigo_postal, "El código postal") == false) { return false; }

		if (ValidaSeleccionado("id_comuna", "una comuna") == false) { return false; }

		if (ValidaEscrito(document.inscripcion.ciudad, "una ciudad") == false) { return false; }

		if (ValidaSeleccionado("id_pais", "un país") == false) { return false; }



		if (ValidaEscrito(document.inscripcion.telefono, "un numero de telefono fijo") == false) { return false; }

		if (ValidaNumeros(document.inscripcion.telefono, "El numero de telefono") == false) { return false; }

		if (ValidaEscrito(document.inscripcion.celular, "un numero de celular") == false) { return false; }

		if (ValidaNumeros(document.inscripcion.celular, "El numero de celular") == false) { return false; }



	}





	if (que == "invitacion") { 

		if (document.forma_capto243._id_invitacion.value == 0) {

			alert("Debe seleccionar una invitacion para aceptar!");

			return false;

		}

	}

	if (que == "libro") { 

		if (document.forma_capto243.libro[0].checked == false && document.forma_capto243.libro[1].checked == false && document.forma_capto243.libro[2].checked == false) {

			alert("Debe seleccionar una categoría!");

			return false;

		}

	}

	if (que == "capitulo") { 

		if (document.forma_capto243.codigo[0].checked == false && document.forma_capto243.codigo[1].checked == false && document.forma_capto243.codigo[2].checked == false && document.forma_capto243.codigo[3].checked == false) {

			alert("Debe seleccionar un producto!");

			return false;

		}

	}	

	if (que == "categoria") { 

		if (document.forma_capto243._categoria.value == "") {

			alert("Debe seleccionar una categoría!");

			return false;

		}

	}

	if (que == "rubro") { 

		if (document.forma_capto243._categoria.value == "") {

			alert("Debe seleccionar un rubro!");

			return false;

		}

	}


	if (que == "producto") { 

		if (document.forma_capto243._codigo.value == "") {

			alert("Debe seleccionar un producto!");

			return false;

		}

	}	

	if (que == "forma_pago") { 



		if (document.forma_capto243.forma_pago[0].checked == false && document.forma_capto243.forma_pago[1].checked == false) {

			alert("Debe seleccionar una forma de pago!");

			return false;

		}



		if (document.forma_capto243.forma_pago[0].checked == true && document.forma_capto243.id_banco.value == 0) {

			alert("Debe seleccionar un banco desde el que realizará la transferencia!");

			return false;

		}



		if (document.forma_capto243.forma_pago[1].checked == true && document.forma_capto243.id_banco_boleta.value == 0) {

			alert("Debe seleccionar un banco en el que realizará el depósito!");

			return false;

		}

	}

	if (que == "forma_nivel") { 

	
		if (document.forma_capto243.nivel[0].checked == false && document.forma_capto243.nivel[1].checked == false&& document.forma_capto243.nivel[2].checked == false) {

			alert("Debe seleccionar un Nivel!");

			return false;

		}

	}


	if (que == "login") { 

		if (ValidaEscrito(document.login_capto243.usuario, "un nombre de usuario") == false) { return false; }

		if (ValidaEscrito(document.login_capto243.password, "una clave de acceso") == false) { return false; }

	}



	if (que == "invitar") {
	
		

		if(document.forma_capto243.quitar.value == 0) {
		
			/*
			
			var inv=document.forma_capto243.total_invitaciones.value;
			
			if(inv !='3'){		
			
				for(i=1;i<=inv;i++){
					
					
					
					var v=forma_capto243.enviada_[inv].value;
					
					alert(v);
				
					if(document.forma_capto243.enviada_[i].value != 1) {
					
						if (ValidaEscrito(document.forma_capto243.email_invitado_[i], "una casilla de correo para tu invitado") == false) { return false; }

						if (ValidaMail(document.forma_capto243.email_invitado_[i]) == false) { return false; }

						if (ValidaCaracteresMail(document.forma_capto243.email_invitado_[i]) == false) { return false; }
					
					}
				
				}
				
			}else{
			
					alert("U2"); 
			
				}
			
			
			//alert(inv); 
			*/
			
			
			
			if(document.forma_capto243.enviada_1.value != 1) {

				if (ValidaEscrito(document.forma_capto243.email_invitado_1, "una casilla de correo para tu primer invitado") == false) { return false; }

				if (ValidaMail(document.forma_capto243.email_invitado_1) == false) { return false; }

				if (ValidaCaracteresMail(document.forma_capto243.email_invitado_1) == false) { return false; }

			}

			
			if (document.forma_capto243.total_invitaciones.value > 1){

				if(document.forma_capto243.enviada_2.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_2, "una casilla de correo para tu segundo invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_2) == false) { return false; }

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_2) == false) { return false; }

				}
			}
			
			//if (document.forma_capto243.total_invitaciones.value > 2 && document.forma_capto243.reventa.value >= 2) {
		
			//if (document.forma_capto243.total_invitaciones.value > 2 && document.forma_capto243.paso_reven.value == 0) {
			
			//alert(document.forma_capto243.email_invitado_3.disabled);	
			
			if (document.forma_capto243.total_invitaciones.value > 2 && document.forma_capto243.paso_reven.value == '') {
					
				//if(document.forma_capto243.enviada_3.value != 1) {
				
				if(document.forma_capto243.email_invitado_3.disabled == false) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_3, "una casilla de correo para tu tercer invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_3) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_3) == false) { return false; }

				}
			}
			
			
			
			
			if (document.forma_capto243.total_invitaciones.value > 3) {
			
			
				if(document.forma_capto243.enviada_4.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_4, "una casilla de correo para tu cuarto invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_4) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_4) == false) { return false; }

				}

			}

			if (document.forma_capto243.total_invitaciones.value > 4) {

				if(document.forma_capto243.enviada_5.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_5, "una casilla de correo para tu quinto invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_5) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_5) == false) { return false; }

				}

			}
			
			if (document.forma_capto243.total_invitaciones.value > 5) {

				if(document.forma_capto243.enviada_6.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_6, "una casilla de correo para tu sexto invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_6) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_6) == false) { return false; }

				}

			}
			
			if (document.forma_capto243.total_invitaciones.value > 6) {

				if(document.forma_capto243.enviada_7.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_7, "una casilla de correo para tu septima invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_7) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_7) == false) { return false; }

				}

			}
			
			if (document.forma_capto243.total_invitaciones.value > 7) {

				if(document.forma_capto243.enviada_8.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_8, "una casilla de correo para tu octava invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_8) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_8) == false) { return false; }

				}

			}
			if (document.forma_capto243.total_invitaciones.value > 8) {

				if(document.forma_capto243.enviada_9.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_9, "una casilla de correo para tu novena invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_9) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_9) == false) { return false; }

				}

			}
			
			if (document.forma_capto243.total_invitaciones.value > 9) {

				if(document.forma_capto243.enviada_10.value != 1) {

					if (ValidaEscrito(document.forma_capto243.email_invitado_10, "una casilla de correo para tu decima invitado") == false) { return false; }

					if (ValidaMail(document.forma_capto243.email_invitado_10) == false) { return false; }					

					if (ValidaCaracteresMail(document.forma_capto243.email_invitado_10) == false) { return false; }

				}

			}

		}				

	}

	return true;

}





function reemplazaQuotes(theString) { 

	var s = theString;

	var s1 = "";

	var k = s.length;

	var i = 0;

	while (k >=0) {

		if (s.charAt(k) == "'") {

			s1 = s.substring(0,k+1) + "'" + s.substring(k+1,s.length);

			s = s1;

		}

		k--;

	}

	return s;

}





function ValidaRUT() {

	if (document.forms[1].run.disabled != true) {

		var ElRut = document.forms[1].run.value.toUpperCase();

		var ElDv = document.forms[1].runDv.value.toUpperCase();

		var largo_run = ElRut.length;

		var largo_dv = ElDv.length;

		var RutC = ElRut+ElDv;

		var run00 = "000000000";

		if (largo_run==0) {

			alert("No se ha ingresado una Cédula de Identidad.\n\nPor favor inténtelo nuevamente");

			document.forms[1].run.focus();

			return (false);

		}

		if (largo_dv==0) {

			alert("El Dígito Verificador está vacío");

			document.forms[1].runDv.focus();

			return (false);

		if (ElRut.substring(0, 1) == "0"){

			alert("La Cédula de Identidad ingresada no es válida\n\nPor favor inténtelo nuevamente");

			document.forms[1].run.focus();

			return false;

		}

		if (run00.substring(0, largo_run) == ElRut){

			alert("La Cédula de Identidad ingresada no es válida\n\nPor favor inténtelo nuevamente");

			document.forms[1].run.focus();

			return (false);

		}

		} else if ( largo_dv == 2 ){

			alert("El Dígito Verificador sólo debe tener un caracter");

			document.forms[1].runDv.focus();

			return (false);

	    }

		for(i=0;i<largo_run;i++){

			c = ElRut.charAt(i);

			if (c<"0" || c>"9"){

				alert("La Cédula de Identidad ingresada no es válida\n\nPor favor inténtelo nuevamente");

				document.forms[1].run.focus();

				return (false);

			}

		}

		c=ElDv.charAt(0);

		if ( (c < "0" || c > "9") && c != "K" ){

			alert("El Digito Verificador de la Cédula de Identidad ingresado no es válido\n\nPor favor inténtelo nuevamente");

			document.forms[1].runDv.focus();

			return (false);

		}

		var suma=0;

		var mult=2;

		if (ElDv=="K") ElDv="10";

		for (i=largo_run-1;i>=0;i--){

			c=ElRut.charAt(i);

			suma+=parseInt(c,10)*mult;

			mult++;

			if (mult > 7) mult = 2;

		}

		var calculado = 11 - suma%11;

		if (calculado == 11) calculado = 0;

		if (parseInt(ElDv) != calculado){

			alert("El digito verificador no corresponde al número de cédula de identidad ingresado\n\nPor favor inténtelo nuevamente");

			document.forms[1].runDv.focus();

			return (false);

		}

	}

}



function ValidaMail(cual) {

	if (cual.value != "")

	{

		var mail=cual.value;

		var largo;

		var valido1 = 0;

		var valido2 = 0;

		var valido3 = 1;

		largo=mail.length;

		for (i=1;i<=largo;i++) 

		{

			if (mail.charAt(i)=='@') {valido1=1;}

			if (mail.charAt(i)=='.') {valido2=valido1;}

			if (mail.charAt(i)==' ') {valido3=0;}

		}

		if (!(valido1 && valido2 && valido3)) {

			alert(cual.value +" no es una dirección válida");

			cual.focus();

			return false;

		}

	}

}



function ValidaEscrito(cual, mensaje) {

	if (cual.value == '' || cual.value == ' ') {

		alert('Ingrese ' + mensaje); 

		cual.focus();

		return false;

	}	

}



function ValidaLongitud(cual, largo) {

	if (cual.value.length < largo) {

		alert('El largo debe ser mayor a ' + largo + ' caracteres.'); 

		cual.focus();

		return false;

	}	

}



function ValidaIguales(cual1, cual2, mensaje) {

	if (cual1.value != cual2.value) {

		alert(mensaje + "deben ser iguales!"); 

		cual2.focus();

		return false;

	}	

}





function ValidaSeleccionado(cual, mensaje) {

	var x=document.getElementById(cual)

	if (x.selectedIndex == 0) {

		alert("Debe seleccionar " + mensaje);

		x.focus();

		return false;

	}

}



function ValidaNumeros(cual, que) {

	if (cual.value != "")

	{

		var Chars = "0123456789";

		if(cual.value.length < 7  ){
			
			alert("La cantidad de numeros ingresados no es suficiente.");
			cual.focus();
			return false;
		}
		
		
		for (var i = 0; i < cual.value.length; i++) 

		{

			
						
			if (Chars.indexOf(cual.value.charAt(i)) == -1)

			{

				alert (que + ' sólo puede contener números, sin caracteres alfanumericos ni espacios al medio, inicio o final.');

				cual.focus();

				return false;

			}

		}

	}	

}



function ValidaCaracteres(cual, que) {

	if (cual.value != "")

	{

		var Chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		for (var i = 0; i < cual.value.length; i++) 

		{

			if (Chars.indexOf(cual.value.charAt(i).toUpperCase()) == -1)

			{

				alert ('Por favor, ingrese sólo números o letras, sin puntos, guiones, ni espacios en ' + que);

				cual.focus();

				return false;

			}

		}

	}	

}



function ValidaCaracteresMail(cual, que) {

	if (cual.value != "")

	{

		var Chars = "0123456789@_.abcdefghijklmnopqrstuvwxyz";

		for (var i = 0; i < cual.value.length; i++) 

		{

			if (Chars.indexOf(cual.value.charAt(i).toLowerCase()) == -1)

			{

				alert ('Por favor, ingrese sólo carácteres válidos en la dirección de mail');

				cual.focus();

				return false;

			}

		}

	}	

}



function ValidaFecha(cual, cero){

	var CadenaChequear = "0123456789";

	var CampoFecha = cual;

	var ValorFecha = "";

	var TempFecha = "";

	var Dia;

	var Mes;

	var Anno;

	var Salto = 0;

	var err = 0;

	var i;

	err = 0;

	ValorFecha = CampoFecha.value;

	for (i = 0; i < ValorFecha.length; i++) {

	if (CadenaChequear.indexOf(ValorFecha.substr(i,1)) >= 0) {

		TempFecha = TempFecha + ValorFecha.substr(i,1);

	}

	}

	ValorFecha = TempFecha;



	Anno = ValorFecha.substr(0,4);

	Mes = ValorFecha.substr(4,2);

	Dia = ValorFecha.substr(6,2);

	

// Si cualquiera de los valores no esta en el rango o es cero



	if (cero == 1) {

		if (Math.abs(Dia) < 1) {

			alert("Debe ingresar un dia!");

			document.inscripcion.fec_nacim_dia.focus();

			return false;

		}

		if ((Math.abs(Mes) < 1) || (Math.abs(Mes) > 12)) {

			alert("Debe ingresar un mes correcto!");

			document.inscripcion.fec_nacim_mes.focus();

			return false;

		}

		if (Math.abs(Anno) == 0) {

			alert("Debe ingresar un año!");

			document.inscripcion.fec_nacim_ano.focus();

			return false;

		}

	}

	

// Si es año Bisiesto	

	if ((Anno % 4 == 0) || (Anno % 100 == 0) || (Anno % 400 == 0)) {

		Salto = 1;

	}

	

// Dias en Febrero

	if ((Mes == 2) && (Salto == 1) && (Dia > 29)) {

		err = 23;

	}

	if ((Mes == 2) && (Salto != 1) && (Dia > 28)) {

		err = 24;

	}

	

// Meses de 30 dias	

	if ((Dia > 30) && ((Mes == "04") || (Mes == "06") || (Mes == "09") || (Mes == "11"))) {

		err = 26;

	}

	

// TODO OK	

	if (err != 0) {

		alert("La fecha " + CampoFecha.value + " es incorrecta!");

		return false;

	}

}