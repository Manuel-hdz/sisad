/**
  * Nombre del M�dulo: Gerencia T�cnica                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 14/Julio/2011                                       			
  * Descripci�n: Este archivo contiene la funcion que valida que el presupuesto no este incluido en otro 
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function verificarRangoValido(fecha1,fecha2,clave,destino){
		//Verificar al Ubicacion haya sido proporcionada		
		if(destino!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/verificarRangoFechas.php?fecha1="+fecha1+"&fecha2="+fecha2+"&clave="+clave+"&destino="+destino;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar 
			 *problemas con la cach� del navegador. Como cada petici�n
			 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n 
			 *directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaFecha(url, "GET", procesarFecha);	
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la 
	 *funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaFecha(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}	

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarFecha(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var caso = respuesta.getElementsByTagName("caso").item(0).firstChild.data;
					if(caso==1){//indicar el valor que ha tomado hdn_fechas, 1 significa  que las 2 fechas estan mal
						document.getElementById("hdn_fechas").value = "1";
					}//Cierre if(caso==1)
					if(caso==2){//indicar el valor que ha tomado hdn_fechas, 2 significa  que la fecha de inicio esta mal
						document.getElementById("hdn_fechas").value = "2";
					}//Cierre if(caso==2)
					if(caso==3){//indicar el valor que ha tomado hdn_fechas, 3 significa  que la fecha de fin esta mal
						document.getElementById("hdn_fechas").value = "3";						
					}//Cierre if(caso==3)
					if(caso==4){//indicar el valor que ha tomado hdn_fechas, 4 significa el rango seleccionado contiene al rango registrado en la BD
						document.getElementById("hdn_fechas").value = "4";
					}//Cierre if(caso==4)
				}
				else{	
					//indicar el valor que ha tomado hdn_fechas 1 significa que las 2 fechas estan bien
					document.getElementById("hdn_fechas").value = "0";						
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()