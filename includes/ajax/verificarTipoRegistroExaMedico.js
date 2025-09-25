/**
  * Nombre del Módulo: Unidad de Salud Ocupacional                                             
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 21/Marzo/2012
  * Descripción: Este archivo contiene la funcion que valida que la informacion de la Empresa Externa ya se encuentre registrada en la BD.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta función verifica que los datos de una empresa ya se encuentren registrados en la BD */
	function verificarRegistroExamenMedico(clave){
		if(clave!=""){
			//Crear la URL, la cual será solicitada al Servidor 
			var url = "includes/ajax/verificarTipoRegistroExaMedico.php?clave="+clave;
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
				//Variable que contiene el tipo de registro del examen 
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if(existe=="true"){
					var claveExa = respuesta.getElementsByTagName("claveExa").item(0).firstChild.data;
					var nomExa = respuesta.getElementsByTagName("nomExa").item(0).firstChild.data;					
					var tipoExa = respuesta.getElementsByTagName("tipoExa").item(0).firstChild.data;
					var costoExa = respuesta.getElementsByTagName("costoExa").item(0).firstChild.data;
					var com = respuesta.getElementsByTagName("com").item(0).firstChild.data;	
								
					//Si el campo de comnetarios se encunetra vacio 
					if(com=="¬ND")
						com="";
					
					//Se asignan los valores de los campos en sus respectivas cajas de texto dentro del formulario
					document.getElementById("hdn_claveExamen").value=claveExa;
					document.getElementById("txt_nomExamen").value=nomExa;
					document.getElementById("txt_tipoExamen").value=tipoExa;
					document.getElementById("txt_costoExamen").value=costoExa;
					document.getElementById("txa_comentarios").value=com;
					
				
					//Solo hasta que se el usuario haya seleccionado un opcion del combo se habilitaran los siguientes campos dentro del formulario
					document.getElementById("hdn_claveExamen").readOnly=false;
					document.getElementById("txt_nomExamen").readOnly=false;
					document.getElementById("txt_tipoExamen").readOnly=false;
					document.getElementById("txt_costoExamen").readOnly=false;
					document.getElementById("txa_comentarios").readOnly=false;
				}	
				else{
					//De lo contrario las opciones se deben de mostrar desahabilitados
					document.getElementById("hdn_claveExamen").readOnly=true;
					document.getElementById("txt_nomExamen").readOnly=true;
					document.getElementById("txt_tipoExamen").readOnly=true;
					document.getElementById("txt_costoExamen").readOnly=true;
					document.getElementById("txa_comentarios").readOnly=true;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()--procesarTipoReg