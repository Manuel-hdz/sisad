/**
  * Nombre del M�dulo: Direcci�n General                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Febrero/2012
  * Descripci�n: Este archivo muestra los registros previos y siguientes a los de los avances en pantalla
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/***************************************************************************************************************************************/
	/***********************************************REPORTE PRESUPUESTO VS AVANCE***********************************************************/
	/***************************************************************************************************************************************/

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporte(noRep,cmb_periodo,cmb_ubicacion){
		if(cmb_periodo.value!="" && cmb_ubicacion.value!=""){
			//Icono de Cargando
			document.getElementById("resultados").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesGT.php?rep="+noRep+"&combo="+cmb_periodo.value+"&ubicacion="+cmb_ubicacion.value;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporte);
		}
		else{
			band=1;
			if (cmb_periodo.value==""){
				alert("Falta Seleccionar un Periodo");
				band=0;
			}
			if (cmb_ubicacion.value=="" && band==1)
				alert("Falta Seleccionar una Ubicaci�n");
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporte(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var grafica = respuesta.getElementsByTagName("grafica").item(0).firstChild.data;
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					//document.getElementById("form-selecPeriodo").style.visibility="hidden";
					document.getElementById("resultados").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox' title='GR�FICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gr�fico' border=0></a>";
					//document.getElementById("parrila-volver").innerHTML="<form action='frm_repGerenciaAvancePpto.php'><input type='image' src='images/back.png' name='back' id='back' width='50' height='50' border='0' title='Subir un Nivel'/></form>";
				}
				else{
					document.getElementById("resultadoGrafico").innerHTML = "<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/***************************************************************************************************************************************/
	/**********************************************REPORTE COMPARATIVO DE MINAS*************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd*/
	function mostrarReporteCompMina(noRep,cmb_ubicacion,cmb_anios){
		if(cmb_ubicacion.value!="" && cmb_anios.value!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesGT.php?rep="+noRep+"&ubicacion="+cmb_ubicacion.value+"&anio="+cmb_anios.value;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteCompMina);
		}
		else{
			band=1;
			if (cmb_ubicacion.value==""){
				alert("Falta Seleccionar una Ubicaci�n");
				band=0;
			}
			if (cmb_anios.value=="" && band==1)
				alert("Falta Seleccionar el A�o");
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporteCompMina(){
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
					document.getElementById("form-selecPeriodo").style.visibility='hidden';
					var grafica = respuesta.getElementsByTagName("grafica").item(0).firstChild.data;
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					document.getElementById("form-selecPeriodo").style.visibility="hidden";
					document.getElementById("resultadoGrafico").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox' title='GR�FICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gr�fico' border=0></a>";
					document.getElementById("parrila-volver").style.visibility='visible';
					document.getElementById("resultadoGrafico").style.visibility='visible';
					document.getElementById("resultadoTabla").innerHTML = tablaMod;
					document.getElementById("resultadoTabla").style.visibility='visible';
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarReporteCompMina()
	
	/***************************************************************************************************************************************/
	/***********************************************REPORTE ANUAL POR CLIENTES**************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd*/
	function mostrarReporteAnual(noRep,cmb_anios){
		if(cmb_anios.value!=""){
			//Icono de Cargando
			document.getElementById("resultadoGrafico").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesGT.php?rep="+noRep+"&anio="+cmb_anios.value;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteAnual);
		}
		else
			alert("Falta Seleccionar el A�o");
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporteAnual(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var cont=0;
					var texto="";
					var titulo= respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					do{
						var grafica = respuesta.getElementsByTagName("grafica"+cont).item(0).firstChild.data;
						if (cont==0)
							document.getElementById("resultadoGrafico").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repAnual]' title='GR�FICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gr�fico' border=0></a>";
						else{
							texto+="<a href='tmp/"+grafica+"' rel='lightbox[repAnual]' title='GR�FICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gr�fico' border=0></a>";
						}
						cont++;
					}while(cont<12);
					document.getElementById("imagenes").innerHTML = texto;
				}
				else{
					document.getElementById("resultadoGrafico").innerHTML = "<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarReporteCompMina()
	
	/***************************************************************************************************************************************/
	/**********************************************REPORTE MENSUALPOR CLIENTES**************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd*/
	function mostrarReporteMensual(noRep,periodo){
		if(periodo.value!=""){
			document.getElementById("resultados").style.visibility='visible';
			//Icono de Cargando
			document.getElementById("resultados").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesGT.php?rep="+noRep+"&periodo="+periodo.value;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteMensual);
		}
		else
			alert("Falta Seleccionar el Periodo");
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporteMensual(){
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
					var tablaMod=tabla.replace(/�/g,"<");
					document.getElementById("resultados").innerHTML = tablaMod+"<p align='center'><a href='tmp/"+grafica+"' rel='lightbox' title='Comparativo Gr�fico'><img src='images/grafica.png' title='Ver comparativo Mensual en Gr�fica' border=0/></a></p>";
					document.getElementById("resultados").style.overflow='scroll';
					document.getElementById("resultados").style.visibility='visible';
				}
				else{
					document.getElementById("resultados").innerHTML = "<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarReporteCompMina()
	
	/***************************************************************************************************************************************/
	/******************************************************FUNCIONES GENERALES**************************************************************/
	/***************************************************************************************************************************************/
	
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