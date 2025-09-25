/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 12/04/2012
  * Descripción: Este archivo se encarga de obtener datos de los proveedores registrados en Compras
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var pet_datos_proveedor;


	/*Esta función crea la Url para hacer la petición asincrona al servidor para obtener la dirección del proveedor indicado*/
	function obtenerDireccion(razonSocial){
		
		if(razonSocial!="" && razonSocial!="NVO_PROVEEDOR"){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(datosProveedor.js)
			var url = "includes/ajax/datosProveedor.php?razonSocial="+razonSocial;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoProveedor(url, "GET", procObtenerDireccion);
		}
		
	}//Fin de la Funcion cargarEquiposFamilia(familia,area,nomCmbCargar,etiqCombo,valSeleccionado)
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procObtenerDireccion(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_datos_proveedor.readyState==READY_STATE_COMPLETE){
			if(pet_datos_proveedor.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = pet_datos_proveedor.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;					
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var direccion = respuesta.getElementsByTagName("direccion").item(0).firstChild.data;
					
					//Colocar la Dirección obtenida en la caja de texto de 'txt_direccion'
					document.getElementById("txt_direccion").value = direccion;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procObtenerDireccion()
	
	
		
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoProveedor(url, metodo, funcion) {
		pet_datos_proveedor = iniciar_xmlHttpReq();
		if(pet_datos_proveedor){
			pet_datos_proveedor.onreadystatechange = funcion;
			pet_datos_proveedor.open(metodo, url, true);
			pet_datos_proveedor.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function iniciar_xmlHttpReq() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	