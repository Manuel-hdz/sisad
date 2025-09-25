/**
  * Nombre del M�dulo: Recursos Humanos                                               
  * �CONCRETO LANZADO DE FRESNILLO MARCA
  * Fecha: 15/Junio/2011
  * Descripci�n: Este archivo contiene las funciones para validar las claves de los datos que ser�n registrados en la BD de manera Asincrona y de ese modo indicar al usuario cuando una
  * clave esta repetida en la BD antes de que envie los datos para su registro.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petici�n HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_empleado;


	/*Esta funci�n obtendr� el dato que se quiere validar*/
	function archivoCSV(fecha){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/validarArchivo.php?fecha="+fecha;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoEntrada(url, "GET", procesarRespuestaCSV);
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaCSV(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_empleado.readyState==READY_STATE_COMPLETE){
			if(peticion_http_empleado.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_empleado.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				//Si genera Resultados, tiene Entradas Registradas, por lo tanto, desactivar los Elementos para Registro de Entrada
				if (existe=="true"){
					var band=confirm("El Registro ya Existe, �Desea Sobrescribir el Registro Existente?");
					if (!band)
						frm_importarCSV.reset();
					else
						document.getElementById("hdn_sentencia").value=("U");
				}
				//else{...}
			}//If if(peticion_http_empleado.status==200)
		}//If if(peticion_http_empleado.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoEntrada(url, metodo, funcion) {
		peticion_http_empleado = inicializa_xhr_empleado();
		if(peticion_http_empleado) {
			peticion_http_empleado.onreadystatechange = funcion;
			peticion_http_empleado.open(metodo, url, true);
			peticion_http_empleado.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializa_xhr_empleado() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}