/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 03/Marzo/2011                                      			
  * Descripción: Este archivo contiene las funciones para determinar si la Gama recien creada cuenta con toda la información necesaria para ser guardada en la Base de Datos.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_gama;
	var pagina;

	/**/
	function verficarSistemasGama(paginaDestino,nomArreglo){						
		//Guardar la pagina a la cual se redireccionará
		pagina = paginaDestino;		
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
		//incluido este archivo JavaScript(verificarGama.js)
		var url = "includes/ajax/verificarGama.php?nomArr="+nomArreglo;			
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
		 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
		 *servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoGama(url, "GET", procesarRespuestaGama);
	}//Fin de la Funcion verificarDatoBD(campo)
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoGama(url, metodo, funcion) {
		peticion_http_gama = inicializa_xhr_gama();
		if(peticion_http_gama){
			peticion_http_gama.onreadystatechange = funcion;
			peticion_http_gama.open(metodo, url, true);
			peticion_http_gama.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_gama() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaGama(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_gama.readyState==READY_STATE_COMPLETE){
			if(peticion_http_gama.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_gama.responseXML;															
				//Obtener el resultado de la validacion de los datos en la SESSION
				var resultado = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (resultado=="true"){					 					
					//Redireccionar a la pagina donde serán guardados los datos de la nueva Gama
					location.href = pagina+"?btn_guardarGama=GuardarGama";
				}
				else{//Cuando existan mensajes de datos faltantes mostrarselos al usuario
					//Obtener la cantidad de mensajes encontrados
					var cant = respuesta.getElementsByTagName("cantMensajes").item(0).firstChild.data;				
					var mensajes = "Se Encontraron las Siguientes Observaciones: \n";
					for(var i=0;i<cant;i++){
						mensajes += (i+1)+".- "+respuesta.getElementsByTagName("msg"+i).item(0).firstChild.data+"\n";				
					}
					alert(mensajes);
				}																				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()