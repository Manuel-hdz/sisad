
/**
  * Nombre del Módulo: Requisiciones                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 25/Abril/2012
  * Descripción: Este archivo muestra las Requisiciones Registradas en un periodo y con parámetros especifícos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	//Variable que nos permite almacenar el id de la Requisicion para poder mostrar el botón pdf
	var requisicion;
	//Variable para almacenar el modulo actual dependiendo de la bd seleccionada
	var base;
	

	/*Esta función que verifica que una fecha se encuentre dentro del rango de otra ya registrada en la bd */
	function cargarRequisicion(bd){
		//Almacenamos los valores necesarios para la funcion
		var fechaIni = document.getElementById("txt_fechaIni").value;
		var fechaFin = document.getElementById("txt_fechaFin").value;
		var buscarPor = document.getElementById("cmb_buscarPor").value;
		var notas = document.getElementById("txa_notas").value;
		
		if(valFormFechasReq(fechaIni,fechaFin)){		
			if(notas!=""&&buscarPor!=""){
				var url = "../../includes/ajax/cargarRequisicion.php?fechaIni="+fechaIni+"&fechaFin="+fechaFin+"&buscarPor="+buscarPor+"&notas="+notas+"&bd="+bd;	
			}
			if(notas==""&&buscarPor==""){
				var url = "../../includes/ajax/cargarRequisicion.php?fechaIni="+fechaIni+"&fechaFin="+fechaFin+"&bd="+bd;
			}
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador.
			Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y
			no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatosReq(url, "GET", procesarRequisiciones);
		}			
	}//Fin de la Funcion cargarRequisicion(bd)
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarRequisiciones(){		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					//Variable para almacenar el dato del rs y ponerlo en el combo
					var dato;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById("cmb_estadoRequisicion");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					//Ponemos un espacio en blanco con el valor de REquisiciones
					objeto.options[objeto.length-1].text="Requisiciones";
					objeto.options[objeto.length-1].value="";
					//LLenamos elc ombo con el contenido del RS
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valor;						
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById("cmb_estadoRequisicion");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					//Ponemos en el combo la etiqueta de no Hay Requisiciones en caso de no encontrar resultados
					objeto.options[objeto.length-1].text="No Hay Requisiciones";
					//Le asignamos un valor vacio
					objeto.options[objeto.length-1].value="";
					//Vaciamos el InnerHTML de la consulta de la requisicion
					document.getElementById("consulta-datosReq").innerHTML ="";
					//Ocultammos el div da la consulta de requisiciones
					document.getElementById("consulta-datosReq").style.visibility ="hidden";
					//Escribimos unciamente el botón de regresar
					document.getElementById("botones").innerHTML = "<input name=\"btn_regresar\" type=\"button\" class=\"botones\" value=\"Regresar\" title=\"Regresar al Men&uacute; de Requisiciones\" onClick=\"location.href='menu_requisiciones.php'\" />";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRequisiciones()
		
		
	
	/*Esta función que busca una requisicion especifica de acuerdo a la seleccionada en la BD */
	function cargarTablaRequisicion(idReq, bd){
		//Verificamos que se haya seleccionado una requisicion del combo
		if(idReq!=""){
			//Almacenamos el valor global de la requisicion al de idReq para proceder a mostrar el PDF
			requisicion = idReq;
			//Asignamos el valor de la base de datos
			base=bd;
			//Creación de la URL
			var url = "../../includes/ajax/cargarRequisicion.php?idReq="+idReq+"&bd="+bd;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché 
			del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor 
			y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			mostrarDatosReq(url, "GET", procesarTablaRequisiciones);
		}
		else{//De lo contrario
			//Vaciamos el innerHTML que permite mostrar las requisiciones
			document.getElementById("consulta-datosReq").innerHTML ="";
			//Ocultamos el div que permite mostrar esta consulta
			document.getElementById("consulta-datosReq").style.visibility ="hidden";
			//Mostramos unicamente el boton de regresar
			document.getElementById("botones").innerHTML = "<input name=\"btn_regresar\" type=\"button\" class=\"botones\" value=\"Regresar\" title=\"Regresar al Men&uacute; de Requisiciones\" onClick=\"location.href='menu_requisiciones.php'\" />";
		}//Cierre del if(idReq!="")
	}//Fin de la Funcion cargarTablaRequisicion(campo)	
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarTablaRequisiciones(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					
					var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;
					//Obtener el codigo de la Tabla del archivo XML generado por el Servidor
					var codigo = respuesta.getElementsByTagName("tabla").item(0).firstChild.data;
					//Remplazar el simbolo '¬' por el tag de apertura '<' remplazado en el Servidor para poder generar el codigo XML
					var codigoHtmlTabla = codigo.replace(/¬/g,"<");											
					//Asignar la Tabla al DIV que la mostrará en la Pagina
					document.getElementById("consulta-datosReq").innerHTML = codigoHtmlTabla;
					//Hacer visible el DIV, ya que al momento de cargar la página, éste se encuentra oculto
					document.getElementById("consulta-datosReq").style.visibility = "visible";
					if(base==9){
						var atributo ="";
						var titulo="";
						if(estado=="PEDIDO"){
							atributo=" disabled='disabled'";
							titulo = " El Pedido Ya Fue Generado";
						}
						else{
							atributo="";
							titulo = "Generar Pedido";
						}
						//Poner los botones de Regresar y botón para Generar PDF
						document.getElementById("botones").innerHTML = "<input name=\"btn_regresar\" type=\"button\" class=\"botones\" value=\"Regresar\" title=\"Regresar al Men&uacute; de Requisiciones\" onClick=\"location.href='menu_requisiciones.php'\" /> &nbsp;&nbsp;&nbsp;&nbsp; <input name=\"btn_verPDF\" type=\"button\" class=\"botones\" value=\"Ver PDF\" title=\"Ver Archivo PDF de la Requisición Seleccionada\" onmouseover=\"window.status='';return true\" onclick=\"window.open('../../includes/generadorPDF/requisicion.php?id="+requisicion+"', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')\" /> &nbsp;&nbsp;&nbsp;&nbsp; <input name=\"btn_generarPedido\" type=\"button\" class=\"botones\" value=\"Generar Pedido\" title=\""+titulo+"\" onmouseover=\"window.status='';return true\" onclick=\"location.href='frm_generarPedido.php?idReq="+requisicion+"'\""+atributo+"/>";
					}
					else{
						//Poner los botones de Regresar y botón para Generar PDF
						document.getElementById("botones").innerHTML = "<input name=\"btn_regresar\" type=\"button\" class=\"botones\" value=\"Regresar\" title=\"Regresar al Men&uacute; de Requisiciones\" onClick=\"location.href='menu_requisiciones.php'\" /> &nbsp;&nbsp;&nbsp;&nbsp; <input name=\"btn_verPDF\" type=\"button\" class=\"botones\" value=\"Ver PDF\" title=\"Ver Archivo PDF de la Requisición Seleccionada\" onmouseover=\"window.status='';return true\" onclick=\"window.open('../../includes/generadorPDF/requisicion.php?id="+requisicion+"', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')\" />";
					}
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarTablaRequisiciones()
	/***************************************************************************************************************************************/
	/******************************************************FUNCIONES GENERALES**************************************************************/
	/***************************************************************************************************************************************/
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa
	 la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function mostrarDatosReq(url, metodo, funcion) {
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
