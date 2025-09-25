/**
  * Nombre del M�dulo: Aseguramiento                                              
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 02/Diciembre/2011                                       			
  * Descripci�n: Este archivo contiene la funcion que verifica que exista  una clausula en el Catalogo de Clausulas en caso de que exista
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionBC;
	
	var opc;
	
	/*Esta funci�n obtendr� el dato que se quiere validar*/
	function verificarClausula(clausula, manual){
		if(manual!=""){
			//Obtener el dato que se quiere validar
			var cveClausula = clausula.value.toUpperCase();
			var cveManu = manual.toUpperCase();

			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
 			var url = "includes/ajax/buscarClausula.php?manual="+cveManu+"&clausula="+cveClausula;		
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 	*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoClausula(url, "GET", procesarRespuestaClausula);
		}
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaClausula(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionBC.readyState==READY_STATE_COMPLETE){
			if(peticionBC.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionBC.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					document.getElementById("errorClausula").style.visibility = "visible";		
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					alert("La Clave "+clave+" Esta Asignada a "+nombre);																				
					//document.getElementById("hdn_claveValida").value = "no";
					document.getElementById("sbt_guardar").disabled=true;
					document.getElementById("btn_regFormato").disabled=true;
				}
				else{
					document.getElementById("errorClausula").style.visibility = "hidden";				
					//document.getElementById("hdn_claveValida").value = "si";
					document.getElementById("sbt_guardar").disabled=false;
					document.getElementById("btn_regFormato").disabled=false;
				}
			}//If if(peticion_http_val.status==200)
		}//If if(peticion_http_val.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoClausula(url, metodo, funcion) {
		peticionBC = inicializarXHR();
		if(peticionBC) {
			peticionBC.onreadystatechange = funcion;
			peticionBC.open(metodo, url, true);
			peticionBC.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}