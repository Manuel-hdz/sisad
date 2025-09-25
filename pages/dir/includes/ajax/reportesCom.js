/**
  * Nombre del Módulo: Dirección General                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Febrero/2012
  * Descripción: Este archivo muestra los reportes del Área de Compras
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/***************************************************************************************************************************************/
	/*********************************************************REPORTE COMPRAS***************************************************************/
	/***************************************************************************************************************************************/

	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteCompras(noRep,fechaI,fechaF,orden){
		var fechaValida=validarFechas(fechaI,fechaF);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultadoGrafico").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesCom.php?rep="+noRep+"&fechaI="+fechaI+"&fechaF="+fechaF+"&orden="+orden;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteCompras);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteCompras(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var cont=1;
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					var cantidad=respuesta.getElementsByTagName("cant").item(0).firstChild.data;
					var texto="";
					do{
						if (cantidad==1)
							var grafica = respuesta.getElementsByTagName("grafica1").item(0).firstChild.data;
						else
							var grafica = respuesta.getElementsByTagName("grafica"+cont).item(0).firstChild.data;
						if(cont==1)
							document.getElementById("resultadoGrafico").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repCompras]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
						else
							texto+="<a href='tmp/"+grafica+"' rel='lightbox[repCompras]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
						cont++;
					}while(cont<=cantidad);
					document.getElementById("imagenes").innerHTML = texto;				}
				else{
					document.getElementById("resultadoGrafico").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/***************************************************************************************************************************************/
	/******************************************************REPORTE VENTAS*******************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteVentas(noRep,fechaI,fechaF,orden){
		var fechaValida=validarFechas(fechaI,fechaF);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultadoGrafico").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesCom.php?rep="+noRep+"&fechaI="+fechaI+"&fechaF="+fechaF+"&orden="+orden;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteVentas);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteVentas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var cont=1;
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					var cantidad=respuesta.getElementsByTagName("cant").item(0).firstChild.data;
					var texto="";
					do{
						if (cantidad==1)
							var grafica = respuesta.getElementsByTagName("grafica1").item(0).firstChild.data;
						else
							var grafica = respuesta.getElementsByTagName("grafica"+cont).item(0).firstChild.data;
						if(cont==1)
							document.getElementById("resultadoGrafico").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repVentas]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
						else
							texto+="<a href='tmp/"+grafica+"' rel='lightbox[repVentas]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
						cont++;
					}while(cont<=cantidad);
					document.getElementById("imagenes").innerHTML = texto;				}
				else{
					document.getElementById("resultadoGrafico").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/***************************************************************************************************************************************/
	/************************************************REPORTE COMPRAS VS VENTAS**************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteComprasVentas(noRep,fechaI,fechaF){
		var fechaValida=validarFechas(fechaI,fechaF);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultadoGrafico").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesCom.php?rep="+noRep+"&fechaI="+fechaI+"&fechaF="+fechaF;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteComprasVentas);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteComprasVentas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var cont=1;
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					var grafica = respuesta.getElementsByTagName("grafica").item(0).firstChild.data;
					document.getElementById("resultadoGrafico").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
				}
				else{
					document.getElementById("resultadoGrafico").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/***************************************************************************************************************************************/
	/***************************************************REPORTE CAJA CHICA******************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd*/
	function mostrarReporteCajaChica(noRep,fechaI,fechaF,orden){
		var fechaValida=validarFechas(fechaI,fechaF);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultadoGrafico").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesCom.php?rep="+noRep+"&fechaI="+fechaI+"&fechaF="+fechaF+"&orden="+orden;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteCajaChica);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteCajaChica(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var cont=1;
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					var cantidad=respuesta.getElementsByTagName("cant").item(0).firstChild.data;
					var texto="";
					
					var fechaI= respuesta.getElementsByTagName("fechaI").item(0).firstChild.data;
					var fechaF=respuesta.getElementsByTagName("fechaF").item(0).firstChild.data;
					var combo=respuesta.getElementsByTagName("combo").item(0).firstChild.data;
					do{
						if (cantidad==1)
							var grafica = respuesta.getElementsByTagName("grafica1").item(0).firstChild.data;
						else
							var grafica = respuesta.getElementsByTagName("grafica"+cont).item(0).firstChild.data;
						if(cont==1)
							document.getElementById("resultadoGrafico").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repCajaChica]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
						else
							texto+="<a href='tmp/"+grafica+"' rel='lightbox[repVentas]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
						cont++;
					}while(cont<=cantidad);
					document.getElementById("imagenes").innerHTML = texto;
					document.getElementById("hdn_fechaI").value = fechaI;
					document.getElementById("hdn_fechaF").value = fechaF;
					document.getElementById("hdn_combo").value = combo;
					document.getElementById("consultarDetalle").style.visibility='visible';
				}
				else{
					document.getElementById("resultadoGrafico").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarReporteCompMina()
	
	/***************************************************************************************************************************************/
	/******************************************************FUNCIONES GENERALES**************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function mostrarDatos(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	/*Funcion que valida las Fechas de inicio y fin para verificar que la de inicio no sea mayor a la de fin*/
	function validarFechas(fechaIni,fechaFin){
		//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var iniDia=fechaIni.substr(0,2);
		var iniMes=fechaIni.substr(3,2);
		var iniAnio=fechaIni.substr(6,4);
		
		//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var finDia=fechaFin.substr(0,2);
		var finMes=fechaFin.substr(3,2);
		var finAnio=fechaFin.substr(6,4);
		
		//Unir los datos para crear la cadena de Fecha leida por Javascript
		var fechaI=iniMes+"/"+iniDia+"/"+iniAnio;
		var fechaF=finMes+"/"+finDia+"/"+finAnio;
		
		//Convertir la cadena a formato valido para JS
		fechaI=new Date(fechaI);
		fechaF=new Date(fechaF);
	
		//Verificar que el año de Fin sea mayor al de Inicio
		if(fechaI>fechaF){
			alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
			return false;
		}
		else
			return true;
	}