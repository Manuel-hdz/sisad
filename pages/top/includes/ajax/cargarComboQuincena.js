/**
  * Nombre del M�dulo: Topografia
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 12/Junio/2011                                      			
  * Descripci�n: Este archivo se encarga de llenar un comboBox con la informaci�n solicitada.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_quin;
	var nomCmbQuin;


	/******************************************************************************************
	 * Esta funci�n cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. idObra: Id de la obra de la cual se quieren consultar las Quincenas disponibles
	 * 2. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 3. nomCmbQuinCargar: Nombre del comboBox que se va a cargar con los datos
	 ******************************************************************************************/
	function cargarComboQuincena(idObra,nomTabla,nomCmbQuinCargar){
		//Guardar el nombre del comboBox que ser� cargado con los datos
		nomCmbQuin = nomCmbQuinCargar;
		
		//Si no ha sido seleccionado ningun valor no hacer nada		
		if(idObra!=""){			
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(cargarComboQuincena.js)
			var url = "includes/ajax/cargarComboQuincena.php?datoBusq="+idObra+"&tabla="+nomTabla;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoQuin(url, "GET", procesarRespuestaQuin);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que ser� cargado con los datos
			objeto = document.getElementById(nomCmbQuin);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="No. Quincena";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbQuinCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRespuestaQuin(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_quin.readyState==READY_STATE_COMPLETE){
			if(peticion_http_quin.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_quin.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("cant").item(0).firstChild.data;
					
					var dato;
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById(nomCmbQuin);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="No. Quincena";
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que ser�n cargados en el Combo
						valor = respuesta.getElementsByTagName("quincena"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tama�o del comboBox
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
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById(nomCmbQuin);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="No Hay Datos Registrados";
					objeto.options[objeto.length-1].value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	

	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoQuin(url, metodo, funcion) {
		peticion_http_quin = inicializa_xhr_cmb();
		if(peticion_http_quin){
			peticion_http_quin.onreadystatechange = funcion;
			peticion_http_quin.open(metodo, url, true);
			peticion_http_quin.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializa_xhr_cmb() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	