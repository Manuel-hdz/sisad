/**
  * Nombre del Módulo: Produccion
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 30/Diciembre/2011
  * Descripción: Este archivo contiene las funciones para buscar un dato especifico en la BD de acuerdo al Presupuesto registrado y al destino correspondiente
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Esta varible almacenara la petición realizada al Servidor de culquiera de las funciones declaradas en este archivo
	var peticion_http_txt;	
	//Guardar el nombre de la caja de texto que guardará los datos encontrados
	var nomTxt;
	var nomDestino;


	/******************************************************************************************
	 * Esta función obtendra el consecutivo del Area que le corresponda a un trabajador asignado, Parametros:
	 * 1. nomDestino: Es el nombre del Destino mediante la cual se obtendra el respectivo presupuesto
	 * 2. fechaPpto: Fecha en la que se realizará el registro, la cual debe estar incluida en un presupuesto
	 * 2. nomTxtCargar: Nombre de la Caja de Texto o Elemento HTML indicado para guardar el dato encontrado
	 ******************************************************************************************/
	function obtenerPresupuesto(nomDestino,fechaPpto,nomTxtCargar){
		//Guardar el nombre de la caja de Texto o componente HTML donde se guardara el dato buscado
		nomTxt = nomTxtCargar;			
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(nomDestino!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoPresupuesto.js)
			var url = "includes/ajax/obtenerDatoPresupuesto.php?destino="+nomDestino+"&fecha="+fechaPpto;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoTxt(url, "GET", procesarPptoDestino);
		}		
		else{
			document.getElementById(nomTxt).value = "";
		}		
	}//Fin de la function obtenerClaveArea(nomArea,nomTxtCargar)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarPptoDestino(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){								
					//Recuperar datos del Archivo XML					
					var ppto_destino = respuesta.getElementsByTagName("pptoDestino").item(0).firstChild.data;
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById(nomTxt).value = ppto_destino;
				}
				else{
					document.getElementById(nomTxt).value = "Sin Presupuesto";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarPptoDestino()	
	
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoTxt(url, metodo, funcion) {
		peticion_http_txt = inicializa_xhr_txt();
		if(peticion_http_txt){
			peticion_http_txt.onreadystatechange = funcion;
			peticion_http_txt.open(metodo, url, true);
			peticion_http_txt.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_txt() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}