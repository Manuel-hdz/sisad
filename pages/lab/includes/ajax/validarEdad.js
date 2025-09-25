/**
  * Nombre del M�dulo: Laboratorio                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 18/Julio/2011                                       			
  * Descripci�n: Este archivo contiene las funciones para obtener los datos que ser�n registrados en la Salida de Material de manera Asincrona.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n obtendr� el dato que se quiere validar y realizar� la Petici�n Asincrona al Servidor */
	function verificarEdad(campo,clave){
		document.getElementById("error").style.visibility = "hidden";
		//Verificar que el dato que se esta buscando sea diferente de vac�o
		if(campo.value!=""){
			//Obtener el valor de los parametros ,mandados a la funcion
			var edad = campo.value;
			var clave = clave.value;
			//Ocultar el mensaje que indica si la clave fue encontrada o no
			document.getElementById("error").style.visibility = "hidden";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
 			var url = "includes/ajax/validarEdad.php?edad="+edad+"&clave="+clave;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoMaterial(url, "GET", procesarEdad);
		}
	}//Fin de la Funcion verificarEdad(campo,clave)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoMaterial(url, metodo, funcion) {
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

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarEdad(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					document.getElementById("btn_cargaFotos").disabled = true;
					//Obtener los datos del material del Archivo XML
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var edad = respuesta.getElementsByTagName("edad").item(0).firstChild.data;
					
					document.getElementById("error").style.visibility = "visible";
					document.getElementById("hdn_edadValida").value = "no";
					alert("La Mezcla: "+clave+"\nYa Tiene Datos Registrados a la Edad de "+edad+" D�as");
				}
				else{
					document.getElementById("error").style.visibility = "hidden";
					document.getElementById("hdn_edadValida").value = "si";
					document.getElementById("btn_cargaFotos").disabled = false;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()