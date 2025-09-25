/**
  * Nombre del M�dulo: Recursos Humanos                                               
  * �CONCRETO LANZADO DE FRESNILLO MARCA
  * Fecha: 07/Junio/2011
  * Descripci�n: Este archivo contiene las funciones para verificar el ultimo prestamo otorgado a un trabajador y el calculo de los pagos que ser�n realizados.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petici�n HTTP para validar los Dato que se quieren guardar en la BD
	var pet_http_prestamo;


	/*Esta funci�n obtendr� los datos requeridos para validar el periodo de pagos dentro del ciclo fiscal actual*/
	function validarPeriodoPagos(){
		
		//Recuperar los datos necesarios para realizar el c�lculo de los pagos y retirar las posibles comas(,) que contengan el Monto del Prestamo la cantidad a pagar por periodo				
		var fechaRegistro = document.getElementById("txt_fechaRegistro").value;
		var periodo = document.getElementById("cmb_periodo").value;
		var cantPrestamo = document.getElementById("txt_cantidadPrestamo").value.replace(/,/g,'');
		var pagoPorPeriodo = document.getElementById("txt_pagoPorPeriodo").value.replace(/,/g,'');
				
		//Verificar si el trabajador seleccionada tiene un prestamo autorizado
		var presAutorizado = document.getElementById("hdn_prestamoAutorizado").value;
		
		//Si el RFC es diferente de vac�o y el prestamo es autorizado, proceder a calcular los pagos
		if(presAutorizado=="si" && periodo!="" && cantPrestamo!="" && pagoPorPeriodo!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicaci�n del archivo donde esta 
			//Siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/validarPagosPeriodo.php?fechaReg="+fechaRegistro+"&periodo="+periodo+"&montoPrestamo="+cantPrestamo+"&pagoPorPeriodo="+pagoPorPeriodo;
			/* A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 * Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente 
			 * al servidor y no utilizar su cache */
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			gestionarPetAsinServidor(url, "GET", verificarResultadoPagos);
		}//Cierre if(rfc!="")
		else{
			//Si el prestamo no es autorizado, proceder a notificar al usuario			
			if(presAutorizado=="no"){								
				alert("El Prestamo no esta Autorizado");
			}
		}
		
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function verificarResultadoPagos(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_http_prestamo.readyState==READY_STATE_COMPLETE){
			if(pet_http_prestamo.status==200){
				
				//Recuperar la respuesta del Servidor
				respuesta = pet_http_prestamo.responseXML;
				//Verificar si el proceso solicitado fue realizado con exito
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){										
					
					
					//Recuperar los datos del archivo XML
					var cantPagos = respuesta.getElementsByTagName("cantPagos").item(0).firstChild.data;
					var pagoPorPeriodo = respuesta.getElementsByTagName("pagoPorPeriodo").item(0).firstChild.data;
					var ultimoPago = respuesta.getElementsByTagName("ultimoPago").item(0).firstChild.data;
					var ejerFiscalExedido = respuesta.getElementsByTagName("ejerFiscalExcedido").item(0).firstChild.data;
					var ejercicioFiscal = respuesta.getElementsByTagName("ejercicioFiscal").item(0).firstChild.data;
					
					//Notificar que el Empleado no puede recibir el prestamo, debido a que el periodo de �ste excede el ejercicio fiscal actual
					if(ejerFiscalExedido=="SI"){						
						//Notificar al usuario, cuando el pr�stamo excede el ejercicio fiscal
						alert("El Periodo de Duraci�n del Pr�stamo Excede el Ejercicio Fiscal "+ejercicioFiscal);
						
						//Vaciar la caja de texto que contiene la cantidad a pagar por periodo
						document.getElementById("txt_pagoPorPeriodo").value = "";
					}//Cierre if(ejerFiscalExedido=="SI")
					else if(ejerFiscalExedido=="NO"){
						//Quitar el posible texto que contenga la etiqueta
						document.getElementById("lbl_ultimoPago").innerHTML = "";
						document.getElementById("hdn_prestamoAutorizado").value = "si";//Indicar que el Empleado es candidato para recibir un prestamo
						document.getElementById("error").style.visibility = "hidden";//Ocultar Mensaje Oculto
						
						//Armar mensaje para notificar al usuario que el empleado es candidato a recibir prestamo
						var msg = "�Pr�stamo Concedido! \n"+cantPagos+" Pagos de $ "+pagoPorPeriodo;
						//Verificar si el �ltimo pago es mayor a cero
						if(parseInt(ultimoPago.replace(/,/g,''))>0){
							msg += " �ltimo Pago de $ "+ultimoPago;
							//Colocar el dato del pago adicional
							document.getElementById("lbl_ultimoPago").innerHTML = "<br> 1 Pago de $ "+ultimoPago;
						}
						
						//Colocar la cantidad de pagos en la caja de texto correspondiente
						document.getElementById("txt_cantPagos").value = cantPagos;
						
						//Desplegar Mensaje
						alert(msg);
																		
					}//Cierre else if(ejerFiscalExedido=="NO")
										
				}//Cierre if (existe=="true")
				
			}//If if(pet_http_prestamo.status==200)
		}//If if(pet_http_prestamo.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	
	//Esta funci�n recibira el ID del Prestamo para hacer la petici�n asincrona y obtener los datos del prestamo.
	function obtenerDatosPrestamo(idPrestamo){
		if(idPrestamo!=""){
			//Antes de realizar la petici�n ocultamos el mensaje "Registrar Primer Abono" y quitar los valores de las cajas de texto de Saldo Actual, Nuevo Saldo y Total a Pagar y las Fechas
			document.getElementById("msjAgregarAbono").innerHTML = "";
			document.getElementById("txt_totalPagar").value = "";
			document.getElementById("txt_saldoActual").value = "";
			document.getElementById("txt_nuevoSaldo").value = "";
			document.getElementById("txt_fechaRegPrestamo").value = "";
			document.getElementById("txt_fechaUltimoAbono").value = "";
			
			
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicaci�n del archivo donde esta 
			//Siendo incluido este archivo JavaScript(validarPagosPeriodo.js)
			var url = "includes/ajax/validarPagosPeriodo.php?idPrestamo="+idPrestamo;
			/* A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 * Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente 
			 * al servidor y no utilizar su cache */
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			gestionarPetAsinServidor(url, "GET", procesarDatosPrestamo);			
		}
		else{
			//Antes de realizar la petici�n ocultamos el mensaje "Registrar Primer Abono" y quitar los valores de las cajas de texto de Saldo Actual, Nuevo Saldo y Total a Pagar y las Fechas
			document.getElementById("msjAgregarAbono").innerHTML = "";
			document.getElementById("txt_totalPagar").value = "";
			document.getElementById("txt_saldoActual").value = "";
			document.getElementById("txt_nuevoSaldo").value = "";
			document.getElementById("txt_fechaRegPrestamo").value = "";
			document.getElementById("txt_fechaUltimoAbono").value = "";
		}
	}//Cierre de la funci�n obtenerDatosPrestamo(idPrestamo)
	
	
	function procesarDatosPrestamo(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_http_prestamo.readyState==READY_STATE_COMPLETE){
			if(pet_http_prestamo.status==200){
				
				//Recuperar la respuesta del Servidor
				respuesta = pet_http_prestamo.responseXML;
				//Verificar si el proceso solicitado fue realizado con exito
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar Datos del prestamo
					var total = respuesta.getElementsByTagName("total").item(0).firstChild.data;
					var saldoActual = respuesta.getElementsByTagName("saldoFinal").item(0).firstChild.data;
					var fechaRegistro = respuesta.getElementsByTagName("fechaRegistro").item(0).firstChild.data;
					var fechaUltAbono = respuesta.getElementsByTagName("fechaUltimoAbono").item(0).firstChild.data;
					
					
					
					//Colocar los datos en las cajas de texto que los mostraran
					document.getElementById("txt_totalPagar").value = total;
					document.getElementById("txt_saldoActual").value = saldoActual;					
					document.getElementById("txt_fechaRegPrestamo").value = fechaRegistro;
					document.getElementById("txt_fechaUltimoAbono").value = fechaUltAbono;
					
					
				}//Cierre if (existe=="true")
				else if(existe=="false"){
					//Recuperar Datos del prestamo
					var total = respuesta.getElementsByTagName("total").item(0).firstChild.data;
					var fechaAltaPrestamo = respuesta.getElementsByTagName("fechaRegistro").item(0).firstChild.data;
					
					//Colocar los datos en las cajas de texto que los mostraran
					document.getElementById("txt_totalPagar").value = total;
					document.getElementById("txt_saldoActual").value = total;//Cuando es el primero pago, el monto total es el saldo actual
					document.getElementById("msjAgregarAbono").innerHTML = "Registrar Primer Abono";
					document.getElementById("txt_fechaRegPrestamo").value = fechaAltaPrestamo;
				}
				
			}//If if(pet_http_prestamo.status==200)
		}//If if(pet_http_prestamo.readyState==READY_STATE_COMPLETE)	
	}//Cierre de la funcion obtenerDatosPrestamo()	
	
	
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function gestionarPetAsinServidor(url, metodo, funcion) {
		pet_http_prestamo = iniciar_xhr_pagos();
		if(pet_http_prestamo) {
			pet_http_prestamo.onreadystatechange = funcion;
			pet_http_prestamo.open(metodo, url, true);
			pet_http_prestamo.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function iniciar_xhr_pagos() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}