/**
  * Nombre del Módulo: Laboratorio                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 10/Febrero/2012                                       			
  * Descripción: Este archivo contiene las funciones para crear el ID de la muestra para realizar las pruebas de CONCRETO.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var petHttpClave;


	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function obtenerIdMuestraConcreto(codigo){
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este 
		//archivo JavaScript
 		var url = "includes/ajax/clavesConcreto.php?codigo="+codigo;
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
		 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		iniciarObjetosXHR(url, "GET", crearIdConcreto);		
	}//Fin de la Funcion verificarEdad(campo,clave)
				

	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function crearIdConcreto(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(petHttpClave.readyState==READY_STATE_COMPLETE){
			if(petHttpClave.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = petHttpClave.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener el Numero de muestra y el Codigo de CONCRETO
					var noMuestra = parseInt(respuesta.getElementsByTagName("noMuestra").item(0).firstChild.data);
					var codigo = respuesta.getElementsByTagName("codigo").item(0).firstChild.data;
					//Aumentar en 1 el numero de muestra para crear el nuevo ID
					noMuestra++;
					
					//Crear el ID para la muestra de CONCRETO
					var clave = "";
					if(noMuestra<9)
						clave = codigo+"-0"+noMuestra;
					else if(noMuestra>=10)
						clave = codigo+"-"+noMuestra;
					//Asignar la clave a la caja de texto de Id de Muetsra del Formulario de Registrar Muestras
					document.getElementById("txt_idMuestra").value = clave;
					//Asignar el No. de Muestra
					document.getElementById("txt_noMuestra").value = noMuestra;										
				}
				else{
					//Obtener el Codigo de CONCRETO					
					var codigo = respuesta.getElementsByTagName("codigo").item(0).firstChild.data;
					
					//Crear el ID para la muestra de CONCRETO con el No. de Muestra 1
					var clave = codigo+"-01";
					
					//Asignar la clave a la caja de texto de Id de Muetsra del Formulario de Registrar Muestras
					document.getElementById("txt_idMuestra").value = clave;
					//Asignar el No. de Muestra
					document.getElementById("txt_noMuestra").value = 1;
				}				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion crearIdConcreto()
	
	
	/*Esta funcion verifica que el ID creado de la muestra no se encuentre registrado en la BD de Laboratorio */
	function verificarIdMuestra(clave){
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este 
		//archivo JavaScript
 		var url = "includes/ajax/clavesConcreto.php?clave="+clave;
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
		 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		iniciarObjetosXHR(url, "GET", verificarClaveMuestra);	
	}//Cierre de la funcion verificarIdMuestra(clave)
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function verificarClaveMuestra(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(petHttpClave.readyState==READY_STATE_COMPLETE){
			if(petHttpClave.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = petHttpClave.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener el No. de la muestra con el que se hizo la clave para indicar que existe un registro con esa clave
					var noMuestra = respuesta.getElementsByTagName("noMuestra").item(0).firstChild.data;
					var lugar = respuesta.getElementsByTagName("lugar").item(0).firstChild.data;
					
					//Notificar al Usuario
					alert("El No. de Muestra "+noMuestra+" ya se Encuentra Registrado \nen la Ubicación: "+lugar);

					//Borrar el No. de Muestra y el Id de la Muestra de la Pagina
					document.getElementById("txt_idMuestra").value = "";
					document.getElementById("txt_noMuestra").value = "";
				}
				else{
					//No hacer nada, se procede a guardar la Muestra con el ID calculado previamente
				}				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funcion verificarClaveMuestra()
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function iniciarObjetosXHR(url, metodo, funcion) {
		petHttpClave = inicializarObjetoXHR();
		if(petHttpClave) {
			petHttpClave.onreadystatechange = funcion;
			petHttpClave.open(metodo, url, true);
			petHttpClave.send(null);
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