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
	var cmb_periodo;
	var clasificacion;

	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function mostrarHistorialMovFin(){
		//Obtener la clasificacion
		clasificacion=document.getElementById("hdn_clasificacion").value;
		//Icono de Cargando
		document.getElementById("resultados").innerHTML="<br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
		var fecha = document.getElementById("txt_fecha").value;
		//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/regMovFin.php?fecha="+fecha+"&accion=show&clasificacion="+clasificacion;
		/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
		*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		mostrarDatos(url, "GET", procesarVistaMovimientos);
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarVistaMovimientos(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var tabla=respuesta.getElementsByTagName("tabla").item(0).firstChild.data;
					var titulo=respuesta.getElementsByTagName("titulo").item(0).firstChild.data;
					//Se remplazan todas las apariciones del caracter separador por el caracter <
					var tablaMod=tabla.replace(/¬/g,"<");
					document.getElementById("resultados").innerHTML = "<label class='titulo_tabla'><strong>"+titulo+"</strong></label><br>"+tablaMod;
				}
				else{
					document.getElementById("resultados").innerHTML="<p align='center'><img src='images/advertencia.png' border=0 width='234' heigth='233' title='En el Mes Seleccionado, No hay Registros de Ingresos ni Egresos'></p>";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	//Funcion que guarda el Movimiento en la bitacoa de Finanzas
	function guardarRegMovFin(){
		res=1;
		if(document.getElementById("cmb_tipoMov").value==""){
			res=0;
			alert("Seleccionar Tipo de Movimiento");
			document.getElementById("cmb_tipoMov").focus();
		}
		if(res==1 && document.getElementById("txt_cantidad").value==""){
			res=0;
			alert("Ingresar la Cantidad");
			document.getElementById("txt_cantidad").focus();
		}
		if(res==1 && document.getElementById("txa_concepto").value==""){
			res=0;
			alert("Ingresar el Concepto");
			document.getElementById("txa_concepto").focus();
		}
		if(res==1 && document.getElementById("txt_responsable").value==""){
			res=0;
			alert("Ingresar al Responsable");
			document.getElementById("txt_responsable").focus();
		}
		if(res==1){
			var cantidad = document.getElementById("txt_cantidad").value;
			var tipo = document.getElementById("cmb_tipoMov").value;
			if(parseFloat(cantidad.replace(/,/g,''))>parseFloat(document.getElementById("txt_monto").value.replace(/,/g,'')) && tipo=="EGRESO"){
				if(document.getElementById("txt_monto").value=="0.00")
					alert("No Hay Dinero Disponible, es Necesario Registrar un INGRESO");
				else
					alert("La Cantidad que Desea Sacar Excede el Monto Disponible");
			}
			else{
				//Icono de Cargando
				document.getElementById("resultados").innerHTML="<br><br><br><br><br><br><p align='center'><img src='../../images/cargando2.gif' border=0></p>";
				var fecha = document.getElementById("txt_fecha").value;
				var concepto = document.getElementById("txa_concepto").value;
				var responsable = document.getElementById("txt_responsable").value;
				//Obtener la clasificacion
				clasificacion=document.getElementById("hdn_clasificacion").value;
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
				var url = "includes/ajax/regMovFin.php?tipo="+tipo+"&fecha="+fecha+"&cantidad="+cantidad+"&concepto="+concepto+"&responsable="+responsable+"&accion=add&clasificacion="+clasificacion;
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
				*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				mostrarDatos(url, "GET", procesarGuardadoMovimientos);
			}
		}
	}
	
	function procesarGuardadoMovimientos(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					alert("¡Movimiento Guardado con Éxito!");
					mostrarHistorialMovFin();
					var tipo = respuesta.getElementsByTagName("tipo").item(0).firstChild.data;
					var descto = parseFloat(respuesta.getElementsByTagName("descto").item(0).firstChild.data);
					if(tipo=="EGRESO")
						total=document.getElementById("txt_monto").value.replace(/,/g,'')-descto;
					if(tipo=="INGRESO")
						total=parseFloat(document.getElementById("txt_monto").value.replace(/,/g,''))+parseFloat(descto);
					formatCurrency(total,'txt_monto');
					document.getElementById("cmb_tipoMov").value="";
					document.getElementById("txt_cantidad").value="";
					document.getElementById("txa_concepto").value="";
					document.getElementById("txt_responsable").value="";
				}
				else{
					alert("Hubo un Problema");
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}
	
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