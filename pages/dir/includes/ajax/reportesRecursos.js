/**
  * Nombre del Módulo: Dirección General                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 01/Marzo/2012
  * Descripción: Este archivo contiene las operaciones de Reportes de Recursos Humanos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;

	/************************************************************************************************************/
	/**********************************************REPORTE DE KARDEX*********************************************/
	/************************************************************************************************************/
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteKardex(noRep,fechaIni,fechaFin,area){
		var fechaValida=validarFechaRep(fechaIni,fechaFin);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultado").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesRecursos.php?noRep="+noRep+"&fechaI="+fechaIni+"&fechaF="+fechaFin+"&area="+area;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteKardex);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteKardex(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					var grafica = respuesta.getElementsByTagName("grafica").item(0).firstChild.data;
					document.getElementById("resultado").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repKardex]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
				}
				else{
					document.getElementById("resultado").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()

	/************************************************************************************************************/
	/**********************************************REPORTE DE HISTORIAL******************************************/
	/************************************************************************************************************/
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteHistorial(noRep,fechaIni,fechaFin,area){
		var fechaValida=validarFechaRep(fechaIni,fechaFin);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultado").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesRecursos.php?noRep="+noRep+"&fechaI="+fechaIni+"&fechaF="+fechaFin+"&area="+area;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteHistorial);
			//Asignar los resultados
			document.getElementById("hdn_fechaI").value=fechaIni;
			document.getElementById("hdn_fechaF").value=fechaFin;
			document.getElementById("hdn_combo").value=area;
		}
		else{
			//Asignar los resultados
			document.getElementById("hdn_fechaI").value="";
			document.getElementById("hdn_fechaF").value="";
			document.getElementById("hdn_combo").value="";
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteHistorial(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					var grafica = respuesta.getElementsByTagName("grafica").item(0).firstChild.data;
					document.getElementById("resultado").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repKardex]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
					//Mostrar el icono de Detalle
					document.getElementById("consultarDetalle").style.visibility="visible";
				}
				else{
					document.getElementById("resultado").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
					//Ocultar el icono de Detalle
					document.getElementById("consultarDetalle").style.visibility="hidden";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()

	/************************************************************************************************************/
	/***************************************REPORTE DE BOLSA DE TRABAJO******************************************/
	/************************************************************************************************************/
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteBolsaTrabajo(noRep,fechaIni,fechaFin,area){
		var fechaValida=validarFechaRep(fechaIni,fechaFin);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultado").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesRecursos.php?noRep="+noRep+"&fechaI="+fechaIni+"&fechaF="+fechaFin+"&area="+area;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteBolsaTrabajo);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteBolsaTrabajo(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var tabla=respuesta.getElementsByTagName("tabla").item(0).firstChild.data;
					//Se remplazan todas las apariciones del caracter separador por el caracter <
					var tablaMod=tabla.replace(/¬/g,"<");
					document.getElementById("resultado").style.overflow='scroll';
					document.getElementById("resultado").innerHTML = tablaMod;
				}
				else{
					document.getElementById("resultado").style.overflow='hidden';
					document.getElementById("resultado").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/************************************************************************************************************/
	/******************************************REPORTE DE PRESTAMOS**********************************************/
	/************************************************************************************************************/
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReportePrestamos(noRep,fechaIni,fechaFin,area){
		var fechaValida=validarFechaRep(fechaIni,fechaFin);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultado").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesRecursos.php?noRep="+noRep+"&fechaI="+fechaIni+"&fechaF="+fechaFin+"&area="+area;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReportePrestamos);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReportePrestamos(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var tabla=respuesta.getElementsByTagName("tabla").item(0).firstChild.data;
					var grafica=respuesta.getElementsByTagName("grafica").item(0).firstChild.data;
					//Se remplazan todas las apariciones del caracter separador por el caracter <
					var tablaMod=tabla.replace(/¬/g,"<");
					document.getElementById("resultado").style.overflow='scroll';
					document.getElementById("resultado").innerHTML = tablaMod+"<p align='center'><a href='tmp/"+grafica+"' rel='lightbox' title='Comparativo Gráfico'><img src='images/grafica.png' title='Ver Comparativo entre Prestado y Pagado' border=0/></a></p>";
				}
				else{
					document.getElementById("resultado").style.overflow='hidden';
					document.getElementById("resultado").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()

	/************************************************************************************************************/
	/**********************************************FUNCIONES GENERALES*******************************************/
	/************************************************************************************************************/

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
	function validarFechaRep(fechaIni,fechaFin){
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





































