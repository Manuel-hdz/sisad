/**
  * Nombre del Módulo: SISAD
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 30/Mayo/2012
  * Descripción: Este archivo contiene las funciones para buscar una clave repetida en la BD, a excepcion del dato original
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	//Esta varible almacenara la petición realizada al Servidor de culquiera de las funciones declaradas en este archivo
	var peticion_http_txt;	
	//Esta variable almacenara el boton,campo con la informacion y el nombre del formulario para trabajar con ellos de forma global
	var boton;
	var campoContenido;
	var formulario;
	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. base: Base de Datos a conectarse
	 * 2. tabla: Nombre de la Tabla con la informacion
	 * 3. campoRef: Campo a partir del cual se hara la busqueda, en, su mayoria sera el campo Clave
	 * 4. campo: Campo de Texto donde se encuentra contenido el Texto, por lo regular sera el que dispare la funcion
	 * 5. valorOriginal: Valor Original a excluir
	 * 6. botonFormulario: Boton a activar o desactivar segun sea el caso
	 * 7. form: Nombre del Formulario donde se encuentran los datos para verificar su estilo
	 ******************************************************************************************/
	function validarCambioClave(base,tabla,campoRef,campo,valorOriginal,botonFormulario,form){
		//Obtener el boton del Formulario a deshabilitar
		boton=botonFormulario;
		//Obtener el ID del Campo donde se encuentra el dato
		campoContenido=campo.id;
		//Obtener el Valor Nuevo del Campo
		valorNuevo=campo.value;
		//Obtener el nombre del formulario
		formulario=form;
		//Si no ha sido seleccionado ningun valor no hacer nada
		if(valorNuevo!="" && valorNuevo!=valorOriginal){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerDatoBD.js)
			var url = "../../includes/ajax/validarCambioDato.php?valorNuevo="+valorNuevo+"&valorOriginal="+valorOriginal+"&tabla="+tabla+"&campoRef="+campoRef+"&base="+base;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoCambioClave(url, "GET", procesarRespuestaCambioClave);
		}
		else{
			//Variable bandera para activar el boton en el momento que sea requerido
			var band=0;
			//Quitar el posible color amarillo que haya tomado
			document.getElementById(campoContenido).style.background="FFF";					
			//Contar la cantidad de Formularios que hay en la pagina
			cantForms=document.forms.length;
			//Contador para recorrer TODOS los Formularios
			i=0;
			//Recorrer todos los formularios declarados en la pagina actual
			do{
				//Verificar si el formulario tiene el mismo nombre del pasado por parametro, sino pasar al siguiente
				if(document.forms[i].name==formulario){
					//Recorrer el arreglo de Elementos del Formulario
					for (ind=0;ind<document.forms[i].elements.length;ind++){ 
						//Verificar si los elementos son de tipo Texto
						if(document.forms[i].elements[ind].type=="text"){
							//Obtener el ID del Elemento en caso de ser Texto
							idElemento=document.forms[i].elements[ind].id;
							//Verificar que el IdElemento sea diferente de vacio
							if(idElemento!=""){
								//Si el Elemento tiene color de Fondo igual a Amarillo-(#FF0) activar la bandera
								if(document.getElementById(idElemento).style.background=="#ff0")
									band=1;
							}//if(idElemento!="")
						}//if(document.forms[i].elements[i].type=="text")
					}//Fin del For
				}
				i++;
			}while(i<cantForms);
			//Si la bandera no se activo, quiere decir que se puede activar el Boton
			if(band==0)
				document.getElementById(boton).disabled=false;
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuestaCambioClave(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_txt.readyState==READY_STATE_COMPLETE){
			if(peticion_http_txt.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_txt.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Recuperar datos del Archivo XML					
					var dato = respuesta.getElementsByTagName("dato").item(0).firstChild.data;
					alert("El Dato "+dato+" ya se Encuentra Asignado, favor de Ingresar un Dato Diferente");
					document.getElementById(boton).disabled=true;
					//Modificar el color de Fondo del Cuadro donde viene el Dato
					document.getElementById(campoContenido).style.background="FF0";
				}
				else{
					//Variable bandera para activar el boton en el momento que sea requerido
					var band=0;
					//Quitar el posible color amarillo que haya tomado
					document.getElementById(campoContenido).style.background="FFF";					
					//Contar la cantidad de Formularios que hay en la pagina
					cantForms=document.forms.length;
					//Contador para recorrer TODOS los Formularios
					i=0;
					//Recorrer todos los formularios declarados en la pagina actual
					do{
						//Verificar si el formulario tiene el mismo nombre del pasado por parametro, sino pasar al siguiente
						if(document.forms[i].name==formulario){
							//Recorrer el arreglo de Elementos del Formulario
							for (ind=0;ind<document.forms[i].elements.length;ind++){ 
								//Verificar si los elementos son de tipo Texto
								if(document.forms[i].elements[ind].type=="text"){
									//Obtener el ID del Elemento en caso de ser Texto
									idElemento=document.forms[i].elements[ind].id;
									//Verificar que el IdElemento sea diferente de vacio
									if(idElemento!=""){
										//Si el Elemento tiene color de Fondo igual a Amarillo-(#FF0) activar la bandera
										if(document.getElementById(idElemento).style.background=="#ff0")
											band=1;
									}//if(idElemento!="")
								}//if(document.forms[i].elements[i].type=="text")
							}//Fin del For
						}
						i++;
					}while(i<cantForms);
					//Si la bandera no se activo, quiere decir que se puede activar el Boton
					if(band==0)
						document.getElementById(boton).disabled=false;
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoCambioClave(url, metodo, funcion) {
		peticion_http_txt = inicializa_xhr_txt();
		if(peticion_http_txt){
			peticion_http_txt.onreadystatechange = funcion;
			peticion_http_txt.open(metodo, url, true);
			peticion_http_txt.send(null);
		}
	}
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_txt() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}