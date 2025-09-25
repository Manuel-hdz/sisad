/**
  * Nombre del M�dulo: Recursos Humanos                                               
  * �CONCRETO LANZADO DE FRESNILLO MARCA
  * Fecha: 06/Agosto/2012
  * Descripci�n: Este archivo contiene las funciones para validar las claves de los datos que ser�n registrados en la BD de manera Asincrona y de ese modo indicar al usuario cuando una
  * clave esta repetida en la BD antes de que envie los datos para su registro.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Guardar la Petici�n HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_empleado;
	var inc="";

	/*Esta funci�n obtendr� el dato que se quiere validar*/
	function guardarIncPeriodo(rfc,incidencia,comentario,fechaI,fechaF){
		if (incidencia!=''){
			inc=incidencia;
			//Convertir el comentario a mayusculas
			comentario = comentario.toUpperCase();
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
			var url = "includes/ajax/regKardexPeriodo.php?rfc="+rfc+"&inc="+incidencia+"&com="+comentario+"&fechaI="+fechaI+"&fechaF="+fechaF;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoRegKardex(url, "GET", procesarRegistroKardex);
		}
		else{
			alert("Seleccionar la Incidencia");
		}
	}//Fin de la Funcion verificarDatoBD(campo)		
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function procesarRegistroKardex(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_empleado.readyState==READY_STATE_COMPLETE){
			if(peticion_http_empleado.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_empleado.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				//Si genera Resultados, tiene Entradas Registradas, por lo tanto, desactivar los Elementos para Registro de Entrada
				if (existe=="true"){
					//Contar la cantidad de Formularios que hay en la pagina
					cantForms=document.forms.length;
					//Contador para recorrer TODOS los Formularios
					i=0;
					//Recorrer todos los formularios declarados en la pagina actual
					do{
						//Verificar si el formulario tiene el mismo nombre del que queremos restablecer
						if(document.forms[i].name=="frm_temp"){
							//Recorrer el arreglo de Elementos del Formulario
							for (ind=0;ind<document.forms[i].elements.length;ind++){
								//Si los elementos son de tipo diferente a checkbox y texto, deshabilitarlos
								if(document.forms[i].elements[ind].type=="text"){
									//Obtener el ID de cada Elemento
									idElemento=document.forms[i].elements[ind].id;
									//Verificar que el IdElemento sea diferente de vacio y deshabilitarlo
									if(idElemento!=""){
										//Cambiar el color de fondo de los Campos Tipo Text
										document.getElementById(idElemento).style.background="669900";
										//Si la incidencia es igual a A de asistencia, el color del texto sera Verde
										if (inc=="A")
											document.getElementById(idElemento).style.color="669900";
										//Para cualquier otra incidencia el color es blanco
										else
											document.getElementById(idElemento).style.color="FFF";
										//Escribir la incidencia en los campos tipo TEXT
										document.getElementById(idElemento).value=inc;
									}//if(idElemento!="")
								}//if(document.forms[i].elements[i].type=="text")
							}//Fin del For
						}
						i++;
					}while(i<cantForms);
				}
				//else{...}
			}//If if(peticion_http_empleado.status==200)
		}//If if(peticion_http_empleado.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuestaVal()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoRegKardex(url, metodo, funcion) {
		peticion_http_empleado = inicializa_xhr_empleado();
		if(peticion_http_empleado) {
			peticion_http_empleado.onreadystatechange = funcion;
			peticion_http_empleado.open(metodo, url, true);
			peticion_http_empleado.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializa_xhr_empleado() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}