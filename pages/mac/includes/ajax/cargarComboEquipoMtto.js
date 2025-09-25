/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 31/Enero/2012                                      			
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada de los Equipos de Mantenimiento
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var nomCmb;
	var etqCombo;
	var opcSelected;


	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. familia: nombre de la familia, de la cual se cargaran los equipos
	 * 2. area: Area (Mina o Concreto), ya que la misma familia puede existir en las dos áreas
	 * 3. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos consultados
	 * 4. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 5. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarEquiposFamilia(familia,area,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado;
		
		
		//Si no ha sido seleccionada ninguna familia, vaciar el combo que con
		if(familia!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "includes/ajax/cargarComboEquipoMtto.php?familia="+familia+"&area="+area;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbMtto(url, "GET", cargarDatosCmbEquipoMtto);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarEquiposFamilia(familia,area,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbEquipoMtto(){				
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
					
					var valor;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = etqCombo;
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("idEquipo"+(i+1)).item(0).firstChild.data;
						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
							objeto.options[objeto.length-1].selected = true;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "No Hay Equipos Registrados";
					objeto.options[objeto.length-1].value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbEquipoMtto()
	
	function cargarFamiliaEquipos(area,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado;
		
		
		//Si no ha sido seleccionada ninguna familia, vaciar el combo que con
		if(area!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "includes/ajax/cargarComboEquipoMtto.php?area="+area+"&opcion='1'";	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbMtto(url, "GET", cargarDatosCmbFamilia);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarFamiliaEquipos(area,nomCmbCargar,etiqCombo,valSeleccionado)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbFamilia(){				
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
					
					var valor;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = etqCombo;
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("familia"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
							objeto.options[objeto.length-1].selected = true;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "No Hay Famlias Registradas";
					objeto.options[objeto.length-1].value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbFamilia()
		
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCmbMtto(url, metodo, funcion) {
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
	
	