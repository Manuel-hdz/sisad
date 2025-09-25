/**
  * Nombre del M�dulo: Mantenimiento                                               
  * �Concreto Lanzado de Fresnillo MARCA 
  * Fecha: 09/Junio/2012                                       			
  * Descripci�n: Este archivo contiene la funci�n que carga el Cat�logo de Aceites en Mtto
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function obtenerMedicamento(medicamento){
		if (medicamento!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/cargarMedicamento.php?idMed="+medicamento;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaDatosMed(url, "GET", procesarMedicina);
		}
		else{
			document.getElementById("txt_existencia").value="";
			document.getElementById("txt_surtido").value="";
			document.getElementById("txt_total").value="";
		}
	}//Fin de la Funcion obtenerSueldo(campo)	

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarMedicina(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var cantidad = respuesta.getElementsByTagName("cantidad").item(0).firstChild.data;
					document.getElementById("txt_existencia").value=cantidad;
					document.getElementById("txt_surtido").value="";
					document.getElementById("txt_total").value="";
				}
				else{
					document.getElementById("txt_existencia").value="";
					document.getElementById("txt_surtido").value="";
					document.getElementById("txt_total").value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function obtenerMedicamentoDatos(medicamento){
		if (medicamento!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/cargarMedicamento.php?idMed="+medicamento+"&tipoCons=2";
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaDatosMed(url, "GET", procesarMedicinaDatos);
		}
		else{
			document.getElementById("txt_codigo").value="";
			document.getElementById("txa_existencia").value="";
			document.getElementById("txt_entregado").value="";
			document.getElementById("txt_total").value="";
			document.getElementById("etiquetaUnidadDespacho1").innerHTML="";
			document.getElementById("etiquetaUnidadDespacho2").innerHTML="";
		}
	}//Fin de la Funcion obtenerSueldo(campo)	

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarMedicinaDatos(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var codigo = respuesta.getElementsByTagName("codigo").item(0).firstChild.data;
					document.getElementById("txt_codigo").value=codigo;
					
					var cantidad = respuesta.getElementsByTagName("cantidad").item(0).firstChild.data;
					document.getElementById("txt_total").value=cantidad;
					
					var pres = respuesta.getElementsByTagName("tipoPres").item(0).firstChild.data;
					var cantPres = respuesta.getElementsByTagName("cantPres").item(0).firstChild.data;
					var uMedida = respuesta.getElementsByTagName("uMedida").item(0).firstChild.data;
					var uDespacho = respuesta.getElementsByTagName("uDespacho").item(0).firstChild.data;
					var restante=cantidad/cantPres;
					restante=parseInt(restante);
					var libres=cantidad%cantPres;
					if(libres==0)
						document.getElementById("txa_existencia").value=restante+" "+uMedida+"(S) CON "+cantPres+" "+uDespacho+"(S)";
					else
						document.getElementById("txa_existencia").value=restante+" "+uMedida+"(S) CON "+cantPres+" "+uDespacho+"(S) y "+libres+" "+uDespacho+"(S)";
					document.getElementById("etiquetaUnidadDespacho1").innerHTML=uDespacho+"(S)";
					document.getElementById("etiquetaUnidadDespacho2").innerHTML=uDespacho+"(S)";
				}
				else{
					document.getElementById("txt_codigo").value="";
					document.getElementById("txa_existencia").value="";
					document.getElementById("txt_total").value="";
					document.getElementById("etiquetaUnidadDespacho1").innerHTML="";
					document.getElementById("etiquetaUnidadDespacho2").innerHTML="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaDatosMed(url, metodo, funcion) {
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