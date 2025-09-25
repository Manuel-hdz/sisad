/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 16/Julio/2012
  * Descripción: Este archivo contiene las funciones para cargar el Tiempo a la Sesion de las Actividades de Mtto
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;

	/*Funcion para modificar y guardar el Tiempo aproximado de una actividad*/
	function modTiempoXActividad(cajaTiempo,actividad){
		//Verificar si la caja de Texto esta en Readonly para habilitarla y poder Editarla
		if(cajaTiempo.readOnly){
			cajaTiempo.readOnly=false;
			cajaTiempo.focus();
		}
		else{
			//Guardar la pagina a la cual se redireccionará
			var tiempo=cajaTiempo.value;
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(verificarGama.js)
			var url = "includes/ajax/modTiempoXActs.php?tiempo="+tiempo+"&ind="+actividad;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			guardaTiempoGama(url, "GET", procesarRespuestaGuardarTiempoGama);
			cajaTiempo.readOnly=true;
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function guardaTiempoGama(url, metodo, funcion) {
		peticion_http_gama = inicializa_xhr_gama();
		if(peticion_http_gama){
			peticion_http_gama.onreadystatechange = funcion;
			peticion_http_gama.open(metodo, url, true);
			peticion_http_gama.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_gama() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaGuardarTiempoGama(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_gama.readyState==READY_STATE_COMPLETE){
			if(peticion_http_gama.status==200){
				alert("El Tiempo Modificado ha Sido Registrado");
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()