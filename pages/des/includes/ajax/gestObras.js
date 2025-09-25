/**
  * Nombre del M�dulo: Desarrollo
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 03/Marzo/2012
  * Descripci�n: Este archivo obtiene la informaci�n de las obras consultadas
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var petGestObras;
	
	
	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd*/
	function consultarObra(idObra){
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/gestObras.php?idObra="+idObra;
		/* A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 * variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		realizarPetXHR(url, "GET", obtenerDatosObra);
	}//Fin de la Funcion mostrarReporteAnual(idObra)
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function obtenerDatosObra(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(petGestObras.readyState==READY_STATE_COMPLETE){
			if(petGestObras.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = petGestObras.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener el codigo de la Tabla del archivo XML generado por el Servidor
					var codigo = respuesta.getElementsByTagName("tabla").item(0).firstChild.data;
					//Remplazar el simbolo '�' por el tag de apertura '<' remplazado en el Servidor para poder generar el codigo XML
					var codigoHtmlTabla = codigo.replace(/�/g,"<");											
					//Asignar la Tabla al DIV que la mostrar� en la Pagina
					document.getElementById("consulta-datosObra").innerHTML = codigoHtmlTabla;
					//Hacer visible el DIV, ya que al momento de cargar la p�gina, �ste se encuentra oculto
					document.getElementById("consulta-datosObra").style.visibility = "visible";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion obtenerDatosObra()
	
	
	/*Esta funci�n carga el combo con todas las obras registradas en Desarrollo con una opci�n adicional de Ver Todas con value='TODAS'*/
	function cargarComboObras(){
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/gestObras.php?opcCargar=cargarComboObras";
		/* A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 * variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		realizarPetXHR(url, "GET", crearComboObras);
	}
	
	//Esta funci�n obtiene los valores a cargar en el Combo de Obras del archivo XML devuelto por el servidor
	function crearComboObras(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(petGestObras.readyState==READY_STATE_COMPLETE){
			if(petGestObras.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = petGestObras.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){										
					
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var clave = "";
					var obra = "";
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById("cmb_obra");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="Obra";
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){
						//Obtener cada uno de los datos que ser�n cargados en el Combo
						clave = respuesta.getElementsByTagName("clave"+(i+1)).item(0).firstChild.data;
						obra = respuesta.getElementsByTagName("obra"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tama�o del comboBox
						objeto.length++;						
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value = clave;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = clave;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = obra;						
					}//Cierre for(var i=0;i<tam;i++)	
					
					
					//Agregar la Opci�n Adicional al ComboBox
					objeto.length++;
					//Colocar los atributos de value y text
					objeto.options[objeto.length-1].text="Ver Todas";
					objeto.options[objeto.length-1].value="TODAS";
					
				}//Cierre if (existe=="true")
				else{
					//Obtener la referencia del comboBox que ser� cargado con los datos
					objeto = document.getElementById("cmb_obra");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text="No Hay Datos Registrados";
					objeto.options[objeto.length-1].value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Cierre function crearComboObras()

	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function realizarPetXHR(url, metodo, funcion) {
		petGestObras = inicializarElementoXHR();
		if(petGestObras) {
			petGestObras.onreadystatechange = funcion;
			petGestObras.open(metodo, url, true);
			petGestObras.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarElementoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}