/**
  * Nombre del M�dulo: Recursos Humanos                                               
  * �CONCRETO LANZADO DE FRESNILLO MARCA
  * Fecha: 20/Abril/2011
  * Descripci�n: Este archivo contiene las funciones para validar las claves de los datos que ser�n registrados en la BD de manera Asincrona y 
  * de ese modo indicar al usuario cuando una clave esta repetida en la BD antes de que envie los datos para su registro.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petici�n HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_empleado;
	var nomTxtField;


	/*Esta funci�n obtendr� el dato que se quiere validar*/
	function validarEmpleado(campo){
		//Obtener el dato que se quiere validar
		var datoBusq = campo.value.toUpperCase();
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este 
		//archivo JavaScript(validarDatoBD.js)
 		var url = "includes/ajax/validarEmpleado.php?datoBusq="+datoBusq;	
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoEmpleado(url, "GET", procesarRespuestaEmpleado);
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaEmpleado(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_empleado.readyState==READY_STATE_COMPLETE){
			if(peticion_http_empleado.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_empleado.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){	
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					var apePat = respuesta.getElementsByTagName("apePat").item(0).firstChild.data;
					var apeMat = respuesta.getElementsByTagName("apeMat").item(0).firstChild.data;
					var fecha_baja= respuesta.getElementsByTagName("baja").item(0).firstChild.data;
					var obs=respuesta.getElementsByTagName("observaciones").item(0).firstChild.data;
					var aprobacion=false;
					if (obs=="")
						aprobacion=confirm("El Trabajador con los datos \nRFC: "+clave+"\nNombre: "+nombre+" "+apePat+" "+apeMat+"\nFecha de Baja: "+fecha_baja+"\n�Esta Seguro de Querer Darlo de Alta Nuevamente?");
					else
						aprobacion=confirm("El Trabajador con los datos \nRFC: "+clave+"\nNombre: "+nombre+" "+apePat+" "+apeMat+"\nFecha de Baja: "+fecha_baja+"\nObservaciones: "+obs+"\n�Esta Seguro de Querer Darlo de Alta Nuevamente?");
					//Preguntar si se esta seguro de dar de alta al empleado previamente dado de baja
					if (aprobacion){
						document.getElementById("sbt_continuar").disabled = false;
						document.getElementById("sbt_continuar").title = "Agregar los Datos Personales del Empleado";
						document.getElementById("txt_nombre").value=nombre;
						document.getElementById("txt_apePat").value=apePat;
						document.getElementById("txt_apeMat").value=apeMat;
					}
					else{
						document.getElementById("sbt_continuar").disabled = true;
						document.getElementById("sbt_continuar").title = "No se Aprob� la Contrataci�n del Aspirante";
						alert("En unos Segundos se Redireccionar� al Men� de Empleados");
						setTimeout("location.href='menu_empleados.php'",2000);
					}
				}
				else{
					document.getElementById("sbt_continuar").disabled = false;
					document.getElementById("sbt_continuar").title = "Agregar los Datos Personales del Empleado";
				}
			}//If if(peticion_http_empleado.status==200)
		}//If if(peticion_http_empleado.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	
	/*Esta funci�n validara si el Empleado es candidato a recibir un prestamo o no y obtendra el RFC de acuerdo al nombre del Empleado*/
	function validarEstadoEmpleado(nomEmpleado,nomCajaTexto){
		//Guardar el nombre de la caja de Texto que contendra el RFC del Empleado seleccionado
		nomTxtField = nomCajaTexto;		
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este 
		//archivo JavaScript(validarDatoBD.js)
 		var url = "includes/ajax/validarEmpleado.php?nomEmpleado="+nomEmpleado.toUpperCase()+"&opcRealizar=validarEstadoEmpleado";
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoEmpleado(url, "GET", procesarEstadoEmpleado);
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarEstadoEmpleado(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_empleado.readyState==READY_STATE_COMPLETE){
			if(peticion_http_empleado.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_empleado.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){	
					//Obtener datos generales del Empleado para desplegar los mensajes al Usuario
					var opcion = respuesta.getElementsByTagName("condicion").item(0).firstChild.data;
					var rfc = respuesta.getElementsByTagName("rfcEmpleado").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					
					switch(opcion){
						case "prestamoExistente":							
							//Obtener los datos del Prestamo asociado al Empleado
							var idDeduccion = respuesta.getElementsByTagName("idDeduccion").item(0).firstChild.data;
							var nomDeduccion = respuesta.getElementsByTagName("nomDeduccion").item(0).firstChild.data;
							
							document.getElementById(nomTxtField).value = rfc;
							document.getElementById("hdn_prestamoAutorizado").value = "no";						
							
							//Desplegar Mensaje Oculto
							document.getElementById("error").style.visibility = "visible";
							
							alert("El Empleado "+nombre+" \nYa Tiene un Prestamo Asignado \nId Deducci�n: "+idDeduccion+" \nPrestamo: "+nomDeduccion);							
						break;
						case "antiguedadRequerida":
							//Obtener la antiguedad del trabajador
							var antiguedad = respuesta.getElementsByTagName("antiguedad").item(0).firstChild.data;
							//Colocar el rfc del empleado en la caja de texto correspondiente
							document.getElementById(nomTxtField).value = rfc;
							document.getElementById("hdn_prestamoAutorizado").value = "no";//Indicar que el Empleado no puede recibir un prestamo							
							
							//Desplegar Mensaje Oculto
							document.getElementById("error").style.visibility = "visible";
							
							alert("El Empleado "+nombre+" \nNO Tiene la Antiguedad Requerida de 3 Meses \nAntiguedad: "+antiguedad+" Meses");							
						break;
						case "SeisMesesNoCumplidos":
							//Obtener la antiguedad del trabajador
							var antiguedad = respuesta.getElementsByTagName("antiguedad").item(0).firstChild.data;
							var fechaLiquidacion = respuesta.getElementsByTagName("fechaLiqUltPrestamo").item(0).firstChild.data;
							//Colocar el rfc del empleado en la caja de texto correspondiente
							document.getElementById(nomTxtField).value = rfc;
							document.getElementById("hdn_prestamoAutorizado").value = "no";//Indicar que el Empleado no puede recibir un prestamo
														
							//Desplegar Mensaje Oculto
							document.getElementById("error").style.visibility = "visible";
							
							//Armar mensaje para notificar al usuario
							var msg = "El Empleado "+nombre+" \nNo ha Cumplido 6 Meses Desde el �ltimo Pr�stamo Otorgado";
							msg += "\nAntiguedad: "+antiguedad+" Meses"+"\nFecha Liquidaci�n: "+fechaLiquidacion;
							//Desplegar mensaje
							alert(msg);
							
						break;
						case "candidatoPrestamo":
							//Obtener la antiguedad del trabajador
							var antiguedad = respuesta.getElementsByTagName("antiguedad").item(0).firstChild.data;
							//Colocar el rfc del empleado en la caja de texto correspondiente
							document.getElementById(nomTxtField).value = rfc;
							document.getElementById("hdn_prestamoAutorizado").value = "si";//Indicar que el Empleado puede recibir un prestamo
														
							//Ocultar Mensaje
							document.getElementById("error").style.visibility = "hidden";
							
							alert("El Empleado "+nombre+" \nEs Candidato a Recibir un Prestamo \nAntiguedad: "+antiguedad+" Meses");							
						break;
					}
				}
				else{													
					document.getElementById("hdn_prestamoAutorizado").value = "no";
					//Desplegar Mensaje Oculto
					document.getElementById("error").style.visibility = "visible";
					
					alert("El trabajador Seleccionado no Tiene Registros");
				}
			}//If if(peticion_http_empleado.status==200)
		}//If if(peticion_http_empleado.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoEmpleado(url, metodo, funcion) {
		peticion_http_empleado = inicializa_xhr_empleado();
		if(peticion_http_empleado) {
			peticion_http_empleado.onreadystatechange = funcion;
			peticion_http_empleado.open(metodo, url, true);
			peticion_http_empleado.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializa_xhr_empleado() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}