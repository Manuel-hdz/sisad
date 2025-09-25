/**
 * Nombre del M�dulo: Compras                                               
 * �Concreto Lanzado de Fresnillo S.A. de C.V.
 * Fecha: 26/Noviembre/2010                                      			
 * Descripci�n: Este archivo contiene las funciones para obtener las sugerencias de la BD, cuando el usuario esta buscando un dato en particular
 */


/*Esta funci�n recoje los datos necesarios para realizar la opetici�n al servidor y buscar los datos de acuerdo al texto ingresado por el usuario*/
function lookup(cajaTexto, cajaTexto2, nomBd, nomTabla, nomCampo, nomCampo2, num) {
	//Guardar el numero del Layer que despliega los resultados
	//Obtener el dato a buscar
	var inputString = cajaTexto.value;
	if (inputString.length == 0) {
		//Si el dato a buscar esta vac�o, no mostrar el mensaje de sugerencias
		$('#suggestions' + num).hide();
	} else {
		//Obtener el nombre de la caja de texto que contendr� el valor seleccionado
		var nomCajaTexto = cajaTexto.name;
		//Enviar la petici�n al servidor para realziar la consulta de los datos que ser�n mostrados en el Layer de sugerencias
		$.post("../../includes/ajax/busq_spider_2.php?nomCajaTexto=" + nomCajaTexto + "&nomCajaTexto2=" + cajaTexto2 + "&nomBd=" + nomBd + "&nomTabla=" + nomTabla + "&nomCampo=" + nomCampo + "&nomCampo2=" + nomCampo2 + "&num=" + num, {
			queryString: "" + inputString + ""
		}, function (data) {
			//Si los datos obtenidos son mayores que 0, entonces desplegar el Layer con las sugerencias
			if (data.length > 0) {
				$('#suggestions' + num).show();
				$('#autoSuggestionsList' + num).html(data);
			}
		});
	}
} //Fin de la funcion lookup(cajaTexto,nomTabla,nomCampo,num)


/*Esta funci�n se encarga de asignar el valor seleccionado a la caja de texto correspondiente*/
function fill(nomCampo, thisValue, nomCampo2, thisValue2, num) {
	//Asignar el valor seleccionado a la caja de texto correspondiente
	$('#' + nomCampo).val(thisValue);
	$('#' + nomCampo2).val(thisValue2);
	//Ocultar el layer que muestra las sugerencias
	$('#suggestions' + num).hide();
}