/**
  * Nombre del Módulo: Mantenimiento                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Octubre/2012
  * Descripción: Este archivo contiene la función que carga el Catálogo de Llantas en Mtto
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;
	var combo;
	var numLlanta;

	/*Esta función que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function obtenerLlanta(comboLlanta){
		if (comboLlanta.value!=""){
			if(comboLlanta.value=="NUEVALLANTA"){
				//Resetear el formulario
				document.forms.frm_gestionLlantas.reset();
				//Asignar el valor por default a Agregar Llanta
				document.getElementById("cmb_llanta").value="NUEVALLANTA";
				//Linea que muestra un mensaje donde guardar la nueva Area
				var linea = prompt("Ingresar Número de la Llanta","Número de la Llanta...");
				//Verificar si el dato introducido es valido
				if(linea!=null && linea!="Número de la Llanta..." && linea!="" && !isNaN(linea)){
					linea=linea.toUpperCase();
					//Variable para revisar los caracteres de error
					var error=0;
					//Recorrer el dato ingresado buscando caracteres prohibidos
					for(i=0;i<linea.length;i++){
						//Igualamos el valor de seccion a car para su facil manejo
						car = linea.charAt(i);
						if(car=='%'||car=='&'||car=='"'){
							error=1;
							break;
						}
					}//Cierre for(i=0;i<linea.length;i++)
					if(error==0){
						//Variable que permite verificar si existe un dato o no en el combo de referencia
						var existe=0;
						for(i=0; i<document.getElementById("cmb_llanta").length; i++){
							//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
							if(document.getElementById("cmb_llanta").options[i].value==linea)
								existe = 1;
						} //FIN for(i=0; i<comboBox.length; i++)
						if (existe==1){
							alert("La Llanta ya existe");
							document.getElementById("cmb_llanta").value=linea;
						}
						//Si el area existe, no continuar con el proceso
						if(existe!=1){
							//Agregar al final la nueva opcion seleccionada
							comboLlanta.length++;
							comboLlanta.options[comboLlanta.length-1].text = linea;
							//Ingresar un value aleatorio a la Llanta, solo para el manejo de la misma
							comboLlanta.options[comboLlanta.length-1].value = linea;
							//Preseleccionar la opcion agregada
							comboLlanta.options[comboLlanta.length-1].selected = true;
							//Mover el foco al siguiente Elemento
							document.getElementById("cmb_marca").focus();
						}
					}
					else{
						alert("El Dato "+linea+" Ingresado No Es Válido");
						document.getElementById("cmb_llanta").value = "";
					}
				}
				else{
					//Deshabilitar los elementos del formulario
					if(linea!=null && isNaN(linea))
						alert("El Número de la Llanta No debe Contener Letras");
					else
						alert("Dato Ingresado No Válido");
					document.getElementById("cmb_llanta").value = "";
				}
			}
			if(comboLlanta.value!=""){
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
				var url = "includes/ajax/cargarCatalogoLlantas.php?idLlanta="+comboLlanta.value;
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
				*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				cargarInformacion(url, "GET", procesarLlanta);
			}
		}
		else{
			//Resetear el formulario
			document.forms.frm_gestionLlantas.reset();
			//Asignar el valor por default a Agregar Llanta
			document.getElementById("cmb_llanta").value="";
		}
	}//Fin de la Funcion obtenerSueldo(campo)	

	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarLlanta(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var marca = respuesta.getElementsByTagName("marca").item(0).firstChild.data;
					var medida = respuesta.getElementsByTagName("medida").item(0).firstChild.data;
					var medidaRin = respuesta.getElementsByTagName("rin").item(0).firstChild.data;
					var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;
					var costo = respuesta.getElementsByTagName("costo").item(0).firstChild.data;
					var ubicacion = respuesta.getElementsByTagName("ubicacion").item(0).firstChild.data;
					var disponible = respuesta.getElementsByTagName("disponible").item(0).firstChild.data;
					//Asignar los valores encontrados
					document.getElementById("cmb_marca").value=marca;
					document.getElementById("cmb_ubicacion").value=ubicacion;
					document.getElementById("cmb_estado").value=estado;
					document.getElementById("txt_costo").value=costo;
					document.getElementById("cmb_disponibilidad").value=disponible;
					document.getElementById("txt_medida").value=medida;
					document.getElementById("txt_medidaRin").value=medidaRin;
					//Cambiar el valor de la variable hidden, a fin de actualizar
					document.getElementById("hdn_estado").value="Actualizar";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarInformacion(url, metodo, funcion) {
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