/**
  * Nombre del Módulo: Seguridad Industrial                                              
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 21/Marzo/2012
  * Descripción: Este archivo contiene la funcion que valida que el tiempo de vida del material de seguridad ya se encuentre registrado en la BD.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta función que verifica que un material ya se encuentre registrado en la BD */
	function verificarRegistroMaterialES(clave){
		if(clave!=""){
			//Crear la URL, la cual será solicitada al Servidor 
			var url = "includes/ajax/verificarTipoRegistroES.php?clave="+clave;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché 
			del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarTipoRegistro(url, "GET", procesarTipoReg);

		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarTipoRegistro(url, metodo, funcion) {
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
	function procesarTipoReg(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Variable que contiene el tipo de registro del material
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if(existe=="true"){
					var claveMat = respuesta.getElementsByTagName("claveMat").item(0).firstChild.data;
					var tiempoVida = respuesta.getElementsByTagName("tiempoVida").item(0).firstChild.data;
					var tipoTiempo = respuesta.getElementsByTagName("tipoTiempo").item(0).firstChild.data;
					var fechaReg = respuesta.getElementsByTagName("fechaReg").item(0).firstChild.data;
					var obs = respuesta.getElementsByTagName("obs").item(0).firstChild.data;	
					
					if(obs=="¬ND")
						obs="";
					
					document.getElementById("txt_claveMaterial").value=claveMat;
					document.getElementById("txt_tiempoVida").value=tiempoVida;
					document.getElementById("cmb_tipoTiempo").value=tipoTiempo;
					document.getElementById("txt_fechaReg").value=fechaReg;
					document.getElementById("txa_observaciones").value=obs;
					//Botones que se encuentran en la seccion de Registrar Tiempo de Vida Útil de los equipos de Seguridad y que se condicionan en cuanto se seelcciona un material
					document.getElementById("sbt_guardar").disabled=true;
					document.getElementById("sbt_modificar").disabled=false;

				}	
				else{
					//De lo contrario los botones se deben de mostrar desahabilitados
					document.getElementById("sbt_modificar").disabled=true;
					document.getElementById("sbt_guardar").disabled=false;					
				}
				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()--procesarTipoReg