/**
  * Nombre del Módulo: Compras
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 24/Enero/2015                                      			
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada del detalle de una requisicion
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var nomCmb;
	var control;
	var cuenta;
	var control2;
	var equipo_sel;
	var cmb_con;
	var cmb_cue;
	var cmb_sub;
	var hdn_cc;
	var hdn_cue;

	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. controlC: Control del que se buscaran cuentas
	 * 2. combo: Combo en el que se van a cargar los datos
	 ******************************************************************************************/
	
	function cargarCuentas(controlC,combo){
		nomCmb=combo;
		control=controlC;
		if(controlC!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboCuentas.php?controlC="+controlC;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegadorestará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbCuentas(url, "GET", cargarDatosCmbCuentas);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Cuentas";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion
			
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbCuentas(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					var texto;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "Cuentas";
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = texto;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = texto;
					}
				}
				else{
					//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "No hay cuentas para el control de costos";
					objeto.options[objeto.length-1].value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbDetalle()
	
	function cargarSubCuentas(control2C,cuentaC,combo){
		nomCmb=combo;
		control2=control2C;
		cuenta=cuentaC;
		if(cuentaC!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboCuentas.php?control2C="+control2C+"&cuentaC="+cuentaC;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegadorestará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbCuentas(url, "GET", cargarDatosCmbSubCuentas);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="SubCuentas";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbSubCuentas(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
				
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					var valor;
					var texto;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "SubCuentas";
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = texto;
						//Agregar el valor del atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = texto;
					}
				}
				else{
					//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "No hay Subcuentas para la cuenta";
					objeto.options[objeto.length-1].value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbDetalle()
	
	function cargarCategroias(control2C,cuentaC,combo){
		nomCmb=combo;
		control2=control2C;
		cuenta=cuentaC;
		if(cuentaC!=""){			
			var url = "includes/ajax/cargarComboCuentas.php?control2C="+control2C+"&cuentaC2="+cuentaC;
			url += "&nocache=" + Math.random();	
			cargaContenidoCmbCuentas(url, "GET", cargarDatosCategorias);
		}
		else{
			objeto = document.getElementById(nomCmb);
			objeto.length = 0;
			objeto.length++;
			objeto.options[objeto.length-1].text="Categorias";
			objeto.options[objeto.length-1].value="";
		}
	}
	
	function cargarDatosCategorias(){				
		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				var respuesta = peticion_equipo_mtto.responseXML;
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
				
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					var valor;
					var texto;
					objeto = document.getElementById(nomCmb);					
					objeto.length = 0;
					objeto.length++;
					objeto.options[objeto.length-1].text = "Categorias";
					objeto.options[objeto.length-1].value = "";
					
					for(var i=0;i<tam;i++){												
						valor = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						objeto.length++;
						objeto.options[objeto.length-1].text = texto;
						objeto.options[objeto.length-1].value = valor;
						objeto.options[objeto.length-1].title = texto;
					}
					objeto.value=document.getElementById("txt_cat").value;
				}
				else{
					objeto = document.getElementById(nomCmb);					
					objeto.length = 0;
					objeto.length++;
					objeto.options[objeto.length-1].text = "No hay categorias para las opciones seleccionadas";
					objeto.options[objeto.length-1].value = "";
				}
			}
		}
	}
	
	function cargarCuentas_Equipo(equipo, combo_con, combo_cuen, combo_sub){
		cmb_con = combo_con;
		cmb_cue = combo_cuen;
		cmb_sub = combo_sub;
		
		equipo_sel=equipo;
		if(equipo!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboCuentas.php?equipo="+equipo;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegadorestará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbCuentas(url, "GET", cargarDatosCmbCuentas_Equipo);
		}
	}//Fin de la Funcion
			
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbCuentas_Equipo(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var control_costos;
					var cuenta;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(""+cmb_con);
					objeto2 = document.getElementById(""+cmb_cue);
					objeto3 = document.getElementById(""+cmb_sub);
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){
						//Obtener cada uno de los datos que serán cargados en el Combo
						control_costos = respuesta.getElementsByTagName("control"+(i+1)).item(0).firstChild.data;
						cuenta = respuesta.getElementsByTagName("cuenta"+(i+1)).item(0).firstChild.data;
					}
					objeto.value = control_costos;
					
					cargarCuentas(control_costos,cmb_cue);
					setTimeout("objeto2.value='"+cuenta+"'",200);
					setTimeout("cargarSubCuentas('"+control_costos+"','"+cuenta+"','"+cmb_sub+"')",400);
					setTimeout("cargarCategroias('"+control_costos+"','"+cuenta+"','cmb_cat')",600);
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbDetalle()
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCmbCuentas(url, metodo, funcion) {
		peticion_equipo_mtto = iniciar_xhr_req();
		if(peticion_equipo_mtto){
			peticion_equipo_mtto.onreadystatechange = funcion;
			peticion_equipo_mtto.open(metodo, url, true);
			peticion_equipo_mtto.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	