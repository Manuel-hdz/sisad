/**
  * Nombre del Módulo: Topografía                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 18/Enero/2011                                       			
  * Descripción: Este archivo contiene las funciones para obtener los datos que serán registrados en la Salida de Material de manera Asincrona.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function buscarMaterialBD(campo,opcion){
		//Verificar que el dato que se esta buscando sea diferente de vacío
		if(campo.value!=""){
			//Guardar el origen de la solicitud de busqueda
			opc = opcion;
			//Obtener el datos que se quiere validar
			var clave = campo.value.toUpperCase();
			//Ocultar el mensaje que indica si la clave fue encontrada o no
			document.getElementById("mensaje").style.visibility = "hidden";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
 			var url = "includes/ajax/buscarMaterial.php?claveMaterial="+clave;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoMaterial(url, "GET", procesarDatosMaterial);
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoMaterial(url, metodo, funcion) {
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

	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarDatosMaterial(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					var existencia = respuesta.getElementsByTagName("existencia").item(0).firstChild.data;
					var unidad = respuesta.getElementsByTagName("unidad").item(0).firstChild.data;
					var costo = respuesta.getElementsByTagName("costo").item(0).firstChild.data;
					var categoria = respuesta.getElementsByTagName("categoria").item(0).firstChild.data;
					
					
					if(opc==1){//Entrada de Material
						//Colocar los datos en el Formulario de Entrada de Material
						document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = existencia;
						document.getElementById("txt_unidadMedida").value = unidad;						
						document.getElementById("cmb_categoria").value = "";
						//Colocar los datos ocultos en el Formulario de Entrada de Material
						document.getElementById("hdn_clave").value = clave;
						document.getElementById("hdn_existencia").value = existencia;						
				
						alert("Material: "+nombre+"\nCategoria: "+categoria);			
					}//Cierre if(opc==1){//Entrada de Material										
					if(opc==2){//Salida de Material
						//Colocar los datos en el Formulario de Salida de Material
						document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = existencia;
						document.getElementById("txt_unidadMedida").value = unidad;
						formatCurrency(costo,'txt_costoUnidad');
						document.getElementById("cmb_categoria").value = "";
						//Colocar los datos ocultos en el Formulario de Salida de Material
						document.getElementById("hdn_clave").value = clave;
						document.getElementById("hdn_existencia").value = existencia;
						document.getElementById("hdn_costoUnidad").value = costo;
				
						alert("Material: "+nombre+"\nCategoria: "+categoria);			
					}//Cierre if(opc==2){//Salida de Material
					if(opc==3){//Generar Requisicion													
						//Colocar los datos en el Formulario de Entrada de Material
						document.getElementById("cmb_categoria").value = "";						
						document.getElementById("cmb_material").value = "";
						document.getElementById("txt_clave").value = clave;																								
						
						alert("Material: "+nombre+"\nCategoria: "+categoria);
					}//Cierre if(opc==3){//Generar Requisicion
				}
				else{					
					if(opc==1){//Entrada de Material
						document.getElementById("mensaje").style.visibility = "visible";
						//Quitar los posibles datos que existan en el Formulario de Entrada de Material cuando una clave no esta registrada
						if(document.getElementById("cmb_material")!=null) document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = "";
						document.getElementById("txt_unidadMedida").value = "";
						document.getElementById("txt_costoUnidad").value = "";
						if(document.getElementById("cmb_categoria")!=null) document.getElementById("cmb_categoria").value = "";
						//Quitar los datos ocultos en el Formulario de Entrada de Material
						document.getElementById("hdn_clave").value = "";
						document.getElementById("hdn_existencia").value = "";						
					}//Cierre if(opc==1){//Entrada de Material					
					if(opc==2){//Salida de Material
						document.getElementById("mensaje").style.visibility = "visible";
						//Quitar los posibles datos que existan en el Formulario de Salida de Material cuando una clave no esta registrada
						if(document.getElementById("cmb_material")!=null) document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = "";
						document.getElementById("txt_unidadMedida").value = "";
						document.getElementById("txt_costoUnidad").value = "";
						if(document.getElementById("cmb_categoria")!=null) document.getElementById("cmb_categoria").value = "";
						//Quitar los datos ocultos en el Formulario de Salida de Material
						document.getElementById("hdn_clave").value = "";
						document.getElementById("hdn_existencia").value = "";
						document.getElementById("hdn_costoUnidad").value = "";
					}//Cierre if(opc==2){//Salida de Material
					if(opc==3){//Generar Requisicion
						document.getElementById("mensaje").style.visibility = "visible";
						//Quitar los posibles datos que existan en el Formulario de Salida de Material cuando una clave no esta registrada
						if(document.getElementById("cmb_material")!=null) document.getElementById("cmb_material").value = "";
						if(document.getElementById("cmb_categoria")!=null) document.getElementById("cmb_categoria").value = "";
						document.getElementById("txt_clave").value = "";							
					}//Cierre if(opc==3){//Generar Requisicion
					
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()