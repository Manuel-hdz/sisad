/**
  * Nombre del Módulo: Dirección General                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 12/Marzo/2012
  * Descripción: Este archivo muestra los registros previos y siguientes a los de los avances en pantalla
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;
	var nomCmb;

	/***************************************************************************************************************************************/
	/**********************************************SECCION DE CARGA DE COMBO CON DATOS******************************************************/
	/***************************************************************************************************************************************/
	function cargarComboClaves(num,fechaIni,fechaFin,combo){
		nomCmb=combo;
		opc=num;
		var fechaValida=validarFechaRep(fechaIni,fechaFin);
		if(fechaValida){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesSeguridad.php?rep="+num+"&fechaI="+fechaIni+"&fechaF="+fechaFin;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarDatosCombo);
		}
		else{
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Error en Rango Fechas";
			objeto.options[objeto.length-1].value="";
		}
	}
	
	function procesarDatosCombo(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="Clave";
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						id = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=id;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=id;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=id;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="No Hay Datos";
					objeto.options[objeto.length-1].value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}

	/***************************************************************************************************************************************/
	/****************************************************RECORRIDOS DE SEGURIDAD************************************************************/
	/***************************************************************************************************************************************/
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteRecSeg(noActa){
		if(noActa!=""){
			//Abrir la nueva ventana
			window.open("../../includes/generadorPDF/reporteRecSeg.php?id="+noActa+"","_blank","top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no");
		}
		else{
			alert("Falta Seleccionar la Clave del Recorrido");
		}
	}//Fin de la Funcion obtenerSueldo(campo)
		
	/***************************************************************************************************************************************/
	/*************************************************COMISION SEGURIDAD E HIGIENE**********************************************************/
	/***************************************************************************************************************************************/
	function mostrarReporteCSH(noActa){
		if(noActa!=""){
			//Abrir la nueva ventana
			window.open("../../includes/generadorPDF/actaSH.php?idActa="+noActa+"", "_blank","top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no'");
		}
		else{
			alert("Falta Seleccionar la Clave del Acta");
		}
	}
	
	/***************************************************************************************************************************************/
	/****************************************************ACCIDENTES E INCIDENTES************************************************************/
	/***************************************************************************************************************************************/
	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarReporteAccInc(noRep,fechaI,fechaF){
		var fechaValida=validarFechaRep(fechaI,fechaF);
		if(fechaValida){
			//Icono de Cargando
			document.getElementById("resultados").innerHTML="<br><br><br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/reportesSeguridad.php?rep="+noRep+"&fechaI="+fechaI+"&fechaF="+fechaF;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatos(url, "GET", procesarReporteAccInc);
		}
	}//Fin de la Funcion mostrarReporteAccInc(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarReporteAccInc(){
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
					document.getElementById("resultados").innerHTML = "<a href='tmp/"+grafica+"' rel='lightbox[repAccInc]' title='GRÁFICO DEL "+titulo+"'><img src='tmp/"+grafica+"' width='100%' height='100%' title='Ampliar Gráfico' border=0></a>";
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