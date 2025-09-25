/**
  * Nombre del M�dulo: Compras
  * �CONCRETO LANZADO DE FRESNILLO MARCA
  * Fecha: 27/Enero/2015                                      			
  * Descripci�n: Este archivo se encarga de llenar un comboBox con la informaci�n solicitada sobre un control de costos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var fecha_ini;
	var fecha_fin;
	var nomina;
	
	function cargarTxtFechas(nominaC,fechaI,fechaF,bd){
		fecha_fin=fechaF;
		fecha_ini=fechaI;
		nomina=nominaC;
		if(nominaC!=""){			
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarFechasTxt.php?nominaC="+nominaC+"&bd="+bd;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegadorestar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoFechas(url, "GET", cargarDatosFechas);
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function cargarDatosFechas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var fecI;
					var fecF;
					var area;
					//Obtener la referencia de los elementos que seran cargados con los datos
					objeto = document.getElementById(fecha_ini);	
					objeto2 = document.getElementById(fecha_fin);
					objeto3 = document.getElementById("hdn_area");
										
					//Recorrer la respuesta XML para colocar los valores
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que ser�n cargados
						fecI = respuesta.getElementsByTagName("fechaI"+(i+1)).item(0).firstChild.data;
						fecF = respuesta.getElementsByTagName("fechaF"+(i+1)).item(0).firstChild.data;
						area = respuesta.getElementsByTagName("area"+(i+1)).item(0).firstChild.data;
						
						//Agregar el valor del atributo value
						objeto.value = fecI;
						objeto2.value = fecF;
						objeto3.value = area;
					}
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosFechas()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoFechas(url, metodo, funcion) {
		peticion_equipo_mtto = iniciar_xhr_req();
		if(peticion_equipo_mtto){
			peticion_equipo_mtto.onreadystatechange = funcion;
			peticion_equipo_mtto.open(metodo, url, true);
			peticion_equipo_mtto.send(null);
		}
	}
	
	function cargarCmbNomina(nominaC,fechaI,fechaF,bd,area,stat){
		nomCmb=nominaC;
		fecha_fin=fechaF;
		fecha_ini=fechaI;
		if(area!=""){			
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarFechasTxt.php?area="+area+"&bd="+bd+"&fecha_fin="+fecha_fin+"&fecha_ini="+fecha_ini+"&stat="+stat;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegadorestar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoFechas(url, "GET", cargarDatosCmbNomina);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que ser� cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="N�mina";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function cargarDatosCmbNomina(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					var texto;
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "N�mina";
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que ser�n cargados en el Combo
						valor = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tama�o del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = texto;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = texto;
					}
				}
				else{
					//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "No hay N�minas entre las fechas seleccionadas";
					objeto.options[objeto.length-1].value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbNomina()
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	function cargarCmbBono(bonoC,fechaI,fechaF){
		nomCmb=bonoC;
		fecha_fin=fechaF;
		fecha_ini=fechaI;
		if(nomCmb!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarFechasTxt.php?fecha_fin="+fecha_fin+"&fecha_ini="+fecha_ini+"&bono=1";
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegadorestar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoFechas(url, "GET", cargarDatosCmbBono);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que ser� cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Bono Productividad";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function cargarDatosCmbBono(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					var texto;
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "Bono Productividad";
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que ser�n cargados en el Combo
						valor = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tama�o del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = texto;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = texto;
					}
				}
				else{
					//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "No hay Bonos entre las fechas seleccionadas";
					objeto.options[objeto.length-1].value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbNomina()