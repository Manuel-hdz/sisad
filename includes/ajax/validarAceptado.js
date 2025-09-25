/**
  * Nombre del M�dulo: Compras                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Noviembre/2010                                      			
  * Descripci�n: Este archivo contiene las funciones para actualziar el IVA de manera Asincrona en la Base de Datos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var ruta;
	var user;

	/*Esta funci�n obtendr� el dato que se quiere validar*/
	function verificarAceptado(usuario,inicio){
		ruta=inicio;
		user=usuario;
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
	 	var url = "../../includes/ajax/validarAceptado.php?usr="+usuario+"&consulta=1";		
		//A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		//variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoUsuarios(url, "GET", procesarRespuestaUsuarios);
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoUsuarios(url, metodo, funcion) {
		peticion_http_iva = inicializa_xhr_iva();
		if(peticion_http_iva) {
			peticion_http_iva.onreadystatechange = funcion;
			peticion_http_iva.open(metodo, url, true);
			peticion_http_iva.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializa_xhr_iva() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaUsuarios(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_iva.readyState==READY_STATE_COMPLETE){
			if(peticion_http_iva.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_iva.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe!="true"){
					if(confirm("\t\t�ATENCI�N "+user+"!\nAntes de comenzar a usar este servicio sirvase de leer los siguientes puntos:\n\n1.-Los mensajes escritos a trav�s de este medio son y ser�n visibles por todos los usuarios que tengan acceso al SISAD, incluyendo la Direcci�n General.\n2.-El uso de este servicio es rastreado en todo momento.\n3.-Evite usar lenguaje vulgar.\n4.-Favor de no abusar del servicio.\n5.-Al presionar el bot�n Aceptar, confirma haber le�do y estar de acuerdo con los puntos aqui expuestos.\n\nNOTA: Evite Sanciones por uso indebido del Servicio."))
						guardarAceptado(user);
					else
						window.close();
				}
			}//If if(peticion_http_iva.status==200)
		}//If if(peticion_http_iva.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	function guardarAceptado(usuario){
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
	 	var url = "../../includes/ajax/validarAceptado.php?usr="+usuario+"&guarda=1";
		//A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		//variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoUsuarios(url, "GET", procesarRespuestaGuardarUsuario);
	}
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaGuardarUsuario(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_iva.readyState==READY_STATE_COMPLETE){
			if(peticion_http_iva.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_iva.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true")
					alert("Bienvenido "+user);
			}//If if(peticion_http_iva.status==200)
		}//If if(peticion_http_iva.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()