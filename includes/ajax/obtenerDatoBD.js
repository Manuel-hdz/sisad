/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 15/Marzo/2011
  * Descripción: Este archivo contiene las funciones para buscar un dato especifico en la BD.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Esta varible almacenara la petición realizada al Servidor de culquiera de las funciones declaradas en este archivo
	var peticion_http_txt;	
	//Guardar el nombre de la caja de texto que guardará los datos encontrados
	var nomTxt;
	var nomTxtEmpleado;

	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene el dato que será cargado en la Caja de Texto o Elemento HTML indicado
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que dato será cargado
	 * 6. nomTxtCargar: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 ******************************************************************************************/
	function obtenerDatoBD(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomTxtCargar){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = nomTxtCargar;		
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(datoBusq!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?datoBusq="+datoBusq+"&BD="+nomBD+"&tabla="+nomTabla+"&campoBusq="+nomCampoBusq+"&campoRef="+nomCampoRef;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarRespuestaTxt);
		}		
		else{
			document.getElementById(nomTxtCargar).value = "";
		}		
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaTxt(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var dato = respuesta.getElementsByTagName("dato").item(0).firstChild.data;
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById(nomTxt).value = dato;
				}
				else{//Cuando NO se encuentre un dato la caja de texto indicada sera vaciada
					document.getElementById(nomTxt).value = "";
				}
				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. rfcBuscar: Es el dato que será buscado, en este caso se trata del RFC del Empleado
	 ******************************************************************************************/
	function obtenerNominaInterna(rfcBuscar){				
		//Obtener las Fechas correspodientes al registro de la Nomina Interna
		var fechaIni = document.getElementById("txt_fechaIniN").value;
		var fechaFin = document.getElementById("txt_fechaFinN").value;
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
		//incluido este archivo JavaScript(obtenerDatoBD.js)
		var url = "../../includes/ajax/obtenerDatoBD.php?rfcBuscar="+rfcBuscar+"&fechaIni="+fechaIni+"&fechaFin="+fechaFin+"&opcRealizar=obtenerNominaInterna";		
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
		 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
		 *servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();	
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoTxt(url, "GET", procesarDatosNominaInterna);		
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarDatosNominaInterna(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){												
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var resultado = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (resultado=="true"){					 													
					//Recuperar datos del Archivo XML					
					var rfc = respuesta.getElementsByTagName("rfc").item(0).firstChild.data;
					var fechaIni = respuesta.getElementsByTagName("fechaIni").item(0).firstChild.data;
					var fechafin = respuesta.getElementsByTagName("fechafin").item(0).firstChild.data;				
					var diaFestivo = respuesta.getElementsByTagName("diaFestivo").item(0).firstChild.data;
					var diasTrabajados = respuesta.getElementsByTagName("diasTrabajados").item(0).firstChild.data;
					var sueldoDiario = respuesta.getElementsByTagName("sueldoDiario").item(0).firstChild.data;
					var sueldoSemana = respuesta.getElementsByTagName("sueldoSemana").item(0).firstChild.data;
					var tiempoExtra = respuesta.getElementsByTagName("tiempoExtra").item(0).firstChild.data;
					var domingo = respuesta.getElementsByTagName("domingo").item(0).firstChild.data;
					var total = respuesta.getElementsByTagName("total").item(0).firstChild.data;	
					var asistencia = respuesta.getElementsByTagName("asistencia").item(0).firstChild.data;	
					
					
					//Desplegar los datos en el el Formulario
					document.getElementById("txt_fechaIniN").value = fechaIni;
					document.getElementById("txt_fechaFinN").value = fechafin;					
					document.getElementById("txt_diaFestivo").value = diaFestivo;
					document.getElementById("txt_diaTrabajado").value = diasTrabajados;
					document.getElementById("txt_sueldoDiario").value = sueldoDiario;
					document.getElementById("txt_sueldoSemanal").value = sueldoSemana;
					document.getElementById("txt_tiempoExtra").value = tiempoExtra;
					document.getElementById("txt_domingo").value = domingo;
					document.getElementById("txt_total").value = total;
					document.getElementById("txt_diaTrabajado").value = asistencia;
					//Preservar el RFC del Empleado para realizar la Actualizacion
					document.getElementById("hdn_rfc2").value = rfc;
					//Preservar las Fechas originales para actulizar el registro correspondiente junto con el RFC
					document.getElementById("hdn_fechaIni").value = fechaIni;
					document.getElementById("hdn_fechaFin").value = fechafin;
					
					//Indicar que la información obtendida será actulizada en la BD y no insertada
					document.getElementById("hdn_tipoSentencia").value = "UPDATE";
					
					//Mostrar el mensaje que indica la acción que esta realizando el Usuario
					document.getElementById("msj_agregar").style.visibility = "hidden";
					document.getElementById("msj_actualizar").style.visibility = "visible";
					
				}
				else{//Cuando no exista registro previo en la Nomina Interna, obtener el Sueldo Diario de la tabla de Empleados
				
					//Restablecer del Formulario los datos que existian previamente
					document.frm_registrarNomina.reset();
				
					//Recuperar datos del Archivo XML, Obtener el RFC del Empleado
					var rfcEmpleado = respuesta.getElementsByTagName("rfcEmpleado").item(0).firstChild.data;
					document.getElementById("hdn_rfc").value = rfcEmpleado;
					var asistencias = respuesta.getElementsByTagName("asistencia").item(0).firstChild.data;
					document.getElementById("txt_diaTrabajado").value = asistencias;
					//Guardar el Suledo Diario obtenido de la BD en una caja de texto oculta para obtener de ahi darle formato en la caja de texto txt_sueldoDiario
					obtenerDatoBD(rfcEmpleado,'bd_recursos','empleados','sueldo_diario','rfc_empleado','hdn_sueldoDiario');
					setTimeout("var sueldoDiario=parseFloat(document.getElementById('hdn_sueldoDiario').value);formatCurrency(sueldoDiario,'txt_sueldoDiario');",500);
					setTimeout("document.getElementById('txt_sueldoSemanal').value=document.getElementById('txt_sueldoDiario').value*document.getElementById('txt_diaTrabajado').value;formatCurrency(document.getElementById('txt_sueldoSemanal').value,'txt_sueldoSemanal');",501);
					setTimeout("document.getElementById('txt_total').value=parseFloat(document.getElementById('txt_sueldoSemanal').value)+parseFloat(document.getElementById('txt_diaFestivo').value)+parseFloat(document.getElementById('txt_tiempoExtra').value)+parseFloat(document.getElementById('txt_domingo').value);formatCurrency(document.getElementById('txt_total').value,'txt_total')",502);
					
					
					
					//Mostrar el mensaje que indica la acción que esta realizando el Usuario					
					document.getElementById("msj_actualizar").style.visibility = "hidden";
					document.getElementById("msj_agregar").style.visibility = "visible";
				}																
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()	
	
	
	/******************************************************************************************
	 * Esta función obtendra el consecutivo del Area que le corresponda a un trabajador asignado, Parametros:
	 * 1. nomArea: Es el nombre del Area mediante la cual se obtendra el respectivo ID
	 * 2. nomTxtCargar: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 ******************************************************************************************/
	function obtenerClaveArea(nomArea,nomTxtCargar){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = nomTxtCargar;				
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(nomArea!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?nomArea="+nomArea+"&opcRealizar=obtenerClaveArea";		
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarClaveArea);
		}		
		else{
			document.getElementById(nomTxtCargar).value = "";
		}		
	}//Fin de la function obtenerClaveArea(nomArea,nomTxtCargar)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarClaveArea(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){								
					//Recuperar datos del Archivo XML					
					var cve_area = respuesta.getElementsByTagName("claveArea").item(0).firstChild.data;
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById(nomTxt).value = cve_area;															
				}				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarClaveArea()	
	
	
	/******************************************************************************************
	 * Esta función obtendra el rfc del Empleado proporcionando el nombre del mismo
	 * 1. nomEmpleado: Es el nombre del empleadoa mediante la cual se obtendra su RFC
	 * 2. nomTxtCargar: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 ******************************************************************************************/
	function obtenerRFCEmpleado(nomEmpleado,nomTxtCargar){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = nomTxtCargar;		
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(nomEmpleado!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?nomEmpleado="+nomEmpleado+"&opcRealizar=obtenerRFCEmpleado";
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarRFCEmpleado);
		}		
		else{
			document.getElementById(nomTxtCargar).value = "";
		}		
	}//Fin de la function obtenerRFCEmpleado(nomEmpleado,nomTxtCargar){
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRFCEmpleado(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var RFC = respuesta.getElementsByTagName("RFCEmpleado").item(0).firstChild.data;
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById(nomTxt).value = RFC;
				}		
				else{
					//Vaciar  la Caja de Texto o Elemento HTML indicado en caso de que no se haya encontrado un RFC 
					document.getElementById(nomTxt).value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRFCEmpledo()
	
	/******************************************************************************************
	 * Esta función obtendra el id del Empleado proporcionando el nombre del mismo
	 * 1. nomEmpleado: Es el nombre del empleadoa mediante la cual se obtendra su id
	 * 2. nomTxtCargar: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 ******************************************************************************************/
	function obtenerIDEmpleado(nomEmpleado,nomTxtCargar){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = nomTxtCargar;		
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(nomEmpleado!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?nomEmpleado="+nomEmpleado+"&opcRealizar=obtenerIDEmpleado";
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarIDEmpleado);
		}		
		else{
			document.getElementById(nomTxtCargar).value = "";
		}		
	}//Fin de la function obtenerIDEmpleado(nomEmpleado,nomTxtCargar){
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarIDEmpleado(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var ID = respuesta.getElementsByTagName("IDEmpleado").item(0).firstChild.data;
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById(nomTxt).value = ID;
				}		
				else{
					//Vaciar  la Caja de Texto o Elemento HTML indicado en caso de que no se haya encontrado un RFC 
					document.getElementById(nomTxt).value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarIDEmpleado()
	
	/******************************************************************************************
	 * Esta función obtendra el nombre completo indicando los tres campos que lo componenen de cualquier BD
	 * 1. idRegistro: Es el Id del Registro del cual se va a obtener el Nombre completo
	 * 2. columaRef: Es el nombre de la columa que contiene el ID del Registro
	 * 3. campNombre: Nombre de la Columna que contiene el Nombre
	 * 4. campApePat: Nombre de la Columna que tiene el Apellido Paterno
	 * 5. campApeMat: Nombre de la Columna que tienen el Apellido Materno
	 * 6. nomTxtCargar: Id de la Caja de Textoi donde sera colocado el Nombre obtenido
	 * 7. nomBD: Nombre de la Base de Datos donde se va a buscar
	 * 8. nomTabla: Nombre de la tabla donde se va a buscar
	 ******************************************************************************************/
	function obtenerNombreCompleto(idRegistro,columaRef,campNombre,campApePat,campApeMat,nomTxtCargar,nomBD,nomTabla){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = nomTxtCargar;		
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(idRegistro!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?idRegistro="+idRegistro+"&columaRef="+columaRef+"&campNombre="+campNombre+"&campApePat="+campApePat;
			url += "&campApeMat="+campApeMat+"&nomBD="+nomBD+"&nomTabla="+nomTabla+"&opcRealizar=obtenerNombreCompleto";
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();				
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarNombreCompleto);
		}		
		else{
			document.getElementById(nomTxtCargar).value = "";
		}		
	}//Fin de la function obtenerRFCEmpleado(nomEmpleado,nomTxtCargar){
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarNombreCompleto(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){			
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;				
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var nombreCompleto = respuesta.getElementsByTagName("nombreCompleto").item(0).firstChild.data;
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById(nomTxt).value = nombreCompleto;
				}		
				else{
					//Vaciar  la Caja de Texto o Elemento HTML indicado en caso de que no se haya encontrado un RFC 
					document.getElementById(nomTxt).value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRFCEmpledo()
	
	
	/******************************************************************************************
	 * Esta función obtendra el saldo actual de una deducción de un Empleado
	 * 1. idDeduccion: Es el Id de la dección a la cual se le realizará un abono
	 * 2. txtCargarSaldo: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 * 3. txtCargarEmpleado: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el nombre del Empleado
	 ******************************************************************************************/
	function obtenerSaldoActual(idDeduccion,txtCargarSaldo,txtCargarEmpleado){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = txtCargarSaldo;		
		nomTxtEmpleado = txtCargarEmpleado;
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(idDeduccion!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?idDeduccion="+idDeduccion+"&opcRealizar=obtenerSaldoActual";
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarSaldoActual);
		}		
		else{
			document.getElementById(txtCargarSaldo).value = "0.00";
			document.getElementById(txtCargarEmpleado).value = "";			
		}		
	}//Fin de la function obtenerSaldoActual(idDeduccion,nomTxtCargar)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarSaldoActual(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){										
					//Recuperar datos del Archivo XML					
					var saldoFinal = respuesta.getElementsByTagName("saldoFinal").item(0).firstChild.data;
					var nombreEmpleado = respuesta.getElementsByTagName("nombreEmpleado").item(0).firstChild.data;
					var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;
					//Colocar el Saldo con formato de moneda en la caja de texto correspondiente
					formatCurrency(saldoFinal,nomTxt);					
					//Colocar el nombre completo del empleado en la caja de texto correspondiente
					document.getElementById(nomTxtEmpleado).value = nombreEmpleado;	
					
					//Colocar el estado actuar de la deduccion o prestamo
					document.getElementById("hdn_estadoCuenta").value = estado;
									
				}		
				else{
					//Vaciar  la Caja de Texto o Elemento HTML indicado en caso de que no se haya encontrado un RFC 
					document.getElementById(nomTxt).value = "0.00";
					document.getElementById(nomTxtEmpleado).value = "";
					document.getElementById("hdn_estadoCuenta").value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRFCEmpledo()
	
	
	
	/*****************************************************************************************************************
	 * Esta función obtendra el ultimo registro del Odometro/Horometro del equipo seleccionado
	 * 1. claveEquipo: Es el Id del equipo, del cual se quiere obtener el ultimo registro del Odometro/Horometro
	 * 2. txtCantMetrica: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 *****************************************************************************************************************/
	function obtenerMetricaEquipo(claveEquipo,txtCantMetrica){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = txtCantMetrica;		
			
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(claveEquipo!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/obtenerDatoBD.php?claveEquipo="+claveEquipo+"&opcRealizar=obtenerMetricaEquipo";
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarCantMetrica);
		}		
		else{
			document.getElementById(txtCantMetrica).value = "0.00";
		}		
	}//Fin de la function obtenerMetricaEquipo(claveEquipo,txtCantMetrica)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarCantMetrica(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){										
					//Recuperar datos del Archivo XML					
					var cantMetrica = respuesta.getElementsByTagName("cantMetrica").item(0).firstChild.data;
					//Colocar la Cantidad de la metrica en la Caja de Texto indicada
					formatCurrency(cantMetrica,nomTxt);							
					//Recuperar datos del Archivo XML					
					var metrica = respuesta.getElementsByTagName("metrica").item(0).firstChild.data;
					document.getElementById("cmb_metrica").value = metrica;
					
				}		
				else{
					//Vaciar la Caja de Texto o Elemento HTML indicado en caso de que no se haya encontrado un registro para el Equipo
					document.getElementById(nomTxt).value = "0.00";
					//Recuperar datos del Archivo XML					
					var metrica = respuesta.getElementsByTagName("metrica").item(0).firstChild.data;
					document.getElementById("cmb_metrica").value = metrica;
					
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarCantMetrica()
	
		
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoTxt(url, metodo, funcion) {
		peticion_http_txt = inicializa_xhr_txt();
		if(peticion_http_txt){
			peticion_http_txt.onreadystatechange = funcion;
			peticion_http_txt.open(metodo, url, true);
			peticion_http_txt.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_txt() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}