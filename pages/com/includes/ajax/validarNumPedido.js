/**
  * Nombre del Módulo: Compras
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Febrero/2010                                      			
  * Descripción: Este archivo contiene las funciones para determinar cuando una requisicion esta registrada y no ha sido pedida, de tal manera que se debe
  * redireccionar a la pantalla donde se colocan los precios de los materiales de la requisicion ingresada.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_req;

	/*Esta función obtendrá el dato que se quiere validar*/
	function verificarPedidoExistente(numPedido){
		if(numPedido!=""){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(validarEstado.js)
			var url = "includes/ajax/validarNumPedido.php?idPedido="+numPedido.toUpperCase();		
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoReq(url, "GET", procesarRespuestaReq);
		}
		else{
			document.getElementById("img_verPedido").style.visibility= "hidden";
			document.getElementById("txt_responsable").value="";
			document.getElementById("txt_aplicacion").value="";
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoReq(url, metodo, funcion) {
		peticion_http_req = inicializa_xhr_req();
		if(peticion_http_req) {
			peticion_http_req.onreadystatechange = funcion;
			peticion_http_req.open(metodo, url, true);
			peticion_http_req.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaReq(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_req.readyState==READY_STATE_COMPLETE){
			if(peticion_http_req.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_req.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var responsable=respuesta.getElementsByTagName("responsable").item(0).firstChild.data;
					var aplicacion=respuesta.getElementsByTagName("equipos").item(0).firstChild.data;
					document.getElementById("img_verPedido").style.visibility= "visible";
					document.getElementById("txt_responsable").value=responsable;
					document.getElementById("txt_aplicacion").value=aplicacion;
				}
				else{
					document.getElementById("img_verPedido").style.visibility= "hidden";
					document.getElementById("txt_responsable").value="";
					document.getElementById("txt_aplicacion").value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()