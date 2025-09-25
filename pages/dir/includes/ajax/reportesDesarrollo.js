/**
  * Nombre del M�dulo: Direcci�n General                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 12/Marzo/2012
  * Descripci�n: Este archivo muestra los registros previos y siguientes a los de los avances en pantalla
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;
	var cmb_periodo;

	/***************************************************************************************************************************************/
	/***********************************************REPORTE PRESUPUESTO VS AVANCE***********************************************************/
	/***************************************************************************************************************************************/

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function reporteDesarrollo(noRep,periodo,cliente){
		if(periodo!="" && cliente!=""){
			//Icono de Cargando
			document.getElementById("resultados").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesDesarrollo.php?rep="+noRep+"&periodo="+periodo+"&cliente="+cliente;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteDesarrollo);
		}
		else{
			var band=1;
			if(periodo==""){
				alert("Falta Seleccionar el Periodo");
				band=0;
			}
			if(band==1 && cliente=="")
				alert("Falta Seleccionar el Cliente");
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarReporteDesarrollo(){
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
					document.getElementById("resultados").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox' title='GR�FICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gr�fico' border=0></a>";
					//document.getElementById("resultados").innerHTML = grafica;	
				}
				else{
					document.getElementById("resultados").innerHTML ="<p align='center'><img src='images/advertencia.png' border=0></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
		
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