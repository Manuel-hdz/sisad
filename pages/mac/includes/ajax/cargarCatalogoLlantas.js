/**
  * Nombre del M�dulo: Mantenimiento                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Octubre/2012
  * Descripci�n: Este archivo contiene la funci�n que carga el Cat�logo de Llantas en Mtto
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;
	var nomElemnt;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function obtenerLlanta(comboLlanta){
		if (comboLlanta.value!=""){
			if(comboLlanta.value=="NUEVALLANTA"){
				//Resetear el formulario
				document.forms.frm_gestionLlantas.reset();
				//Asignar el valor por default a Agregar Llanta
				document.getElementById("cmb_llanta").value="NUEVALLANTA";
				//Habilitar los elementos del formulario
				document.getElementById("cmb_marca").disabled=false;
				document.getElementById("cmb_equipos").disabled=false;
				//document.getElementById("txt_nuevas").disabled=false;
				//document.getElementById("txt_reuso").disabled=false;
				//document.getElementById("txt_deshecho").disabled=false;
				document.getElementById("txt_medida").disabled=false;
				document.getElementById("txt_medidaRin").disabled=false;
				//Linea que muestra un mensaje donde guardar la nueva Area
				var linea = prompt("Ingresar Descripci�n de la Llanta","Nombre de la Llanta...");
				//Verificar si el dato introducido es valido
				if(linea!=null && linea!="Nombre de la Llanta..." && linea!="" && linea.length<=30){
					linea=linea.toUpperCase();
					//Variable para revisar los caracteres de error
					var error=0;
					//Recorrer el dato ingresado buscando caracteres prohibidos
					for(i=0;i<linea.length;i++){
						//Igualamos el valor de seccion a car para su facil manejo
						car = linea.charAt(i);
						if(car=='%'||car=='&'||car=='"'){
							error=1;
							break;
						}
					}//Cierre for(i=0;i<linea.length;i++)
					if(error==0){
						//Variable que permite verificar si existe un dato o no en el combo de referencia
						var existe=0;
						for(i=0; i<document.getElementById("cmb_llanta").length; i++){
							//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
							if(document.getElementById("cmb_llanta").options[i].value==linea)
								existe = 1;
						} //FIN for(i=0; i<comboBox.length; i++)
						if (existe==1){
							alert("La Llanta ya existe");
							document.getElementById("cmb_llanta").value=linea;
						}
						//Si el area existe, no continuar con el proceso
						if(existe!=1){
							//Agregar al final la nueva opcion seleccionada
							comboLlanta.length++;
							comboLlanta.options[comboLlanta.length-1].text = linea;
							//Ingresar un value aleatorio a la Llanta, solo para el manejo de la misma
							comboLlanta.options[comboLlanta.length-1].value = linea;
							//Preseleccionar la opcion agregada
							comboLlanta.options[comboLlanta.length-1].selected = true;
							//Mover el foco al siguiente Elemento
							document.getElementById("cmb_marca").focus();
						}
					}
					else{
						alert("El Dato "+linea+" Ingresado No Es V�lido");
						document.getElementById("cmb_llanta").value = "";
						document.getElementById("cmb_marca").disabled=true;
						document.getElementById("cmb_equipos").disabled=true;
						//document.getElementById("txt_nuevas").disabled=true;
						//document.getElementById("txt_reuso").disabled=true;
						//document.getElementById("txt_deshecho").disabled=true;
						document.getElementById("txt_medida").disabled=true;
						document.getElementById("txt_medidaRin").disabled=true;
					}
				}
				else{
					//Deshabilitar los elementos del formulario
					document.getElementById("cmb_marca").disabled=true;
					document.getElementById("cmb_equipos").disabled=true;
					//document.getElementById("txt_nuevas").disabled=true;
					//document.getElementById("txt_reuso").disabled=true;
					//document.getElementById("txt_deshecho").disabled=true;
					document.getElementById("txt_medida").disabled=true;
					document.getElementById("txt_medidaRin").disabled=true;
					if(linea!=null && linea.length>30)
						alert("El Nombre de la Llanta No puede ser Mayor a 30 Caracteres");
					else
						alert("Dato Ingresado No V�lido");
					document.getElementById("cmb_llanta").value = "";
				}
			}
			if(isNaN(comboLlanta.value)){
				//Habilitar los elementos del formulario
				document.getElementById("cmb_marca").disabled=false;
				document.getElementById("cmb_equipos").disabled=false;
				//document.getElementById("txt_nuevas").disabled=false;
				//document.getElementById("txt_reuso").disabled=false;
				//document.getElementById("txt_deshecho").disabled=false;
				document.getElementById("txt_medida").disabled=false;
				document.getElementById("txt_medidaRin").disabled=false;
			}
			if(isNaN(comboLlanta.value) && comboLlanta.value!="NUEVALLANTA"){
				//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
				var url = "includes/ajax/cargarCatalogoLlantas.php?idLlanta="+comboLlanta.value;
				/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
				*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				cargarInformacion(url, "GET", procesarLlanta);
			}
		}
		else{
			//Resetear el formulario
			document.forms.frm_gestionLlantas.reset();
			//Asignar el valor por default a Agregar Llanta
			document.getElementById("cmb_llanta").value="";
			//Deshabilitar los elementos del formulario
			document.getElementById("cmb_marca").disabled=true;
			document.getElementById("cmb_equipos").disabled=true;
			//document.getElementById("txt_nuevas").disabled=true;
			//document.getElementById("txt_reuso").disabled=true;
			//document.getElementById("txt_deshecho").disabled=true;
			document.getElementById("txt_medida").disabled=true;
			document.getElementById("txt_medidaRin").disabled=true;
		}
	}//Fin de la Funcion obtenerSueldo(campo)	

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarLlanta(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var marca = respuesta.getElementsByTagName("marca").item(0).firstChild.data;
					var familia = respuesta.getElementsByTagName("familia").item(0).firstChild.data;
					var medida = respuesta.getElementsByTagName("medida").item(0).firstChild.data;
					var medidaRin = respuesta.getElementsByTagName("rin").item(0).firstChild.data;
					//var nueva = respuesta.getElementsByTagName("nueva").item(0).firstChild.data;
					//var reuso = respuesta.getElementsByTagName("reuso").item(0).firstChild.data;
					//var deshecho = respuesta.getElementsByTagName("deshecho").item(0).firstChild.data;
					//var costo = respuesta.getElementsByTagName("costo").item(0).firstChild.data;
					//Asignar los valores encontrados
					document.getElementById("cmb_marca").value=marca;
					document.getElementById("cmb_equipos").value=familia;
					document.getElementById("txt_medida").value=medida;
					document.getElementById("txt_medidaRin").value=medidaRin;
					//document.getElementById("txt_nuevas").value=nueva;
					//document.getElementById("txt_reuso").value=reuso;
					//document.getElementById("txt_deshecho").value=deshecho;
					//document.getElementById("txt_costo").value=costo;
					//Habilitar los elementos del formulario
					document.getElementById("cmb_marca").disabled=false;
					document.getElementById("cmb_equipos").disabled=false;
					//document.getElementById("txt_nuevas").disabled=false;
					//document.getElementById("txt_reuso").disabled=false;
					//document.getElementById("txt_deshecho").disabled=false;
					document.getElementById("txt_medida").disabled=false;
					document.getElementById("txt_medidaRin").disabled=false;
					//Cambiar el valor de la variable hidden, a fin de actualizar
					document.getElementById("hdn_estado").value="Actualizar";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	function extraerCantidadLlantas(llanta){
		if(llanta!=""){
			//document.getElementById("txt_nuevas").value=0;
			//document.getElementById("txt_reuso").value=0;
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/cargarCatalogoLlantas.php?idLlanta="+llanta+"&tipo=Cantidad";
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarInformacion(url, "GET", procesarCantidadLlantas);
		}
		else{
			document.getElementById("hdn_cantNuevas").value="";
			document.getElementById("hdn_cantReuso").value="";
		}
	}
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarCantidadLlantas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var nueva = respuesta.getElementsByTagName("nueva").item(0).firstChild.data;
					var reuso = respuesta.getElementsByTagName("reuso").item(0).firstChild.data;
					//Asignar los valores encontrados
					document.getElementById("hdn_cantNuevas").value=nueva;
					document.getElementById("hdn_cantReuso").value=reuso;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarInformacion(url, metodo, funcion) {
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
	
	/*Esta funci�n obtendr� el dato que se quiere validar y realizar� la Petici�n Asincrona al Servidor */
	function extraerInfoLlanta(llanta, elemento, opcion){
		nomElemnt = elemento;
		opc = opcion;
		if(llanta!=""){
			//Quitar todas las apariciones del caracter Apostrofe por <>, esto por la forma en que el lector de codigo de barras toma la informacion
			llanta=llanta.replace(/'/g,"<>");
			//Obtener el datos que se quiere validar
			llanta = llanta.toUpperCase();
			//Ocultar el mensaje que indica si la clave fue encontrada o no
			//document.getElementById("mensaje").style.visibility = "hidden";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/cargarCatalogoLlantas.php?llanta="+llanta.toUpperCase();
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarInformacion(url, "GET", procesarDatosLlanta);
		}
		else{
			if(llanta==""){
				alert("Ingresar el id de la Llanta");
				document.getElementById(""+nomElemnt).focus();
			}
			document.getElementById(""+nomElemnt).value="";
		}
	}//Fin de la Funcion extraerInfoEquipo(campo)
	
	function procesarDatosLlanta(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if(opc == 1){
					if (existe=="true"){			
						var llanta = respuesta.getElementsByTagName("llanta").item(0).firstChild.data;
						alert("La llanta " + llanta + " ya se encuentra registrada");
						document.getElementById(""+nomElemnt).focus();
						document.getElementById(""+nomElemnt).value="";
					}
				}
				else if(opc ==2){
					if (existe=="false"){			
						alert("La llanta no se encuentra registrada");
						document.getElementById(""+nomElemnt).focus();
						document.getElementById(""+nomElemnt).value="";
					}
					else{
						var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;
						if(estado != "INSTALADA"){
							var llanta = respuesta.getElementsByTagName("llanta").item(0).firstChild.data;
							alert("La llanta " + llanta + " no se encuentra instalada en ningun equipo");
							document.getElementById(""+nomElemnt).focus();
							document.getElementById(""+nomElemnt).value="";
						}
					}
				}
				else if(opc ==3){
					if (existe=="false"){			
						alert("La llanta no se encuentra registrada");
						document.getElementById(""+nomElemnt).focus();
						document.getElementById(""+nomElemnt).value="";
					}
					else{
						var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;
						if(estado == "INSTALADA"){
							var llanta = respuesta.getElementsByTagName("llanta").item(0).firstChild.data;
							alert("La llanta " + llanta + " ya se encuentra instalada en un equipo");
							document.getElementById(""+nomElemnt).focus();
							document.getElementById(""+nomElemnt).value="";
						}
						else if(estado == "DESECHADA"){
							var llanta = respuesta.getElementsByTagName("llanta").item(0).firstChild.data;
							alert("La llanta " + llanta + " ya ha sido desechada");
							document.getElementById(""+nomElemnt).focus();
							document.getElementById(""+nomElemnt).value="";
						}
					}
				}
			}
		}
	}
	
	/*Esta funci�n obtendr� el dato que se quiere validar y realizar� la Petici�n Asincrona al Servidor */
	function extraerInfoEmpleado(clave){
		if(clave!=""){
			//Quitar todas las apariciones del caracter Apostrofe por <>, esto por la forma en que el lector de codigo de barras toma la informacion
			clave=clave.replace(/'/g,"<>");
			//Obtener el datos que se quiere validar
			clave = clave.toUpperCase();
			//Ocultar el mensaje que indica si la clave fue encontrada o no
			//document.getElementById("mensaje").style.visibility = "hidden";
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/cargarCatalogoLlantas.php?clave="+clave.toUpperCase();
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarInformacion(url, "GET", procesarDatosEmpleado);
		}
		else{
			if(clave==""){
				alert("Ingresar el id del Trabajador");
				document.getElementById("txt_codigo").focus();
			}
			document.getElementById("txt_codigo").value="";
		}
	}//Fin de la Funcion extraerInfoEmpleado(campo)
	
	function procesarDatosEmpleado(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){			
					var empleado = respuesta.getElementsByTagName("empleado").item(0).firstChild.data;
					var rfc = respuesta.getElementsByTagName("rfc").item(0).firstChild.data;
					document.getElementById("txt_empleado").value=empleado;
					document.getElementById("hdn_rfc").value=rfc;
				}
				else{			
					alert("No hay trabajadores registrados con la clave solicitada");
					document.getElementById("txt_codigo").value="";
					document.getElementById("txt_empleado").value="";
					document.getElementById("hdn_rfc").value="";
					
					document.getElementById("txt_codigo").focus();
				}
			}
		}
	}