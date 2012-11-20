 
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function capto243(url) {

	switch(url) {
		case "simulador":
			link = "productos_simulador";
			break;
		case "comprar":
			link = "grupos_libros";
			break;
		case "comprar_music":
			//link = "grupos_musica";			link = "grupos_musica";
			break;
		case "capitulos_comprados":
			link = "grupos_zona_descargas";
			break;
		case "categorias_ebusiness":
			link = "grupos_categorias_ebusiness";
			break;
		case "grupos":
			link = "control_grupos";
			break;
		case "grupos_historico":
			link = "control_grupos_historico";
			break;
		case "finanzas":
			link = "control_finanzas";
			break;

		case "manual_compra":
			link = "help_manual_compra";
			break;
		case "manual_operaciones":
			link = "help_manual_operaciones";
			break;
		case "manual_panel":
			link = "help_manual_panel";
			break;
		case "resumen_manual":
			link = "help_resumen_manual";
			break;
		case "faq":
			link = "help_faq";
			break;
		case "status":
			link = "help_glosario";
			break;

		case "registro":
			link = "perfil_personal";
			break;


		case "modulo_compra_libros":
			link = "productos_compra_libros";
			break;

		case "modulo_compra_musica":
			link = "productos_compra_musica";
			break;

		case "mis_objetivos":
			link = "productos_mis_objetivos";
			break;

		case "categorias_productos":
			link = "productos_categorias";
			break;

		default:
			link = url;
			break;
	}
//	alert(url);
//	alert("../capto243/index.php?panel=" + link  + ".php");
	location.href="../captomexico/index.php?panel=" + link + ".php";    
}

function ltrim(str) { 
	for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
	return str.substring(k, str.length);
}

function rtrim(str) {
	for(var j=str.length-1; j>=0 && isWhitespace(str.charAt(j)) ; j--) ;
	return str.substring(0,j+1);
}

function trim(str) {
	return ltrim(rtrim(str));
}

function isWhitespace(charToCheck) {
	var whitespaceChars = " \t\n\r\f";
	return (whitespaceChars.indexOf(charToCheck) != -1);
}

var request = false;

if (window.XMLHttpRequest) {
	request = new XMLHttpRequest();
}

function checkName(field) {

	if (window.ActiveXObject) {
    	try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                request = false;
            }
        }
    }

    if (request) {
        request.onreadystatechange = processReqChange;
        request.open("GET", "check.php?name=" + encodeURIComponent(field));
        request.send(null);
    }
}

function processReqChange() {
    var result = document.getElementById("result");

    if (request.readyState == 4) {
        if (request.status == 200) {
            result.innerHTML = request.responseText;
        }
    } else {
        result.innerHTML = "Procesando ...";
    }
}

function EnviarInscripcion(paso) {
	//alert(paso);
	if (Validar('inscripcion') == false) { 
		return false; 
	} else { 
		document.inscripcion.paso.value = paso;
		document.inscripcion.submit(); 
	} 
}

function EnviarInscripcionEditada(paso, valida) {
	if (Validar(valida) == false) { 
		return false; 
	} else { 
		document.inscripcion.paso.value = paso;
		document.inscripcion.que_actualizo.value = valida;
		document.inscripcion.submit();
	} 
}

function Contacto() {
	if (Validar('forma_contacto') == false) { 
		return false; 
	} else { 
		document.forma_contacto.paso.value = 1;
		document.forma_contacto.submit();
	} 
}


function ContactoInterno() {
	if (Validar('forma_contacto_interno') == false) { 
		return false; 
	} else { 
		document.forma_contacto.paso.value = 1;
		document.forma_contacto.submit();
	} 
}

