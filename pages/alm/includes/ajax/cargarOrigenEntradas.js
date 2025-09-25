/**
  * Nombre del Módulo: Mantenimiento
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Febrero/2011                                      			
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_cmb;
	var nomCmb;
	var etqCombo;
	var opc;


	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. datoBusq: Es el dato que será buscado
	 * 2. nomBD: Nombre de la BD donde se encuentran los datos a cargar
	 * 3. nomTabla: Nombre de la Tabla de la BD donde se encuentran los datos a cargar
	 * 4. nomCampoBusq: Nombre del campo en la tabla que contiene los datos que serán cargados en el ComboBox
	 * 5. nomCampoRef: Nombre del campo de referencia que esta en la tabla, el cual indica que datos serán cargados
	 * 6. nomCmbCargar: Nombre del comboBox que se va a cargar con los datos
	 * 7. etiqCombo: Etiqueta que aparecerá en el comboBox que será cargado
	 * 8. valSeleccionado: Es el valor que aparecera seleccionado por defecto.
	 ******************************************************************************************/
	function cargarDatosEntrada(opcion,combo){
		if(opcion!=""){
			opc=opcion;
			nomCmb=combo;
			depto="NA";
			switch(opcion){
				case "oc":
					//Guardar la etiqueta del comboBox que será cargado con los datos
					etqCombo = "Órdenes Compra";
				break;
				case "pedido":
					//Guardar la etiqueta del comboBox que será cargado con los datos
					etqCombo = "Pedidos";
				break;
				case "requisicion":
					//Guardar la etiqueta del comboBox que será cargado con los datos
					etqCombo = "Requisiciones";
					//Obtener el Depto de donde se revisaran las requisiciones
					depto=document.getElementById("cmb_opciones").value;
				break;
			}
			if(opcion=="oc" || opcion=="pedido"){
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
				//incluido este archivo JavaScript(cargarCombo.js)
				var url = "includes/ajax/cargarOrigenEntradas.php?opcion="+opcion+"&depto="+depto;
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
				 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
				 *servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				cargaContenidoCmb(url, "GET", procesarRespuestaCmb);
			}
			else{//Verificar para las Requisiciones
				//Verificar si el combo de PARAMETROS tiene el valor id_requisicion
				//de lo contrario no hacer nada
				if(document.getElementById("cmb_param").value=="id_requisicion"){
					if(opcion=="requisicion" && depto!=""){
						//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
						//incluido este archivo JavaScript(cargarCombo.js)
						var url = "includes/ajax/cargarOrigenEntradas.php?opcion="+opcion+"&depto="+depto;
						/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
						 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
						 *servidor y no utilizar su cache*/
						url += "&nocache=" + Math.random();
						//Hacer la Peticion al servidor de forma Asincrona
						cargaContenidoCmb(url, "GET", procesarRespuestaCmb);
					}
					else{//si en el combo se eligio la etiqueta, o el valor vacio, vaciar el combo
						//Obtener la referencia del comboBox que será cargado con los datos
						objeto = document.getElementById(nomCmb);					
						//Vaciar el comboBox Antes de llenarlo
						objeto.length = 0;
						//Agregar el Primer Elemento Vacio
						objeto.length++;
						objeto.options[objeto.length-1].text=etqCombo;
						objeto.options[objeto.length-1].value="";
					}
				}
			}
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox Dependiente
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text=etqCombo;
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCmb(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_cmb.readyState==READY_STATE_COMPLETE){
			if(peticion_http_cmb.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_cmb.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					var dato;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text=etqCombo;
					objeto.options[objeto.length-1].value="";
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("dato"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text=valor;
						//Si esto se carga desde un pedido, dividr los resultados para colocarlos en el value del combo
						if(opc=="pedido"){
							//En una variable de arreglo emergente, colocar el resultado del valor segun el XML
							var valorReal=valor.split(" - ");
							//Retornar a "valor" el primer parametro del valor seung el XML, este corresponde al PEDIDO
							valor=valorReal[0];
						}
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value=valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title=valor;
					}
				}
				else{//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
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
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCmb(url, metodo, funcion) {
		peticion_http_cmb = inicializa_xhr_cmb();
		if(peticion_http_cmb){
			peticion_http_cmb.onreadystatechange = funcion;
			peticion_http_cmb.open(metodo, url, true);
			peticion_http_cmb.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_cmb() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	