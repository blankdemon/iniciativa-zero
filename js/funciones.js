	function uno(s,c) { 
		s.style.backgroundColor = '#' + c;
		/*s.style.cursor='hand';*/
	}
	
	function dos(s,c) { 
		s.style.backgroundColor = '#' + c;
		s.style.cursor = 'default';
	}
	
	// limite de contenido de textareas
	function contarLetras(o, q) { 
		if(o.value.length >= q) {
			o.value = o.value.substring(0, q);
			alert("Limite superado. " + q + " caracteres como maximo");
		}
	}

	function ltrim(str) { 
		for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
		return str.substring(k, str.length);
	}

	function rtrim(str) {
		for(var j=str.length-1; j>=0 && isWhitespace(str.charAt(j)) ; j--) ;
		return str.substring(0,j+1);
	}
	
	// elimina los espacion en blanco a los extremos
	function trim(str) {
		return ltrim(rtrim(str));
	}
	
	// chequea los espacios en blanco
	function isWhitespace(charToCheck) {
		var whitespaceChars = " \t\n\r\f";
		return (whitespaceChars.indexOf(charToCheck) != -1);
	}
	
	// confirmar eliminacion destacadas
	function confirmarEliminacionDestacada(id_pelicula) {
		if(confirm("Estas seguro(a) que quieres eliminar esta pelicula destacada?")) window.location = '?id_pagina=24&id_destacado_eliminar=' + id_pelicula + '&action=eliminar';
	}
	
	function mouseDown(e) {
		if (parseInt(navigator.appVersion)>3) {
			var evt = navigator.appName=="Netscape" ? e : event;
			if (navigator.appName=="Netscape" && parseInt(navigator.appVersion)==4) {
				// NETSCAPE 4 CODE
				var mString =(e.modifiers+32).toString(2).substring(3,6);
				shiftPressed=(mString.charAt(0)=="1");
				self.status="modifiers="+e.modifiers+" ("+mString+")"
			} else {
				// NEWER BROWSERS [CROSS-PLATFORM]
				shiftPressed=evt.shiftKey;
				self.status=""
				+  "shiftKey="+shiftPressed 
			}
		}
		return true;
		
		/*if (parseInt(navigator.appVersion)>3) {
					document.onmousedown = mouseDown;
					if (navigator.appName=="Netscape") document.captureEvents(Event.MOUSEDOWN);
				}*/
	}
	
	function checkInputs(i) {
		if(firstProd>0) {
			secondProd = i;
			if(secondProd>0) {
				var inicio = ((secondProd-firstProd)>=0) ? firstProd : secondProd;
				var fin = ((secondProd-firstProd)>=0) ? secondProd : firstProd;
				eval("var contenido = document.flist.fventa" + firstProd + ".value;");	
				if (shiftPressed) {
					for(var c=inicio;c<=fin;c++) eval("document.flist.fventa" + c + ".value = contenido;");
				}
				firstProd = secondProd;
			}
		} else {
			firstProd = i;
		}
	}


	
	function hidePest(capa) { var capa;timerId=setTimeout("visibilidad(\'"+capa+"\',false)",500);}
	function holdPest(capa) { var capa;visibilidad(capa,true);clearTimeout(timerId);}
