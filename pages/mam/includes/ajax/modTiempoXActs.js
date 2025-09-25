/**
  * Nombre del M�dulo: Mantenimiento
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 16/Julio/2012
  * Descripci�n: Este archivo contiene las funciones para cargar el Tiempo a la Sesion de las Actividades de Mtto
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
			//Guardar la pagina a la cual se redireccionar�
			var tiempo=cajaTiempo.value;
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(verificarGama.js)
			var url = "includes/ajax/modTiempoXActs.php?tiempo="+tiempo+"&ind="+actividad;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			guardaTiempoGama(url, "GET", procesarRespuestaGuardarTiempoGama);
			cajaTiempo.readOnly=true;
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function guardaTiempoGama(url, metodo, funcion) {
		peticion_http_gama = inicializa_xhr_gama();
		if(peticion_http_gama){
			peticion_http_gama.onreadystatechange = funcion;
			peticion_http_gama.open(metodo, url, true);
			peticion_http_gama.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializa_xhr_gama() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaGuardarTiempoGama(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_gama.readyState==READY_STATE_COMPLETE){
			if(peticion_http_gama.status==200){
				alert("El Tiempo Modificado ha Sido Registrado");
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()