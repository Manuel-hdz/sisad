/**
  * Nombre del Módulo: Recursos Humanos                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 15/Junio/2011
  * Descripción: Este archivo contiene las funciones para validar las claves de los datos que serán registrados en la BD de manera Asincrona y de ese modo indicar al usuario cuando una
  * clave esta repetida en la BD antes de que envie los datos para su registro.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petición HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_password;


	/*Esta función obtendrá el dato que se quiere validar*/
	function verificarPassword(pass){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/validarPassword.php?pass="+pass;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoEntrada(url, "GET", procesarRespuestaPass);
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaPass(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_password.readyState==READY_STATE_COMPLETE){
			if(peticion_http_password.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_password.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				//Si genera Resultados, tiene Entradas Registradas, por lo tanto, desactivar los Elementos para Registro de Entrada
				if (existe!="true"){
					alert("La Contraseña Introducida No Corresponde con la Actual");
					document.getElementById("error").style.visibility="visible";
					document.getElementById("hdn_claveValida").value="no";
				}
				else{
					document.getElementById("error").style.visibility="hidden";
					document.getElementById("hdn_claveValida").value="si";
				}
				//else{...}
			}//If if(peticion_http_password.status==200)
		}//If if(peticion_http_password.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoEntrada(url, metodo, funcion) {
		peticion_http_password = inicializa_xhr_password();
		if(peticion_http_password) {
			peticion_http_password.onreadystatechange = funcion;
			peticion_http_password.open(metodo, url, true);
			peticion_http_password.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_password() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}