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


	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 6. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 7. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 8. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado;
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoRef="+nomCampoRef;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmb);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmb(){				
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
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
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
	
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 6. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 7. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 8. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 * 9. nomCampoOrd: Nombre del campo por medio del cual se desea ordenar el combo.
	 ******************************************************************************************/
	function cargarComboOrdenado(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado,nomCampoOrd){
		//alert("Dato a Buscar: "+datoBusq+"\nDataBase: "+nomBD+"\nTabla: "+nomTabla+"\nCampo Busqueda: "+nomCampoBusq+"\nCampo Referencia: "+nomCampoRef+"\nCombo a Cargar: "+nomCmbCargar+"\nEtiqueta: "+etiqCombo+"\nValor Seleccionado: "+valSeleccionado+"\nCampo Criterio de Ordenacion: "+nomCampoOrd);
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado;
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla;
			url += "&campoBusq="+nomCampoBusq+"&campoRef="+nomCampoRef+"&nomCampoOrd="+nomCampoOrd;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmb);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)						
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoId: Nombre del campo en la tabla que contiene el ID del registro para agregarlo a la propidad value del ComboBox
	 * 6. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 7. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 8. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 9. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarComboConId(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoId,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoId="+nomCampoId+"&campoRef="+nomCampoRef;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmbId);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarComboConId(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoId,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbId(){				
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
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada una de las Id que se colocaran en el value de cada opcion del ComboBox
						valorId = respuesta.getElementsByTagName("datoId"+(i+1)).item(0).firstChild.data;						
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value=valorId;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valorId;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valorId)
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
	}//Fin de la Funcion procesarRespuestaCmbId()
	
	function cargarComboConId2(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoId,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoId="+nomCampoId+"&campoRef="+nomCampoRef;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmbId2);
		}
	}//Fin de la Funcion cargarComboConId(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoId,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbId2(){
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
					objeto2 = document.getElementById("hdn_valorCmbAplicador");
					
					for(var i=0;i<tam;i++){												
						//Obtener cada una de las Id que se colocaran en el value de cada opcion del ComboBox
						valorId = respuesta.getElementsByTagName("datoId"+(i+1)).item(0).firstChild.data;						
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.value = valorId;
						objeto2.value = valor;
					}
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaCmbId()
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 6. nomCampoEspecifico: Campo de la tabla que indica el estado de activo o inactivo
	 * 7. nomCampoRefEsp: Valor del registro que indica activo o inactivo
	 * 8. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 9. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 10. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarComboEspecifico(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCampoEspecifico,nomCampoRefEsp,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoRef="+nomCampoRef+"&nomCampoEspecifico="+nomCampoEspecifico+"&nomCampoRefEsp="+nomCampoRefEsp;		
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmbEspecifico);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbEspecifico(){				
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
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
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
	
	
	
	
	
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 6. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 7. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 8. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarComboDeUnaCadena(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoRef="+nomCampoRef;		
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmbCadena);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbCadena(){				
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
					//Obtener las aplicaciones como una cadena
					var aplicaciones = respuesta.getElementsByTagName("dato"+tam).item(0).firstChild.data;
					//Separar las Aplicaciones
					var apps = aplicaciones.split(", ");
										
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<apps.length;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = apps[i];
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
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
	
	
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoId: Nombre del campo en la tabla que contiene el ID del registro para agregarlo a la propidad value del ComboBox
	 * 6. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 7. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 8. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 9. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarComboIdNombreOrd(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoId,nomCampoRef,nomCmbCargar,etiqCombo,nomCampoOrd,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){	
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoId="+nomCampoId+"&campoRef="+nomCampoRef;
			url += "&ord="+nomCampoOrd;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmbId);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarComboConId(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoId,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbId(){				
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
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada una de las Id que se colocaran en el value de cada opcion del ComboBox
						valorId = respuesta.getElementsByTagName("datoId"+(i+1)).item(0).firstChild.data;						
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value=valorId;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valorId;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valorId)
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
	}//Fin de la Funcion procesarRespuestaCmbId()
	
	
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 6. nomCampoEspecifico: Campo de la tabla que indica el estado de activo o inactivo
	 * 7. nomCampoRef1: Concicion 1
	 * 7. nomCampoEspecifico2: Campo de la Tabla para el segundo parametro
	 * 8. nomCampoRef2: Concicion 2
	 * 9. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 10. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 11. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarComboBiCondicional(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCampoEspecifico,nomCampoRef1,nomCampoEspecifico2,nomCampoRef2,nomCmbCargar,etiqCombo,valSeleccionado){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Guardar la opciones seleccionada del Usuario
		opcSelected = valSeleccionado
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarCombo.js)
			var url = "../../includes/ajax/cargarCombo.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoRef="+nomCampoRef+"&nomCampoEspecifico="+nomCampoEspecifico+"&nomCampoRef1="+nomCampoRef1+"&nomCampoRef2="+nomCampoRef2+"&nomCampoEspecifico2="+nomCampoEspecifico2;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmb(url, "GET", procesarRespuestaCmbBiCondicional);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbBiCondicional(){				
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
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
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
	
	
	
	function cargarComboCompleto(nomBD,nomTabla,campoId,nomCampoBusq,nomCmbCargar,etiqCombo){
		//Guardar el nombre del comboBox que será cargado con los datos
		nomCmb = nomCmbCargar;
		//Guardar la etiqueta del comboBox que será cargado con los datos
		etqCombo = etiqCombo;
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
		//incluido este archivo JavaScript(cargarCombo.js)
		var url = "../../includes/ajax/cargarCombo.php?BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoIdCombo="+campoId+"&opcCombo=1";
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
		 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
		 *servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();	
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoCmb(url, "GET", procesarRespuestaCmbCompleto);
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmbCompleto(){				
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
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;
						id = respuesta.getElementsByTagName("datoId"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=id;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=id+" - "+valor;
						//Indicar cual valor aparecera preseleccionado
						if(opcSelected==valor)
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
	function cargaContenidoCmb(url, metodo, funcion) {
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
	
	