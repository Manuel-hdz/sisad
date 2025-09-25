/**
  * Nombre del Módulo: Compras
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 24/Enero/2015                                      			
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada del detalle de una requisicion
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var nomCmb;
	var req;

	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. reqC: Requisicion donde se buscaran los detalles
	 * 2. combo: Combo en el que se van a cargar los datos
	 * 3. cont: variable en la que se guarda el numero de partida
	 * 4. aux: variable donde se guarda la requisicion en caso de que se seleccione no aplica
	 ******************************************************************************************/
	
	function cargarDetalleReq(reqC,combo,cont,aux){
		nomCmb=combo;
		req=reqC;
		conta=cont;
		aux2=aux;
		if(reqC!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboDescripcion.php?reqC="+reqC;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegadorestará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbDetalle(url, "GET", cargarDatosCmbDetalle);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Descripcion";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion
			
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbDetalle(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					if(req!="No aplica"){//Si el valor del combo de Requisicion es diferente de No aplica, declarar en el SPAN el combo donde se cargaran los detalles
						document.getElementById("datosDescripcion").innerHTML="<select name=\"txa_descripcion\" id=\"txa_descripcion\" class=\"combo_box\"><option value=\"\">Descripcion</option></select>";
					}
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "Descripcion";
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = valor;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = valor;
					}
				}
				else{
					if(req=="No aplica"){//Si no se encuentran datos, verificar si es porque se selecciono la opcion No aplica, en dicho caso sustituir el combo por una caja de Texto para ingresar la requisicion
						//Para poder escribir las comillas y declarar una funcion, se utiliza el caracter de espace \ asi, permite ingresar el Texto
						document.getElementById("datosDescripcion").innerHTML="<textarea name=\"txa_descripcion\" class=\"caja_de_texto\" rows=\"4\" cols=\"60\" />";
						//Si es la primera partida permite modificar libremente el campo de requisicion, en caso contrario el campo es bloqueado y solo muestra el dato poporcionado con aterioridad
						if(conta == 1){
							document.getElementById("datosRequisicion").innerHTML="<input name=\"txt_requisicion\" type=\"text\" class=\"caja_de_texto\" size=\"30\" maxlength=\"60\" />";
						}else{
							document.getElementById("datosRequisicion").innerHTML="<input name=\"txt_requisicion\" type=\"text\" class=\"caja_de_texto\" size=\"30\" value=\""+aux2+"\" readonly=\"true\" maxlength=\"60\" />";
						}
					}
					else{
						//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
						//Obtener la referencia del comboBox que será cargado con los datos
						objeto = document.getElementById(nomCmb);					
						//Vaciar el comboBox Antes de llenarlo
						objeto.length = 0;
						//Agregar el Primer Elemento Vacio
						objeto.length++;
						objeto.options[objeto.length-1].text = "No hay detalle sobre la requisicion";
						objeto.options[objeto.length-1].value = "";
					}
				}
				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbDetalle()
	
	function cargarDetalles(descC,requiC,combo,cant){
		nomCmb=combo;
		req=descC;
		requi=requiC
		uniCant=cant;
		if(descC!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboDescripcion.php?descC="+descC+"&requiC="+requiC;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbDetalle(url, "GET", cargarDatosDescripcion);
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosDescripcion(){		
		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					//Variables que guardan los valores recibidos
					var valor;
					var valor2;
					//Obtener la referencia del comboBox y la caja de texto que serán cargados con los datos
					objeto = document.getElementById(nomCmb);
					objeto2 = document.getElementById(uniCant);
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo y en la caja de texto
						valor = respuesta.getElementsByTagName("unidad").item(0).firstChild.data;
						valor2 = respuesta.getElementsByTagName("cant").item(0).firstChild.data;
					}
					//Se establecen los valores obtenidos
					objeto.value = valor;
					objeto2.value = valor2;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosDescripcion()
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCmbDetalle(url, metodo, funcion) {
		peticion_equipo_mtto = iniciar_xhr_req();
		if(peticion_equipo_mtto){
			peticion_equipo_mtto.onreadystatechange = funcion;
			peticion_equipo_mtto.open(metodo, url, true);
			peticion_equipo_mtto.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	