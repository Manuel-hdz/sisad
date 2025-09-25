/**
  * Nombre del M�dulo: Desarrollo                                               
  * �CONCRETO LANZADO DE FRESNILLO MARCA
  * Fecha: 20/Octubre/2011                                       			
  * Descripci�n: Este archivo contiene la funcion que carga el Catalogo de Sueldos en caso de que exista
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n que verifica que una fecha no se encuentre dentro del rango de otra ya registrada en la bd */
	function cargarTurno(turno){
		if (turno!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/cargarCatalogoTurnos.php?turno="+turno;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaTurno(url, "GET", procesarTurno);
		}
		else{
			document.getElementById("txt_horaE").value=document.getElementById("txt_horaE").defaultValue;
			document.getElementById("txt_horaS").value=document.getElementById("txt_horaS").defaultValue;
			document.frm_catalogoTurnos.txa_comentarios.value="";
		}
	}//Fin de la Funcion obtenerSueldo(campo)
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaTurno(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
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
	function procesarTurno(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var id = respuesta.getElementsByTagName("id").item(0).firstChild.data;
					var turno = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					var horaE = respuesta.getElementsByTagName("horaE").item(0).firstChild.data;
					//Meridiano Entrada
					var merE = respuesta.getElementsByTagName("merE").item(0).firstChild.data;
					if (merE=="am")
						merE=0;
					else
						merE=1;
					//Meridiano Salida
					var horaS = respuesta.getElementsByTagName("horaS").item(0).firstChild.data;
					var merS = respuesta.getElementsByTagName("merS").item(0).firstChild.data;
					if (merS=="am")
						merS=0;
					else
						merS=1;
					
					var comentarios = respuesta.getElementsByTagName("comentarios").item(0).firstChild.data;
					document.getElementById("txt_horaE").value=horaE;
					document.getElementById("txt_horaS").value=horaS;
					document.getElementById("cmb_horaE").selectedIndex=merE;
					document.getElementById("cmb_horaS").selectedIndex=merS;
					document.frm_catalogoTurnos.txa_comentarios.value=comentarios;
					document.getElementById("hdn_estado").value="Modificar";
				}
				else{
					document.getElementById("hdn_estado").value="Agregar";

				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()