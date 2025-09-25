/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Febrero/2011
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_cmb;
	var nomCmb;
	var etqCombo;
	var opcSelected;
	
	function cargarComboMesesMttoC(anio,combo,opc){
		if(opc==1){
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto2 = document.getElementById("cmb_equipo");					
			//Vaciar el comboBox Antes de llenarlo
			objeto2.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto2.length++;
			objeto2.options[objeto2.length-1].text="Equipo";
			objeto2.options[objeto2.length-1].value="";
		}
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = combo;
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(anio!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "includes/ajax/cargarComboMesesBit.php?anio="+anio+"&opc="+opc;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbMttoC(url, "GET", procesarRespuestaCmbMttoC);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Mes";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbMttoC(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_cmb.readyState==READY_STATE_COMPLETE){
			if(peticion_http_cmb.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_cmb.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					var dato;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);	
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="Mes";
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						id = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						nombre = respuesta.getElementsByTagName("nombre"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=nombre;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=id;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=nombre;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==id)
							objeto.options[objeto.length-1].selected=true;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="No Hay Datos Registrados";
					objeto.options[objeto.length-1].value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	function cargarComboMesesMttoAceites(anio,combo){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = combo;
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(anio!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "includes/ajax/cargarComboMesesBit.php?anioBitAceite="+anio+"&opc="+opc;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbMttoC(url, "GET", procesarRespuestaCmbAceite);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Mes";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbAceite(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_cmb.readyState==READY_STATE_COMPLETE){
			if(peticion_http_cmb.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_cmb.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					var dato;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);	
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="Mes";
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						id = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						nombre = respuesta.getElementsByTagName("nombre"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=nombre;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=id;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=nombre;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==id)
							objeto.options[objeto.length-1].selected=true;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="No Hay Datos Registrados";
					objeto.options[objeto.length-1].value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCmbMttoC(url, metodo, funcion) {
		peticion_http_cmb = inicializa_xhr_cmb();
		if(peticion_http_cmb){
			peticion_http_cmb.onreadystatechange = funcion;
			peticion_http_cmb.open(metodo, url, true);
			peticion_http_cmb.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_cmb() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	