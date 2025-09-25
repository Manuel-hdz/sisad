/**
  * Nombre del Módulo: Almacen
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 04/Abril/2012                                      			
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada del Personal en RH
  */
	
	//Permite utilizar funciones de otro archivo .js
	document.write("<script type='text/javascript' src='cargarComboCuentas.js'></script>");
	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var nomCmb;
	var depto;

	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. area: Nombre del Area a donde se buscara al Trabajador
	 * 2. combo: Combo en el que se van a cargar los datos
	 ******************************************************************************************/
	function cargarPersonalRHArea(area,combo){
		
		nomCmb=combo;
		depto=area;
		//Si no ha sido seleccionada ninguna familia, vaciar el combo que con
		if(area!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboPersonalRH.php?area="+area;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbRHArea(url, "GET", cargarDatosCmbRHArea);
		}
		else{//Cuando sea seleccionada una opcion vacia, vaciar el comboBox que contiene los datos resultantes de la consulta
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById(nomCmb);
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Solicitante";
			objeto.options[objeto.length-1].value="";
		}
	}//Fin de la Funcion cargarEquiposFamilia(familia,area,nomCmbCargar,etiqCombo,valSeleccionado)
	
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbRHArea(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;					
				if (existe=="true"){
					if(depto!="OTRO"){//Si el valor del combo de Area es diferente de OTRO, declarar en el SPAN el combo donde se cargaran los empleados
						document.getElementById("datosSolicitante").innerHTML="<select name=\"txt_solicitante\" id=\"txt_solicitante\" class=\"combo_box\"><option value=\"\">Solicitante</option></select>";
					}
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById(nomCmb);					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "Solicitante";
					objeto.options[objeto.length-1].value = "";
					
					//Recorrer la respuesta XML para colocar los valores del ComboBox
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que serán cargados en el Combo
						valor = respuesta.getElementsByTagName("empleado"+(i+1)).item(0).firstChild.data;
						//Aumentar en 1 el tamaño del comboBox
						objeto.length++;
						//Agregar el dato que sera mostrado
						objeto.options[objeto.length-1].text = valor;
						//Agregar el valor dela atributo value
						objeto.options[objeto.length-1].value = valor;
						//Colocarl el valor de la Id en el Atributo Title
						objeto.options[objeto.length-1].title = valor;
					}
				}
				else{
					if(depto=="OTRO"){//Si no se encuentran datos, verificar si es porque se selecciono la opcion OTRO, en dicho caso sustituir el combo por una caja de Texto para ingresar el nombre
						//Para poder escribir las comillas y declarar una funcion, se utiliza el caracter de espace \ asi, permite ingresar el Texto
						document.getElementById("datosSolicitante").innerHTML="<input name=\"txt_solicitante\" type=\"text\" class=\"caja_de_texto\" size=\"30\" maxlength=\"60\" onkeypress=\"return permite(event,'car');\"/>";
					}
					else{
						//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
						//Obtener la referencia del comboBox que será cargado con los datos
						objeto = document.getElementById(nomCmb);					
						//Vaciar el comboBox Antes de llenarlo
						objeto.length = 0;
						//Agregar el Primer Elemento Vacio
						objeto.length++;
						objeto.options[objeto.length-1].text = "No Hay Personal Registrado";
						objeto.options[objeto.length-1].value = "";
					}
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbEquipoMtto()
	
	
	/*Funcion que segun el numero de Empleado, carga los combos con los datos*/
	function extraerInfoEmpCB(cajaEmpleado,procedencia){
		var numEmp=cajaEmpleado.value
		if(numEmp == "LIM"){
			restablecerComboSalida();
			txt_codBarTrabajador.focus();
			document.frm_datosSalida.reset();
		}
		else if(numEmp == "CACA"){
			if(procedencia == "1"){
				document.location.href="frm_salidaMaterialBC.php";
			}
			else if(procedencia == "0"){
				document.location.href="frm_salidaMaterial.php";
			}
		}
		//Si no ha sido seleccionada ninguna familia, vaciar el combo que con
		else if(numEmp!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarComboPersonalRH.php?numEmp="+numEmp;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCmbRHArea(url, "GET", cargarDatosCmbRHCB);
		}
	}
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosCmbRHCB(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;					
				if (existe=="true"){
					//Obtener cada uno de los datos que serán cargados en el Combo
					area = respuesta.getElementsByTagName("area").item(0).firstChild.data;
					empleado = respuesta.getElementsByTagName("empleado").item(0).firstChild.data;
					costos = respuesta.getElementsByTagName("costos").item(0).firstChild.data;
					cuentas = respuesta.getElementsByTagName("cuentas").item(0).firstChild.data;
					//Asignar al combo de Area el Valor encontrado
					document.getElementById("txt_deptoSolicitante").value=area;
					//Ejecutar la Funcion que carga los nombres de los Empleados del Area seleccionada
					cargarPersonalRHArea(area,"txt_solicitante");
					//Seleccionar al Empleado al que corresponde el Id de la credencial escaneada
					setTimeout("document.getElementById('txt_solicitante').value=empleado",300);
					
					if(costos!="sin_dato"){
						//Asignar al combo de control de costos el Valor encontrado
						document.getElementById("cmb_con_cos").value=costos;
						//Ejecutar la Funcion que carga las cuentas de los Empleados del control de costos seleccionado
						setTimeout("cargarCuentas(costos,'cmb_cuenta')",400);
						//Seleccionar la Cuenta que corresponde el Id de la credencial escaneada
						setTimeout("document.getElementById('cmb_cuenta').value=cuentas",500);
						//Ejecutar la Funcion que carga las subcuentas de los Empleados de la cuenta seleccionada
						setTimeout("cargarSubCuentas(costos,cuentas,'cmb_subcuenta')",600);
					}
				}
				else{
					//Cuando el elemento seleccionado no arroge ningun resultado, vaciar el comboBox en caso de que contenga datos de otra opcion
					//Obtener la referencia del comboBox que será cargado con los datos
					objeto = document.getElementById("txt_solicitante");					
					//Vaciar el comboBox Antes de llenarlo
					objeto.length = 0;
					//Agregar el Primer Elemento Vacio
					objeto.length++;
					objeto.options[objeto.length-1].text = "Solicitante";
					objeto.options[objeto.length-1].value = "";
					document.getElementById("txt_deptoSolicitante").value="";
					alert("Trabajador No Encontrado");
					document.getElementById("txt_codBarTrabajador").value="";
					document.getElementById("txt_codBarTrabajador").focus();
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbEquipoMtto()
		
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCmbRHArea(url, metodo, funcion) {
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
	
	