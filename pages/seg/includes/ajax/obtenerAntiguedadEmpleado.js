/**
  * Nombre del M�dulo: Seguridad Industrial                                              
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 07/Febrero/2012
  * Descripci�n: Este archivo contiene la funcion que valida que el presupuesto no este incluido en otro 
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function obtenerAntiguedadEmp(nombre){
		if(nombre!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la residuo 
			var url = "includes/ajax/obtenerAntiguedadEmpleado.php?nombre="+nombre;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� 
			del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al 
			servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarNombreEmp(url, "GET", procesarNombre);
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarNombreEmp(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}	

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarNombre(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Variable que contiene el tipo de residuo
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if(existe=="true"){
					var idEmp = respuesta.getElementsByTagName("noEmp").item(0).firstChild.data;
					var antiguedad = respuesta.getElementsByTagName("antiguedad").item(0).firstChild.data;
					document.getElementById("txt_antEmp").value=antiguedad;
					document.getElementById("txt_ficha").value=idEmp;
				}																
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()