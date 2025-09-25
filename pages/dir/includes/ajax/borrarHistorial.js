/**
  * Nombre del M�dulo: Direcci�n General                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Febrero/2012
  * Descripci�n: Este archivo muestra los registros previos y siguientes a los de los avances en pantalla
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function borrarHistorial(){
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/borrarHistorial.php?nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		borrarGraficos(url, "GET", procesarBorrado);
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarBorrado(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//No realizar ninguna operacion
				var x=0;
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function borrarGraficos(url, metodo, funcion) {
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