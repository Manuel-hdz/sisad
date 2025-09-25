/**
  * Nombre del Módulo: Compras
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Febrero/2010                                      			
  * Descripción: Este archivo contiene las funciones para determinar cuando una requisicion esta registrada y no ha sido pedida, de tal manera que se debe
  * redireccionar a la pantalla donde se colocan los precios de los materiales de la requisicion ingresada.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_req;
	var nomBD;


	/*Esta función obtendrá el dato que se quiere validar*/
	function verificarEstado(campo){		
		//Obtener el dato que se quiere validar
		var inicialesReq = campo.value.substring(0,3);
		inicialesReq = inicialesReq.toUpperCase();
		//Identificar la BD de acuerdo a los 3 primeros digitos que componene el ID de la Requisicion			
		switch(inicialesReq){
			case "ALM":
				nomBD = "bd_almacen";
			break;
			case "GER":
				nomBD = "bd_gerencia";
			break;
			case "REC":
				nomBD = "bd_recursos";
			break;
			case "PRO":
				nomBD = "bd_produccion";
			break;
			case "ASE":
				nomBD = "bd_aseguramiento";
			break;
			case "DES":
				nomBD = "bd_desarrollo";
			break;
			case "MAN":
				nomBD = "bd_mantenimiento";
			break;
			case "MAC":
				nomBD = "bd_mantenimiento";
			break;
			case "MAM":
				nomBD = "bd_mantenimiento";
			break;
			case "TOP":
				nomBD = "bd_topografia";
			break;
			case "LAB":
				nomBD = "bd_laboratorio";
			break;
			case "SEG":
				nomBD = "bd_seguridad";
			break;
			case "PAI":
				nomBD = "bd_paileria";
			break;
			case "MAE":
				nomBD = "bd_mantenimientoE";
			break;
			case "USO":
				nomBD = "bd_clinica";
			break;
			case "MAI":
				nomBD = "bd_comaro";
			break;
			default:
				nomBD = "BD_noEncontrada";
			break;
		}
		
		if(nomBD!="BD_noEncontrada"){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(validarEstado.js)
			var url = "includes/ajax/validarEstado.php?datoBusq="+campo.value+"&BD="+nomBD;		
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoReq(url, "GET", procesarRespuestaReq);
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoReq(url, metodo, funcion) {
		peticion_http_req = inicializa_xhr_req();
		if(peticion_http_req) {
			peticion_http_req.onreadystatechange = funcion;
			peticion_http_req.open(metodo, url, true);
			peticion_http_req.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaReq(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_req.readyState==READY_STATE_COMPLETE){
			if(peticion_http_req.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_req.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;
					var depart = respuesta.getElementsByTagName("depart").item(0).firstChild.data;
					//Si la Requisición ya fue pedida o entregada, no hacer NADA
					if(estado!="PEDIDO" && estado!="ENTREGADA"){
						//Colocar los datos necesarios para abrir la Pantalla de completar los datos de la requisicion (ingresar precios)					
						document.getElementById("hdn_numero").value= clave;
						document.getElementById("hdn_bd").value= nomBD;
						document.getElementById("hdn_estado").value= estado;
						//Redireccionar a la pagina donde se colocara los precios de los materiales de la Requisición
						document.frm_datosRequisicionPedido.action="frm_consultarRequisiciones.php?nomBD="+nomBD+"&depart="+depart+"&clave="+clave+"&bus=1";
						document.frm_datosRequisicionPedido.submit();
					}
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()