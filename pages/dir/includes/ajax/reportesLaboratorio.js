/**
  * Nombre del M�dulo: Direcci�n General                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 01/Marzo/2012
  * Descripci�n: Este archivo contiene las operaciones de Reportes de Recursos Humanos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;

	/***********************************************************************************************************/
	/******************************************REPORTE DE RENDIMIENTO*******************************************/
	/***********************************************************************************************************/
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteRendimiento(idMezcla,numMuestra){
		if(idMezcla!="" && numMuestra!=""){
			//Variables para enviar y generar el reporte
			var nombre="ING. JOSE GUILLERMO MARTINEZ ROM�N";
			var puesto="DIRECTOR GENERAL";
			var empresa="CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.";
			//Abrir la nueva ventana
			window.open("../../includes/generadorPDF/reporteRendimiento.php?id="+idMezcla+"&idReg="+numMuestra+"&nombre="+nombre+"&puesto="+puesto+"&empresa="+empresa+"","_blank","top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no");
		}
		else{
			if(idMezcla=="")
				alert("Es Necesario Seleccionar la Mezcla");
			else if(numMuestra=="")
				alert("Es Necesario Seleccionar el N�mero de Muestra");
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/************************************************************************************************************/
	/******************************************REPORTE DE RESISTENCIAS*******************************************/
	/************************************************************************************************************/
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteResistencias(noRep,idMuestra){
		if(idMuestra!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesLaboratorio.php?noRep="+noRep+"&idMuestra="+idMuestra;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteResistencia);
		}
		else
			alert("Es Necesario Seleccionar la Clave");
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporteResistencia(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Variable que recupera el ID de la Prueba
					var idPrueba = respuesta.getElementsByTagName("idPrueba").item(0).firstChild.data;
					//Variables para enviar y generar el reporte
					var nombre="ING. JOSE GUILLERMO MARTINEZ ROM�N";
					var puesto="DIRECTOR GENERAL";
					var empresa="CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.";
					//Abrir la nueva ventana
					window.open("../../includes/generadorPDF/reportePruebas.php?id="+idPrueba+"&nombre="+nombre+"&puesto="+puesto+"&empresa="+empresa+"","_blank","top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no");
				}
				else{
					document.getElementById("resultado").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()

	/************************************************************************************************************/
	/*******************************************REPORTE DE AGREGADOS*********************************************/
	/************************************************************************************************************/
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteAgregados(noRep,idAgregado,fechaIni,fechaFin){
		var fechaValida=validarFechaRep(fechaIni,fechaFin);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultado").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesLaboratorio.php?noRep="+noRep+"&fechaI="+fechaIni+"&fechaF="+fechaFin+"&idAgregado="+idAgregado;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteAgregados);
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporteAgregados(){
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
					var tablaMod=tabla.replace(/�/g,"<");
					//Se remplazan todas las apariciones del caracter que divide lo escrito para enviar por GET
					tablaMod=tablaMod.replace(/�/g,"&");
					document.getElementById("resultado").style.overflow='scroll';
					document.getElementById("resultado").innerHTML = tablaMod;
				}
				else{
					document.getElementById("resultado").style.overflow='hidden';
					document.getElementById("resultado").innerHTML ="<p align='center'><img src='images/advertencia.png' width='234' height='233' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/************************************************************************************************************/
	/**********************************************FUNCIONES GENERALES*******************************************/
	/************************************************************************************************************/

	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function mostrarDatos(url, metodo, funcion) {
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

	//Verificar que el a�o de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
		return false;
	}
	else
		return true;
}
