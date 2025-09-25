/**
  * Nombre del M�dulo: Seguridad Industrial                                              
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 14/Julio/2011                                       			
  * Descripci�n: Este archivo contiene la funcion que valida que el presupuesto no este incluido en otro 
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var pet_http_vd;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function verificarComplementoPerAlturas(idPer){
		var txt_nomTrabajador = document.getElementById("txt_nomTrabajador").value;
		var txt_nomAutoriza = document.getElementById("txt_nomAutoriza").value;
		var txa_trabRealizar = document.getElementById("txa_trabRealizar").value;
		var txt_lugar = document.getElementById("txt_lugar").value;
		var txa_desTrabajo = document.getElementById("txa_desTrabajo").value;
		var txa_riesgosTrab = document.getElementById("txa_riesgosTrab").value;
		var txt_liderOper = document.getElementById("txt_liderOper").value;

		
		if(txt_nomTrabajador!=""&&txt_nomAutoriza!=""&&txt_liderOper!=""&&txa_trabRealizar!=""&&txt_lugar!=""&&txa_desTrabajo!=""&&txa_riesgosTrab!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(validarDatos.js)
			var url = "includes/ajax/verificarComplementoPerAlturas.php?idPer="+idPer;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar 
			 *problemas con la cach� del navegador. Como cada petici�n
			 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n 
			 *directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			iniciarPeticion(url, "GET", procesarRegistros);	
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la 
	 *funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function iniciarPeticion(url, metodo, funcion) {
		pet_http_vd = inicializarObjetoXHR();
		if(pet_http_vd) {
			pet_http_vd.onreadystatechange = funcion;
			pet_http_vd.open(metodo, url, true);
			pet_http_vd.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}	
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarRegistros(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(pet_http_vd.readyState==READY_STATE_COMPLETE){
			if(pet_http_vd.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = pet_http_vd.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){		
					document.getElementById("hdn_boton").value="si";
					document.getElementById("sbt_continuar").disabled=false;
				}
				else{
					document.getElementById("sbt_continuar").disabled=true;
					document.getElementById("hdn_boton").value="no";
					alert("El Permiso NO cuenta con el Complemento de las Condiciones de Seguridad; Verifique Registro");
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()