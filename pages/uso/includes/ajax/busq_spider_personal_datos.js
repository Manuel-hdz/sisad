/**
  * Nombre del M�dulo: Recursos Humanos                                               
  * �Concreto Lanzado de Fresnillo MARCA 
  * Fecha: 30/Marzo/2011
  * Descripci�n: Este archivo contiene las funciones para obtener las sugerencias de la BD, cuando el usuario esta buscando un dato en particular
  */

/*Esta funci�n recoje los datos necesarios para realizar la opetici�n al servidor y buscar los datos de acuerdo al texto ingresado por el usuario*/
function lookup(cajaTexto,nomTabla,num) {
	//Obtener el dato a buscar
	var inputString = cajaTexto.value;
	if(inputString.length == 0) {
		//Si el dato a buscar esta vac�o, no mostrar el mensaje de sugerencias
		$('#suggestions'+num).hide();
		document.getElementById("txt_rfc").value="";
		document.getElementById("txt_noEmpleado").value="";
		document.getElementById("txt_area").value="";
		document.getElementById("txt_puesto").value="";
	} 
	else{
		//Obtener el nombre de la caja de texto que contendr� el valor seleccionado
		var nomCajaTexto = cajaTexto.name;
		//Enviar la petici�n al servidor para realizar la consulta de los datos que ser�n mostrados en el Layer de sugerencias
		$.post("includes/ajax/busq_spider_personal_datos.php?nomCajaTexto="+nomCajaTexto+"&nomTabla="+nomTabla+"&num="+num, {queryString: ""+inputString+""}, function(data){
			//Si los datos obtenidos son mayores que 0, entonces desplegar el Layer con las sugerencias
			if(data.length >0) {
				$('#suggestions'+num).show();
				$('#autoSuggestionsList'+num).html(data);
			}
		});
	}
}//Fin de la funcion lookup(cajaTexto,nomTabla,nomCampo,num)

/*Esta funci�n se encarga de asignar el valor seleccionado a la caja de texto correspondiente*/	
function fill(nomCampo,thisValue,num) {
	//Asignar el valor seleccionado a la caja de texto correspondiente
	$('#'+nomCampo).val(thisValue);
	//Ocultar el layer que muestra las sugerencias
	$('#suggestions'+num).hide();
}

function escribirDatosEmp(rfc,num,puesto,area){
	//alert("RFC: "+rfc+" Num: "+num+" Puesto: "+puesto+" Area: "+area);
	document.getElementById("txt_rfc").value=rfc;
	document.getElementById("txt_noEmpleado").value=num;
	document.getElementById("txt_area").value=area;
	document.getElementById("txt_puesto").value=puesto;
	/*
	//Verificar de que color debe quedar en el Fondo segun la Existencia
	if(existencia==0)
		nom_clase = "style='background-color:#FFA';font-weight:bold";
	else
		nom_clase = "style='background-color:#B7DEE8';font-weight:bold";

	//Quitar la palabra "inch" y en su lugar mostrar el simbolo de pulgadas
	nombre=nombre.replace("inch",'"');
	nombre=nombre.replace("�",'\'');
	//Dibujar el resultado dentro de la capa correspondiente
	document.getElementById(capa).innerHTML="<label style=\"background-color:#9BBA59;color:#FFFFFF\"><strong>CLAVE:&nbsp;&nbsp</strong></label><label "+nom_clase+">&nbsp;&nbsp"+id+"&nbsp;&nbsp</label><label style=\"background-color:#9BBA59;color:#FFFFFF\"><strong>NOMBRE:&nbsp;&nbsp</strong></label><label "+nom_clase+">&nbsp;&nbsp"+nombre+"&nbsp;&nbsp</label><label style=\"background-color:#9BBA59;color:#FFFFFF\"><strong>EXISTENCIA:&nbsp;&nbsp</strong></label><label "+nom_clase+">&nbsp;&nbsp"+existencia+"&nbsp;&nbsp</label>";
	*/
}