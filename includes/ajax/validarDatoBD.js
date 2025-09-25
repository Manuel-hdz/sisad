/**
  * Nombre del Módulo: Almacén                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Noviembre/2010                                      			
  * Descripción: Este archivo contiene las funciones para validar las claves de los datos que serán registrados en la BD de manera Asincrona y de ese modo indicar al usuario cuando una
  * clave esta repetida en la BD antes de que envie los datos para su registro.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petición HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_val;


	/*Esta función obtendrá el dato que se quiere validar*/
	function verificarDatoBD(campo,BD,nomTabla,campoClave,campoNombre){
		//Obtener el dato que se quiere validar
		var datoBusq = campo.value.toUpperCase();
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
 		var url = "../../includes/ajax/validarDatoBD.php?datoBusq="+datoBusq+"&BD="+BD+"&nomTabla="+nomTabla+"&campoClave="+campoClave+"&campoNombre="+campoNombre;	
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
		 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoVal(url, "GET", procesarRespuestaVal);
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaVal(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_val.readyState==READY_STATE_COMPLETE){
			if(peticion_http_val.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_val.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					document.getElementById("error").style.visibility = "visible";		
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					//Identificar cuando se trata de una requisicion asociada a una PEDIDO y cambiar el Mensaje
					if(nombre.length==10 && nombre.substring(0,3)=="PED"){
						var matNoPedido=respuesta.getElementsByTagName("matNoPedido").item(0).firstChild.data;
						var matPedido=respuesta.getElementsByTagName("matPedido").item(0).firstChild.data;
						//Si el material Pedido es mayor a 0 y el Material NO pedido es igual a 0, mostrar la alerta
						if (matPedido>0 && matNoPedido==0)
							alert("La Requisición "+clave+" esta Asociada al Pedido "+nombre);
						if(matPedido>0 && matNoPedido>0)
							alert("La Requisición "+clave+" tiene "+matPedido+" Material(es) Pedido(s) y "+matNoPedido+" Material(es) NO Pedido(s)");
					}
					else
						alert("La Clave "+clave+" Esta Asignada a "+nombre);																				
					document.getElementById("hdn_claveValida").value = "no";
				}
				else{
					document.getElementById("error").style.visibility = "hidden";				
					document.getElementById("hdn_claveValida").value = "si";
				}
			}//If if(peticion_http_val.status==200)
		}//If if(peticion_http_val.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	
	/*Esta funcion se encarga de hacer la peticion asinrona al servidor para saber si el Vale indicado ya fue registrado con anterioridad*/
	function verificarIdVale(campo,idEquipo){
		//Obtener el dato que se quiere validar
		var datoBusq = campo.value;
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
 		var url = "../../includes/ajax/validarDatoBD.php?datoBusq="+datoBusq+"&idEquipo="+idEquipo+"&opcRealizar=validarVale";		
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
		 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoVal(url, "GET", procesarRespuestaValVale);
		
	}
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaValVale(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_val.readyState==READY_STATE_COMPLETE){
			if(peticion_http_val.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_val.responseXML;
				
				
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					document.getElementById("error").style.visibility = "visible";		
					
					//Obtener los datos para notificar al Usuario
					var idVale = respuesta.getElementsByTagName("idVale").item(0).firstChild.data;
					var idEquipo = respuesta.getElementsByTagName("idEquipo").item(0).firstChild.data;
					var idBitacora = respuesta.getElementsByTagName("idBitacora").item(0).firstChild.data;					
					//Notificar al Usuario
					alert("El Vale "+idVale+" Ya ha Sido Registrado para el Equipo "+idEquipo+"\nSegún Registro en la Bitacora No. "+idBitacora);
					//Indicar que el Id del Vale ya esta registrado
					document.getElementById("hdn_claveValida").value = "no";
				}
				else{
					document.getElementById("error").style.visibility = "hidden";				
					document.getElementById("hdn_claveValida").value = "si";
				}																
				
				
			}//If if(peticion_http_val.status==200)
		}//If if(peticion_http_val.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	/*Esta funcion se encarga de hacer la peticion asinrona al servidor para saber si la norma y el material indicado ya fueron registrados con anterioridad en el catalogo de Normas en Laboratorio*/
	function verificarNorma(campoNorma,campoMaterial){
		//Obtener los dato que se quieren validar
		var datoBusq1 = campoNorma.value;
		var datoBusq2 = campoMaterial.value;
		if(datoBusq1!=""&&datoBusq2!=""){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "../../includes/ajax/validarDatoBD.php?datoBusq1="+datoBusq1+"&datoBusq2="+datoBusq2+"&opcRealizar=validarNorma";		
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoVal(url, "GET", procesarRespuestaValNorma);
		}
		
	}
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaValNorma(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_val.readyState==READY_STATE_COMPLETE){
			if(peticion_http_val.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_val.responseXML;
				
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					
					document.getElementById("error").style.visibility = "visible";		
					
					//Obtener los datos para notificar al Usuario
					var idMaterial = respuesta.getElementsByTagName("idMaterial").item(0).firstChild.data;
					var norma = respuesta.getElementsByTagName("norma").item(0).firstChild.data;
					//Notificar al Usuario
					alert("El Material con clave "+idMaterial+" Ya ha Sido Asociado a la Norma  "+norma+"\nSegún Registro del Catalogo de Normas");
					//Indicar que el Id del Vale ya esta registrado
					document.getElementById("hdn_claveValida").value = "no";
				}
				else{
					document.getElementById("error").style.visibility = "hidden";				
					document.getElementById("hdn_claveValida").value = "si";
				}																
				
				
			}//If if(peticion_http_val.status==200)
		}//If if(peticion_http_val.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaValNorma()
	
	
							
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoVal(url, metodo, funcion) {
		peticion_http_val = inicializa_xhr_val();
		if(peticion_http_val) {
			peticion_http_val.onreadystatechange = funcion;
			peticion_http_val.open(metodo, url, true);
			peticion_http_val.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_val() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}