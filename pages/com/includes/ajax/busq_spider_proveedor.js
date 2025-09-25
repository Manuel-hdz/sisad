/*Esta función recoje los datos necesarios para realizar la opetición al servidor y buscar los datos de acuerdo al texto ingresado por el usuario*/
function lookup_proveedor(cajaTexto,num) {
	//Obtener el dato a buscar
	var inputString = cajaTexto.value;
	if(inputString.length == 0) {
		//Si el dato a buscar esta vacío, no mostrar el mensaje de sugerencias
		$('#suggestions'+num).hide();
	} 
	else{
		//Obtener el nombre de la caja de texto que contendrá el valor seleccionado
		var nomCajaTexto = cajaTexto.name;
		//Enviar la petición al servidor para realizar la consulta de los datos que serán mostrados en el Layer de sugerencias
		$.post("includes/ajax/busq_spider_proveedor.php?nomCajaTexto="+nomCajaTexto+"&num="+num, {queryString: ""+inputString+""}, function(data){
			//Si los datos obtenidos son mayores que 0, entonces desplegar el Layer con las sugerencias
			if(data.length >0) {
				$('#suggestions'+num).show();
				$('#autoSuggestionsList'+num).html(data);
			}
		});
	}
}//Fin de la funcion lookup_material(cajaTexto,nomTabla,nomCampo,num)

/*Esta función se encarga de asignar el valor seleccionado a la caja de texto correspondiente*/	
function fill(nomCampo,thisValue,num){
	thisValue=thisValue.replace("inch",'"');
	//Asignar el valor seleccionado a la caja de texto correspondiente
	$('#'+nomCampo).val(thisValue);
	//Ocultar el layer que muestra las sugerencias
	$('#suggestions'+num).hide();
}