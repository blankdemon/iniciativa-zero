 function vende(v){
	popup('../captomexico/ventana.php?ventana=vender&compra_ayuda='+v+'&id_compra='+document.forma_capto243.id_compra.value,'Ayuda', 550, 783, 1);
 }
 
function cambia(paso, quevalida) {

	if (quevalida != "") {

		eval("bariable = Validar('" + quevalida + "');");

	} else {

		bariable = 1;	

	}

	if (bariable) {

		//alert(paso);
		document.forma_capto243.paso.value = paso;

		document.forma_capto243.submit();

	}

}



function popup(url, nombre, alto, ancho, ajustable) { // RUTINA QUE ABRE VENTANAS!!

	var w = screen.width;

	var h = screen.height - 30;

	var derecha = (w - ancho) / 2;

	var arriba = ((h - alto) / 2) - 20;

	eval(nombre + " = open(url, 'ventana_' + nombre, 'width=' + ancho + ', height=' + alto + ', resizable=' + ajustable + ', top=' + arriba + ', left=' + derecha + ', scrollbars=1')");

	eval(nombre + ".focus()");

	return false;

}



function popup2(url, nombre, alto, ancho, ajustable) { // RUTINA QUE ABRE VENTANAS!!

	var w = screen.width;

	var h = screen.height - 30;

	var derecha = (w - ancho) / 2;

	var arriba = ((h - alto) / 2) - 20;

	eval(nombre + " = open(url, 'ventana_' + nombre, 'width=' + ancho + ', height=' + alto + ', resizable=' + ajustable + ', top=' + arriba + ', left=' + derecha + ', scrollbars=0')");

	eval(nombre + ".focus()");

	return false;

}


function popup3(url, nombre, alto, ancho, ajustable) { // RUTINA QUE ABRE VENTANAS!!


	//alert(url);
	
	var w = screen.width;

	var h = screen.height - 30;

	var derecha = (w - ancho) / 2;

	var arriba = ((h - alto) / 2) - 20;

	eval(nombre + " = open(url, 'ventana_' + nombre, 'width=' + ancho + ', height=' + alto + ', resizable=' + ajustable + ', top=' + arriba + ', left=' + derecha + ', scrollbars=0')");

	eval(nombre + ".focus()");

	return false;

}


function popUpp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,men ubar=0,resizable=0,width=320,height=240');");
}

//Agregado desde el archivo grupos_ver_carrito
function eliminar(k){

document.carro.cod_elimina.value=k;document.carro.submit();

}
function cambia_cat(k){

document.carro.cat.value='1';
document.carro.submit();

}
function autocompletar(){

document.carro.autocom.value='1';document.carro.submit();

}
function checkAll(field)

{

for (i = 0; i < field.length; i++)
	field[i].checked = true ;

}



function uncheckAll(field)

{

for (i = 0; i < field.length; i++)

	field[i].checked = false ;

}

function verificar_eliminar(veri){
    var seleccionado=false;
	for(var i=1;i<veri.length;i++){
	
	  if(veri[i].checked){
			seleccionado=true;
			break;
		}
	}
	
	if(seleccionado==false){ alert("Debe seleccionar al menos un producto");}
	else{document.carro.cod_elimina.value=1; document.carro.submit();}
}

function verificar_recalcular(veri){
    var seleccionado=true;
	for(var i=0;i<veri.length;i++){
	
	  if(IsNumeric(veri[i].value)==false){
			seleccionado=false;
			break;
		}
	}
	
	if(seleccionado==false){ alert("Una/as de las cantidad/es es/son erronea/s");}
	else{ document.carro.recal.value=1;document.carro.submit();}
}

function IsNumeric(valor)
{
 var log=valor.length; var sw="S";
for (x=0; x<log; x++)
{ v1=valor.substr(x,1);
v2 = parseInt(v1);
//Compruebo si es un valor numérico
if (isNaN(v2)) { sw= "N";}
}
if (sw=="S") {return true;} else {return false; } 
   
}