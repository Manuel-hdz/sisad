/**
  * Nombre del Módulo: Seguridad Industrial                                              
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 07/Febrero/2012
  * Descripción: Este archivo contiene la funcion que valida que el presupuesto no este incluido en otro 
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function obtenerAntiguedadEmp(nombre){
		if(nombre!=""){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la residuo 
			var url = "includes/ajax/obtenerAntiguedadEmpleado.php?nombre="+nombre;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché 
			del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarNombreEmp(url, "GET", procesarNombre);
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarNombreEmp(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}	

	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
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