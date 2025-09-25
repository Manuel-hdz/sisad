/**
  * Nombre del M�dulo: Recursos Humanos                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V
  * Fecha: 15/Mayo/2012
  * Descripci�n: Este archivo contiene las funciones para obtener los datos del bono indicado.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petici�n HTTP para validar los Dato que se quieren guardar y consultar en la BD
	var pet_http_bonos_nomina;


	/*Esta funci�n crear� la URl para obtener los datos del Bono con la clave indicada*/
	function obtenerDatosBono(idBono){

		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este 
		//archivo JavaScript(obtenerDatosBonoNomina.js)
		var url = "includes/ajax/obtenerDatosBonoNomina.php?idBono="+idBono;
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContBonosNomina(url, "GET", recuperarDatosBono);
		
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function recuperarDatosBono(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_http_bonos_nomina.readyState==READY_STATE_COMPLETE){
			if(pet_http_bonos_nomina.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = pet_http_bonos_nomina.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				//Si genera Resultados, tiene Entradas Registradas, por lo tanto, desactivar los Elementos para Registro de Entrada
				if (existe=="true"){
					//Recuperar el RFC y el numero de concepto a deshabilitar
					var descripcion = respuesta.getElementsByTagName("descripcion").item(0).firstChild.data;
					var cantidad = respuesta.getElementsByTagName("cantidad").item(0).firstChild.data;
					var autorizo = respuesta.getElementsByTagName("autorizo").item(0).firstChild.data;
					var fecha = respuesta.getElementsByTagName("fecha").item(0).firstChild.data;
					
					//Colocar los datos recuperados en las cajas de texto correspondientes
					document.getElementById("txa_descripcion").value = descripcion;
					formatCurrency(cantidad,'txt_cantidadBono');
					//document.getElementById("txt_cantidadBono").value = cantidad;
					document.getElementById("txt_autorizo").velue = autorizo;
					document.getElementById("txt_fecha").value = fecha;
				}				
			}//If if(pet_http_bonos_nomina.status==200)
		}//If if(pet_http_bonos_nomina.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion recuperarDatosBono()
	
	
	/*Esta funcion cargara las �reas donde existen nominas registradas en el a�o y mes seleccionados*/
	function cargarAreasNomina(anio,mes){
		//Si el mes seleccionado es diferente de vacio, proceder a crear la url
		if(mes!=""){
			//Crear la URL, la cual ser� solicitada al Servidor
			var url = "includes/ajax/obtenerDatosBonoNomina.php?anio="+anio+"&mes="+mes;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 * Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente 
			 * al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContBonosNomina(url, "GET", procesarDatosArea);
		}
		else{//Cuando sea seleccionada la etiqueta con opci�n vac�a, vaciamos el ComboBox de �reas
			objeto = document.getElementById("cmb_area");
			//Vaciar el comboBox antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vac�o
			objeto.length++;
			objeto.options[objeto.length-1].text = "�rea";
			objeto.options[objeto.length-1].value = "";
		}
	}//Cierre de la funcion cargarAreasNomina(anio,mes)
	
	
	/*Esta funci�n agregar� las opciones al ComboBox con los datos obtenidos del servidor*/
	function procesarDatosArea(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_http_bonos_nomina.readyState==READY_STATE_COMPLETE){
			if(pet_http_bonos_nomina.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = pet_http_bonos_nomina.responseXML;
				//Obtener el resultado de la petici�n realizada
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				//Si genera Resultados, proceder a agregarlos al ComboBox
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById("cmb_area");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio y la Etiqueta
					objeto.length++;
					objeto.options[objeto.length-1].text = "�rea";
					objeto.options[objeto.length-1].value = "";
					for(var i=0;i<tam;i++){
						//Obtener cada uno de los datos que ser�n cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;						
						//Aumentar en 1 el tama�o del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = valor;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor del Id en el Atributo Title
						objeto.options[objeto.length-1].title = valor;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion									
					objeto = document.getElementById("cmb_area");										
					objeto.length = 0;					
					objeto.length++;
					objeto.options[objeto.length-1].text = "No Hay Datos Registrados";
					objeto.options[objeto.length-1].value = "";					
				}
			}//If if(pet_http_bonos_nomina.status==200)
		}//If if(pet_http_bonos_nomina.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funci�n procesarDatosArea()
	
	
	/*Esta funci�n cargara las nominas disponibles en el A�o, Mes y �rea seleccionados*/
	function cargarNominas(anio,mes,area){
		//Si el �rea seleccionada es diferente de vacio, proceder a crear la url
		if(area!=""){
			//Crear la URL, la cual ser� solicitada al Servidor
			var url = "includes/ajax/obtenerDatosBonoNomina.php?area="+area+"&anio="+anio+"&mes="+mes;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 * Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente 
			 * al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContBonosNomina(url, "GET", obtenerNominas);
		}
		else{//Cuando sea seleccionada la etiqueta con opci�n vac�a, vaciamos el ComboBox de Periodo
			objeto = document.getElementById("cmb_periodo");
			//Vaciar el comboBox antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vac�o
			objeto.length++;
			objeto.options[objeto.length-1].text = "Periodo";
			objeto.options[objeto.length-1].value = "";
		}
	}//Cierre de la funci�n cargarNominas(anio,mes,area)
	
	
	/*Esta funci�n agregar� las opciones al ComboBox con los datos obtenidos del servidor*/
	function obtenerNominas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_http_bonos_nomina.readyState==READY_STATE_COMPLETE){
			if(pet_http_bonos_nomina.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = pet_http_bonos_nomina.responseXML;
				//Obtener el resultado de la petici�n realizada
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				//Si genera Resultados, proceder a agregarlos al ComboBox
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById("cmb_periodo");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio y la Etiqueta
					objeto.length++;
					objeto.options[objeto.length-1].text = "Periodo";
					objeto.options[objeto.length-1].value = "";
					for(var i=0;i<tam;i++){
						//Obtener cada uno de los datos que ser�n cargados en el Combo
						valor = respuesta.getElementsByTagName("idNomina"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("periodo"+(i+1)).item(0).firstChild.data;
						
						//Aumentar en 1 el tama�o del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = texto;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor del Id en el Atributo Title
						objeto.options[objeto.length-1].title = texto;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion									
					objeto = document.getElementById("cmb_periodo");										
					objeto.length = 0;					
					objeto.length++;
					objeto.options[objeto.length-1].text = "No Hay Datos Registrados";
					objeto.options[objeto.length-1].value = "";					
				}
			}//If if(pet_http_bonos_nomina.status==200)
		}//If if(pet_http_bonos_nomina.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funci�n obtenerNominas()
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContBonosNomina(url, metodo, funcion) {
		pet_http_bonos_nomina = ini_xhr_bonos_nomina();
		if(pet_http_bonos_nomina) {
			pet_http_bonos_nomina.onreadystatechange = funcion;
			pet_http_bonos_nomina.open(metodo, url, true);
			pet_http_bonos_nomina.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function ini_xhr_bonos_nomina() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}