/**
  * Nombre del Módulo: Comaro
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 02/Septiembre/2015
  * Descripción: Este archivo se encarga de llenar un comboBox con la información solicitada del Personal en RH
  */

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
	/*Funcion que segun el numero de Empleado, carga los combos con los datos*/
	function extraerInfoEmpCB(cajaEmpleado){
		var numEmp=cajaEmpleado.value;
		//Si no ha sido seleccionada ninguna familia, vaciar el combo que con
		if(numEmp!=""){			
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarInfoEmpleado.php?numEmp="+numEmp;	
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenido(url, "GET", cargarDatosEmpleado);
		}
	}
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function cargarDatosEmpleado(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;					
				if (existe=="true"){
					empleado = respuesta.getElementsByTagName("empleado").item(0).firstChild.data;
					//Asignar al combo de Area el Valor encontrado
					document.getElementById("txt_empleado").value=empleado;
				}
				else{
					document.getElementById("txt_empleado").value="";
					alert("Trabajador No Encontrado");
					document.getElementById("txt_codBarTrabajador").value="";
					document.getElementById("txt_codBarTrabajador").focus();
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosCmbEquipoMtto()
		
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenido(url, metodo, funcion) {
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
	
	