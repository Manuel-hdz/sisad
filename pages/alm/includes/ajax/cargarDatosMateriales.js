/**
  * Nombre del Módulo: Desarrollo                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 20/Octubre/2011                                       			
  * Descripción: Este archivo contiene la funcion que carga el Catalogo de Sueldos en caso de que exista
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function extraerDatosMaterialCombo(idMaterial,tipo){
		opc=tipo;
		if (idMaterial!=""){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/cargarDatosMateriales.php?idMaterial="+idMaterial;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaDatosMaterialCombo(url, "GET", procesarDatosMaterialCombo);
		}
		else{
			document.getElementById("txt_clave").value="";
			document.getElementById("txt_existencia").value="";
			document.getElementById("txt_unidadMedida").value="";
			if(opc=="S")
				document.getElementById("txt_costoUnidad").value="";
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaDatosMaterialCombo(url, metodo, funcion) {
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
	function procesarDatosMaterialCombo(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var idMat = respuesta.getElementsByTagName("idMaterial").item(0).firstChild.data;
					var existencia = respuesta.getElementsByTagName("existencia").item(0).firstChild.data;
					var u_medida = respuesta.getElementsByTagName("u_medida").item(0).firstChild.data;
					if(opc=="S"){
						var costo = respuesta.getElementsByTagName("costo_u").item(0).firstChild.data;
						document.getElementById("txt_costoUnidad").value=costo;
					}
					document.getElementById("txt_clave").value=idMat;
					document.getElementById("txt_existencia").value=existencia;
					document.getElementById("txt_unidadMedida").value=u_medida;
				}
				else{
					document.getElementById("hdn_estado").value="Agregar";

				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()