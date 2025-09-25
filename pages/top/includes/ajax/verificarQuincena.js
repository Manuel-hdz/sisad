/**
  * Nombre del Módulo: Topografía
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Junio/2011                                      			
  * Descripción: Este archivo contiene las funciones para determinar si ya existe el registro de una quincena para una Obra en Traspaleo
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_quin;
	var tipoObra;


	/* Esta funcion verifica que sean proporcionados los datos de la Quincena y mediante el Id de la Obra verifica si hay otro registro de Quincena para la Obra 
	 * Seleccionada en el Traspaleo o en la Estimación según se indique en el parametro tObra*/
	function verificarQuincena(tObra){				
	
		//Guardar el Tipo de Obra en la Variable tipoObra
		tipoObra = tObra
	
		//Revisar que el No., Mes y Año de la Quincena sean proporcionados		
		noQuincena = document.getElementById("cmb_noQuincena").value;
		mes = document.getElementById("cmb_Mes").value;
		anio = document.getElementById("cmb_Anio").value;
		
		if(noQuincena!="" && mes!="" && anio!=""){
			//Obtener el Id de la Obra
			idObra = document.getElementById("hdn_idObra").value;
			quincena = noQuincena+" "+mes+" "+anio;
			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(verificarQuincena.js)
			var url = "includes/ajax/verificarQuincena.php?idObra="+idObra+"&noQuincena="+quincena+"&tipoObra="+tipoObra;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoQuincena(url, "GET", procesarRespuestaQuincena);
		}								
	}//Fin de la Funcion verificarDatoBD(campo)
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoQuincena(url, metodo, funcion) {
		peticion_http_quin = inicializa_xhr_quincena();
		if(peticion_http_quin){
			peticion_http_quin.onreadystatechange = funcion;
			peticion_http_quin.open(metodo, url, true);
			peticion_http_quin.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_quincena() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaQuincena(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_quin.readyState==READY_STATE_COMPLETE){
			if(peticion_http_quin.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_quin.responseXML;															
				//Obtener el resultado de la validacion de los datos en la SESSION
				var resultado = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (resultado=="true"){					 					
					//Obtener el Nombre de la Obra del Formulario y mnadar un mensaje de notificacion
					nomObra = document.getElementById("txt_nombreObra").value;
					
					if(tipoObra=="TRASPALEO"){
						alert("La Obra "+nomObra+" \nYa Tiene un Registro en la Quincena Seleccionada\nConsultar Registro en la Sección de Consultar Traspaleo");																
						//Vaciar los campos de la Seccion de Registrar Traspaleo
						document.getElementById("txt_acumuladoQuincena").value = "";
						document.getElementById("txt_volumen").value = "";
					}
					else if(tipoObra=="ESTIMACION"){
						alert("La Obra "+nomObra+" \nYa Tiene un Registro en la Quincena Seleccionada\nConsultar Registro en la Sección de Consultar Estimación");																
						//Vaciar los campos de la Seccion de Registrar ESTIMACION
						document.getElementById("txt_cantidad").value = "";
						document.getElementById("txt_totalMN").value = "";
						document.getElementById("txt_totalUSD").value = "";
						document.getElementById("txt_importe").value = "";
					}
					
					
					
					//Vaciar los datos para no poder Continuar y evitar el uso de una variable Bandera en ambas Secciones de Traspaleo y Estimacion
					document.getElementById("txt_tasaCambio").value = "";
					document.getElementById("cmb_noQuincena").value = "";
					document.getElementById("cmb_Mes").value = "";
					document.getElementById("cmb_Anio").value = "";
				}		
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()