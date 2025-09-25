/**
  * Nombre del M�dulo: Unidad de Salud Ocupacional                                             
  * �Concreto Lanzado de Fresnillo MARCA 
  * Fecha: 21/Marzo/2012
  * Descripci�n: Este archivo contiene la funcion que valida que la informacion de la Empresa Externa ya se encuentre registrada en la BD.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n verifica que los datos de una empresa ya se encuentren registrados en la BD */
	function verificarRegistroProyecciones(clave){
		if(clave!=""){
			//Crear la URL, la cual ser� solicitada al Servidor 
			var url = "includes/ajax/verificarTipoRegistroCatRadiografias.php?clave="+clave;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� 
			del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al 
			servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarTipoRegistro(url, "GET", procesarTipoReg);

		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarTipoRegistro(url, metodo, funcion) {
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
	function procesarTipoReg(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Variable que contiene el tipo de registro del examen 
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if(existe=="true"){
					var claveProy = respuesta.getElementsByTagName("claveProy").item(0).firstChild.data;
					var nomProy = respuesta.getElementsByTagName("nomProy").item(0).firstChild.data;					
					var com = respuesta.getElementsByTagName("com").item(0).firstChild.data;	
								
					//Si el campo de comnetarios se encunetra vacio 
					if(com=="�ND")
						com="";
					
					//Se asignan los valores de los campos en sus respectivas cajas de texto dentro del formulario
					document.getElementById("hdn_claveProyeccion").value=claveProy;
					document.getElementById("txt_nomProyeccion").value=nomProy;
					document.getElementById("txa_comentarios").value=com;
					
				
					//Solo hasta que se el usuario haya seleccionado un opcion del combo se habilitaran los siguientes campos dentro del formulario
					document.getElementById("hdn_claveProyeccion").readOnly=false;
					document.getElementById("txt_nomProyeccion").readOnly=false;
					document.getElementById("txa_comentarios").readOnly=false;
				}	
				else{
					//De lo contrario las opciones se deben de mostrar desahabilitados
					document.getElementById("hdn_claveProyeccion").readOnly=true;
					document.getElementById("txt_nomProyeccion").readOnly=true;
					document.getElementById("txa_comentarios").readOnly=true;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()--procesarTipoReg